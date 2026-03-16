<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perizinan;
use App\Models\User; 
use App\Models\Siswa;
 use Carbon\Carbon;

class PerizinanController extends Controller
{

public function index(Request $request)
{
    $user = auth()->user();
    $limit = $request->limit ?? 5;
    $search = $request->search ?? '';
    $showAll = $request->all == 'true'; // pakai all=true untuk tampil semua

    $query = Perizinan::with(['guru','siswa']);

    // =========================
    // FILTER ROLE
    // =========================
    if ($user->role == 'guru' && $user->is_walikelas) {
        // guru wali kelas: hanya lihat perizinan siswa di kelasnya
        $query->whereHas('siswa', function($q) use ($user){
            $q->where('id_guru', $user->id_user);
        });
        
        if (!$showAll) {
            $query->whereDate('created_at', Carbon::today());
        }

    } elseif ($user->role == 'siswa') {
        // siswa hanya lihat perizinannya sendiri
        $query->where('nis', $user->nis);
        // 🟢 siswa tidak dibatasi tanggal, showAll irrelevant

    } elseif ($user->role == 'admin') {
        // admin lihat semua
        if (!$showAll) {
            $query->whereDate('created_at', Carbon::today());
        }
    } else {
        abort(403, 'Anda tidak memiliki akses untuk melihat perizinan.');
    }

    // =========================
    // SEARCH
    // =========================
    if ($search) {
        $query->whereHas('siswa', function($q) use ($search){
            $q->where('nama', 'like', "%$search%")
              ->orWhere('nis', 'like', "%$search%");
        });
    }

    // =========================
    // PAGINATION
    // =========================
    $perizinan = $query->latest()->paginate($limit)
                    ->withQueryString();

    return view('perizinan.index', compact('perizinan','limit','search'));
}

    // =========================
    // FORM BUAT PERIZINAN
    // =========================
public function create()
{
    $guru = User::where('role', 'guru')
                ->where('is_walikelas', 1)
                ->orderBy('username')
                ->get();

    return view('auth.perizinan', compact('guru'));
}

    // =========================
    // SIMPAN PERIZINAN BARU
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|exists:siswa,nis',
            'jenis' => 'required|in:sakit,izin,terlambat',
            'keterangan' => 'required',
            'id_guru' => 'required|exists:users,id_user',
            'file' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);

        $fileName = null;

        if($request->hasFile('file')){
            $file = $request->file('file');
            $fileName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/perizinan'), $fileName);
        }

        Perizinan::create([
            'nis' => $request->nis,
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan,
            'id_guru' => $request->id_guru,
            'file' => $fileName
        ]);

        return redirect()->route('perizinan.index')
            ->with('success','Perizinan berhasil dikirim');
    }

    // =========================
    // FORM EDIT PERIZINAN
    // =========================
    public function edit($id)
    {
        $perizinan = Perizinan::findOrFail($id);
        $guru = User::where('role','guru')
            ->where('is_walikelas',1)
            ->orderBy('username')
            ->get();

        return view('auth.perizinan_edit', compact('perizinan','guru'));
    }

    // =========================
    // UPDATE PERIZINAN
    // =========================
    public function update(Request $request, $id)
    {
        $perizinan = Perizinan::findOrFail($id);

        $request->validate([
            'jenis' => 'required|in:sakit,izin,terlambat',
            'keterangan' => 'required',
            'id_guru' => 'required|exists:users,id_user',
            'file' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);

        // Jika ada file baru, hapus file lama
        if($request->hasFile('file')){
            if($perizinan->file && file_exists(public_path('uploads/perizinan/'.$perizinan->file))){
                unlink(public_path('uploads/perizinan/'.$perizinan->file));
            }

            $file = $request->file('file');
            $fileName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/perizinan'), $fileName);
            $perizinan->file = $fileName;
        }

        $perizinan->update([
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan,
            'id_guru' => $request->id_guru
        ]);

        return redirect()->route('perizinan.index')
            ->with('success','Perizinan berhasil diperbarui');
    }

    // =========================
    // DELETE PERIZINAN
    // =========================
    public function destroy($id)
    {
        $perizinan = Perizinan::findOrFail($id);

        // hapus file jika ada
        if($perizinan->file && file_exists(public_path('uploads/perizinan/'.$perizinan->file))){
            unlink(public_path('uploads/perizinan/'.$perizinan->file));
        }

        $perizinan->delete();

        return redirect()->route('perizinan.index')
            ->with('success','Perizinan berhasil dihapus');
    }


  public function printData(Request $request)
{
    $request->validate([
        'semester' => 'required',
        'tahun' => 'required',
        'kelas' => 'required'
    ]);

    $query = Perizinan::with(['siswa','guru'])
        ->whereYear('created_at', $request->tahun)
        ->whereHas('siswa', function($q) use ($request){
            $q->where('kelas', $request->kelas);
        });

    $perizinan = $query->get();

    // Filter semester
    if($request->semester == 1){
        $perizinan = $perizinan->filter(function($item){
            return $item->created_at->month > 6;
        });
    }else{
        $perizinan = $perizinan->filter(function($item){
            return $item->created_at->month <= 6;
        });
    }

    return view('perizinan.print', compact('perizinan','request'));
}
public function printForm()
{
    $kelas = Siswa::select('kelas')
        ->distinct()
        ->orderBy('kelas')
        ->get();

    return view('perizinan.print_form', compact('kelas'));
}
public function show($id)
{
    $perizinan = Perizinan::with(['siswa','guru'])->findOrFail($id);

    return view('perizinan.show', compact('perizinan'));
}
}