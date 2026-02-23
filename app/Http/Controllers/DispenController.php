<?php

namespace App\Http\Controllers;

use App\Models\Dispen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\DispenStatusMail;
use App\Models\DispenDetail;


class DispenController extends Controller
{

public function index(Request $request)
{
    $limit = $request->limit ?? 5;
    $search = $request->search ?? '';
    $user = auth()->user();
    $showAll = $request->all == 'true';
   $query = Dispen::with([
    'kelasRel',
    'jamKeluar',
    'jamKembali',
    'guru'
]);

// Jika bukan selengkapnya → tetap filter hari ini
if (!$showAll) {
    $query->whereDate('created_at', now());
}


    // 🔥 Kalau yang login guru → hanya tampil data miliknya
    if ($user->role == 'guru') {
        $query->where('id_guru', $user->id_user);
    }

    // 🔎 Search
    $query->when($search, function ($q) use ($search) {
        $q->where(function ($sub) use ($search) {
            $sub->where('nama', 'like', "%$search%")
                ->orWhere('nis', 'like', "%$search%");
        });
    });

    if ($showAll) {
    $dispen = $query->orderByDesc('id_dispen')->get();
} else {
    $dispen = $query->orderByDesc('id_dispen')->paginate($limit);
}

    return view('dispen.index', compact('dispen','limit','search'));
}

    // FORM INPUT
   public function create()
{   
    $kelas = DB::table('kelas')->get();
    $jampel = DB::table('jampel')->get();
    $guru = DB::table('users')
                ->where('role', 'guru')
                ->get();

    return view('auth.dispen', compact('kelas','jampel','guru'));
}


    // SIMPAN DATA
  public function store(Request $request)
{   
    $request->validate([
        'nis' => 'required|numeric',
        'nama' => 'required|string',

        'nis_tambahan' => 'array|max:10',
        'nama_tambahan' => 'array|max:10',

        'kelas' => 'required',
        'jam_keluar' => 'required',
        'jam_kembali' => 'required',
        'email' => 'required|email',
        'id_guru' => 'required|exists:users,id_user',
        'alasan' => 'required|string'
    ]);

    // 🔹 simpan data utama
    $dispen = Dispen::create([
        'nis' => $request->nis,
        'nama' => $request->nama,
        'kelas' => $request->kelas,
        'jam_keluar' => $request->jam_keluar,
        'jam_kembali' => $request->jam_kembali,
        'email' => $request->email,
        'id_guru' => $request->id_guru,
        'alasan' => $request->alasan,
        'status' => 'dalam proses',
        'admin_action' => null,
        'guru_action' => null,
        'approved_by_admin' => null,
        'approved_by_guru' => null,
        'rejection_reason' => null
    ]);

    // 🔥 simpan siswa tambahan
    if ($request->nama_tambahan) {
        foreach ($request->nama_tambahan as $i => $nama) {

            if (!empty($nama) && !empty($request->nis_tambahan[$i])) {

                DispenDetail::create([
                    'id_dispen' => $dispen->id_dispen,
                    'nis' => $request->nis_tambahan[$i],
                    'nama' => $nama
                ]);

            }
        }
    }

    return redirect('/auth/dispen')
        ->with('success','Data berhasil dikirim');
}

public function show($id)
{
    $data = DB::table('dispen as d')
        ->leftJoin('kelas as k', 'd.kelas', '=', 'k.id_kelas')
        ->leftJoin('jampel as jk', 'd.jam_keluar', '=', 'jk.id_jampel')
        ->leftJoin('jampel as jb', 'd.jam_kembali', '=', 'jb.id_jampel')
        ->leftJoin('users as u', 'd.id_guru', '=', 'u.id_user')
        ->where('d.id_dispen', $id)
        ->select(
            'd.*',
            'k.klas as kelas',
            'jk.jam as jam_keluar',
            'jb.jam as jam_kembali',
            'u.username as guru'
        )
        ->first();
    $detail = DB::table('dispen_detail')
    ->where('id_dispen', $id)
    ->get();
    $gurpik = DB::table('gurpik')->get();

    if (!$data) {
        abort(404);
    }

    return view('dispen.detail', compact('data','gurpik','detail'));

}

public function destroy($id)
{
    Dispen::where('id_dispen', $id)->delete();

    return redirect()->route('dispen.index')
        ->with('success', 'Data berhasil dihapus');
}

    // LOGIC FINAL STATUS
   private function updateStatus($data)
{   
    // load relasi biar tidak jadi object mentah
$data->load(['kelasRel', 'jamKeluar', 'jamKembali', 'guru', 'guruPiket']);
// mapping ke string (🔥 ini kunci utama)
$kelasNama = optional($data->kelasRel)->klas ?? '-';
$jamKeluarNama = optional($data->jamKeluar)->jam ?? '-';
$jamKembaliNama = optional($data->jamKembali)->jam ?? '-';
$guruNama = optional($data->guru)->username ?? '-';
$guruPiketNama = optional($data->guruPiket)->gurpi ?? '-';
    if (!empty($data->admin_action) && !empty($data->guru_action)) {

        // =========================
        // FINAL STATUS
        // =========================
        if ($data->guru_action === 'setuju') {
            $data->status = 'disetujui';
        } else {
            $data->status = 'ditolak';
        }

        $data->save();

        // =========================
        // AMBIL DETAIL TAMBAHAN
        // =========================
        $detail = DispenDetail::where('id_dispen', $data->id_dispen)->get();

        // =========================
        // 🔥 FORMAT STATUS EMAIL
        // =========================
        $status_guru_pengajar = [];
        $status_guru_piket = [];

        // ===== GURU PENGAJAR =====
if ($data->guru_action === 'tolak') {
    $status_guru_pengajar[] = [
        'type' => 'rejected',
        'text' => "Ditolak oleh guru pengajar ({$guruNama})"
    ];

    if ($data->rejection_reason) {
        $status_guru_pengajar[] = [
            'type' => 'rejected',
            'text' => "Alasan: {$data->rejection_reason}"
        ];
    }

} elseif ($data->guru_action === 'setuju') {
    $status_guru_pengajar[] = [
        'type' => 'approved',
        'text' => "Disetujui oleh guru pengajar ({$guruNama})"
    ];
} else {
    $status_guru_pengajar[] = [
        'type' => 'pending',
        'text' => "Menunggu persetujuan guru pengajar ({$guruNama})"
    ];
}

       // ===== GURU PIKET =====
if ($data->admin_action === 'tolak') {
    $status_guru_piket[] = [
        'type' => 'rejected',
        'text' => "Ditolak oleh guru piket ({$guruPiketNama})"
    ];

    if ($data->rejection_reason) {
        $status_guru_piket[] = [
            'type' => 'rejected',
            'text' => "Alasan: {$data->rejection_reason}"
        ];
    }

} elseif ($data->admin_action === 'setuju') {
    $status_guru_piket[] = [
        'type' => 'approved',
        'text' => "Disetujui oleh guru piket ({$guruPiketNama})"
    ];
} else {
    $status_guru_piket[] = [
        'type' => 'pending',
        'text' => "Menunggu persetujuan guru piket ({$guruPiketNama})"
    ];
}

        // =========================
        // SET PENGIRIM EMAIL
        // =========================
        config([
            'mail.from.address' => auth()->user()->email,
            'mail.from.name' => auth()->user()->username
        ]);

        // =========================
        // KIRIM EMAIL
        // =========================
        Mail::to($data->email)
    ->send(new DispenStatusMail(
        $data,
        $detail,
        $status_guru_pengajar,
        $status_guru_piket,
        $kelasNama,
        $jamKeluarNama,
        $jamKembaliNama,
        $guruNama,
        $guruPiketNama
    ));

        return;
    }

    // =========================
    // STATUS SEMENTARA
    // =========================
    elseif (!empty($data->admin_action)) {
        $data->status = 'menunggu persetujuan guru';
    }

    elseif (!empty($data->guru_action)) {
        $data->status = 'menunggu persetujuan admin';
    }

    else {
        $data->status = 'dalam proses';
    }

    $data->save();
}

    public function actionAdmin(Request $request, $id)
{
    $data = Dispen::findOrFail($id);

    // 🚫 CEK SUDAH PERNAH AKSI
    if($data->admin_action){
        return back()->with('error','Anda sudah melakukan aksi.');
    }

    $request->validate([
        'action' => 'required|in:setuju,tolak',
        'id_gurupik' => 'required|exists:gurpik,id_guru',
        'alasan' => 'nullable|string'
    ]);

    $data->admin_action = $request->action;
    $data->approved_by_admin = auth()->id();
    $data->gurpi = $request->id_gurupik;

    if($request->action == 'tolak'){
        $data->rejection_reason = $request->alasan;
    }

    $data->save();
    $this->updateStatus($data);

    return back()->with('success','Aksi admin berhasil');
}

public function actionGuru(Request $request, $id)
{
    $data = Dispen::findOrFail($id);

    // 🚫 CEK SUDAH PERNAH AKSI
    if($data->guru_action){
        return back()->with('error','Anda sudah melakukan aksi.');
    }

    $request->validate([
        'action' => 'required|in:setuju,tolak',
        'alasan' => 'nullable|string'
    ]);

    $data->guru_action = $request->action;
    $data->approved_by_guru = auth()->id();

    if($request->action == 'tolak'){
        $data->rejection_reason = $request->alasan;
    }

    $data->save();
    $this->updateStatus($data);

    return back()->with('success','Aksi guru berhasil');
}
    
}