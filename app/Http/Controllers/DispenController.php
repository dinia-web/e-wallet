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
    $user = auth()->user();
    $limit = $request->limit ?? 5;
    $search = $request->search ?? '';
    $showAll = $request->all == 'true';

    $query = Dispen::with(['detail.siswa','guru']);

    // =========================
    // FILTER ROLE
    // =========================
    if ($user->role == 'guru') {
        // guru hanya melihat dispen yang diajar
        $query->where('id_guru', $user->id_user);

        // jika guru ingin lihat semua → bisa pakai ?all=true
        if (!$showAll) {
            $query->whereDate('created_at', now());
        }

    } elseif ($user->role == 'siswa') {
        // siswa hanya melihat dispen miliknya
        $query->whereHas('detail', function ($q) use ($user) {
            $q->where('nis', $user->nis);
        });

        // 🟢 siswa **tidak dibatasi tanggal**
        // $showAll irrelevant → tampil semua
    } else {
        // admin
        if (!$showAll) {
            $query->whereDate('created_at', now());
        }
    }

    // =========================
    // SEARCH
    // =========================
    $query->when($search, function ($q) use ($search) {
        $q->whereHas('detail', function ($sub) use ($search) {
            $sub->where('nama','like',"%$search%")
                ->orWhere('nis','like',"%$search%");
        });
    });

    // =========================
    // PAGINATION
    // =========================
    if ($user->role == 'siswa' || $showAll) {
        // PAGINATION
        $dispen = $query->orderByDesc('id_dispen')->paginate($limit);
    } else {
        $dispen = $query->orderByDesc('id_dispen')->paginate($limit);
    }

    return view('dispen.index', compact('dispen','limit','search'));
}
    // FORM INPUT
 public function create()
{   
    // ✅ Ambil kelas unik dari tabel siswa
    $kelas = Siswa::select('kelas')
                    ->whereNotNull('kelas')
                    ->distinct()
                    ->orderBy('kelas')
                    ->get();

    $guru = DB::table('users')
                ->where('role', 'guru')
                ->get();

    return view('auth.dispen', compact('kelas','guru'));
}


    // SIMPAN DATA
 public function store(Request $request)
{   
    $request->validate([
    'tipe' => 'required|in:individu,kelompok',

    'nis' => 'required_if:tipe,individu|nullable|numeric|exists:siswa,nis',

    'kelas_kelompok' => 'required_if:tipe,kelompok|nullable|string',

    'nis_tambahan' => 'array|max:10',
    'nis_tambahan.*' => 'nullable|numeric|exists:siswa,nis',

    'email' => 'required|email',
    'no_hp' => 'required|numeric|digits_between:10,15',

    'id_guru' => 'required|exists:users,id_user',
    'alasan' => 'required|string'
]);

// 🔥 Ambil data siswa utama
$siswa = Siswa::where('nis', $request->nis)->first();

// 🔹 Simpan header (tanpa nis/nama/kelas kalau sudah dihapus dari tabel)
$dispen = Dispen::create([
    'tipe' => $request->tipe,
    'kelas_kelompok' => $request->kelas_kelompok,
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

   if ($request->tipe === 'kelompok') {

    $siswaKelas = Siswa::where('kelas', $request->kelas_kelompok)->get();

    foreach ($siswaKelas as $siswa) {
        DispenDetail::create([
            'id_dispen' => $dispen->id_dispen,
            'nis' => $siswa->nis,
            'nama' => $siswa->nama
        ]);
    }

} else {

    // 🔥 MODE INDIVIDU
    $siswa = Siswa::where('nis', $request->nis)->first();

    DispenDetail::create([
        'id_dispen' => $dispen->id_dispen,
        'nis' => $siswa->nis,
        'nama' => $siswa->nama

    ]);

    if ($request->nis_tambahan) {
        foreach ($request->nis_tambahan as $nisTambahan) {

            if ($nisTambahan) {

                $siswaTambahan = Siswa::where('nis', $nisTambahan)->first();

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
    $data = Dispen::with(['guru','detail.siswa','guruPiket'])
        ->findOrFail($id);

    $detail = $data->detail;
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
$data->load(['detail.siswa']);
$kelasNama = optional($data->detail->first()?->siswa)->kelas ?? '-';
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
public function printBukti($id)
{
    $data = Dispen::with(['detail.siswa','guru'])->findOrFail($id);

    $detail = $data->detail;

    return view('dispen.print_bukti', compact('data','detail'));
}
}