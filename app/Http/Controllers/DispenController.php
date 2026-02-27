<?php

namespace App\Http\Controllers;

use App\Models\Dispen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\DispenStatusMail;
use App\Models\DispenDetail;
use App\Models\Siswa;

class DispenController extends Controller
{

public function index(Request $request)
{
    $limit = $request->limit ?? 5;
    $search = $request->search ?? '';
    $user = auth()->user();
    $showAll = $request->all == 'true';
   $query = Dispen::with([
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
    $guru = DB::table('users')
                ->where('role', 'guru')
                ->get();

    return view('auth.dispen', compact('kelas','guru'));
}


    // SIMPAN DATA
 public function store(Request $request)
{   
    $request->validate([
    'nis' => 'required|numeric|exists:siswa,nis',
    'nis_tambahan' => 'array|max:10',
    'nis_tambahan.*' => 'nullable|numeric|exists:siswa,nis',

    'email' => 'required|email',
    'no_hp' => 'required|numeric|digits_between:10,15',

    'id_guru' => 'required|exists:users,id_user',
    'alasan' => 'required|string'
]);

    // 🔥 Ambil data siswa utama
    $siswa = Siswa::where('nis', $request->nis)->first();

    // 🔹 Simpan data utama
    $dispen = Dispen::create([
    'nis' => $siswa->nis,
    'nama' => $siswa->nama,
    'kelas' => $siswa->kelas,
    'email' => $request->email,
    'no_hp' => $request->no_hp,
    'id_guru' => $request->id_guru,
    'alasan' => $request->alasan,
    'status' => 'dalam proses',
    'admin_action' => null,
    'guru_action' => null,
    'approved_by_admin' => null,
    'approved_by_guru' => null,
    'rejection_reason' => null
]);
    // 🔥 Simpan siswa tambahan
   if ($request->nis_tambahan) {
    foreach ($request->nis_tambahan as $nisTambahan) {

        if ($nisTambahan) {

            $siswaTambahan = Siswa::where('nis', $nisTambahan)->first();

            if ($siswaTambahan) {

                // 🔥 CEK KELAS
                if ($siswaTambahan->kelas !== $siswa->kelas) {
                    return back()->withErrors([
                        'nis_tambahan' => 'Siswa tambahan harus dari kelas yang sama.'
                    ])->withInput();
                }

                DispenDetail::create([
                    'id_dispen' => $dispen->id_dispen,
                    'nis' => $siswaTambahan->nis,
                    'nama' => $siswaTambahan->nama
                ]);
            }
        }
    }
}

    return redirect('/auth/dispen')
        ->with('success','Data berhasil dikirim');
}

public function show($id)
{
    $data = Dispen::with(['kelasRel','guru'])
        ->findOrFail($id);

    $detail = DispenDetail::where('id_dispen', $id)->get();
    $gurpik = DB::table('gurpik')->get();

    return view('dispen.detail', compact('data','gurpik','detail'));
}

public function getSiswa($nis)
{
    $siswa = \App\Models\Siswa::where('nis', $nis)->first();

    if ($siswa) {
        return response()->json([
            'status' => 'success',
            'nama'   => $siswa->nama,
            'kelas'  => $siswa->kelas
        ]);
    }

    return response()->json([
        'status' => 'error'
    ]);
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
$data->load(['guru', 'guruPiket']);
// mapping ke string (🔥 ini kunci utama)
$kelasNama = $data->kelas ?? '-';
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