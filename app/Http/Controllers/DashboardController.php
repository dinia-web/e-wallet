<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Dispen;
use App\Models\Perizinan;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

          // ===============================
    // Bulanan (Chart)
    // ===============================
    $bulan = [];
    for ($i = 0; $i < 12; $i++) {
        $bulan[] = Carbon::now()->subMonths(11 - $i)->format('M');
    }

    $dataDispen = [];
    $dataSakit = [];
$dataIzin = [];
$dataTerlambat = [];

for ($i = 0; $i < 12; $i++) {
    $month = Carbon::now()->subMonths(11 - $i)->month;

    // Chart Dispen
    $dataDispen[] = Dispen::whereMonth('created_at', $month)->count();

    // Chart Perizinan
    $dataSakit[] = Perizinan::whereMonth('created_at', $month)
                            ->where('jenis', 'sakit')->count();

    $dataIzin[]  = Perizinan::whereMonth('created_at', $month)
                            ->where('jenis', 'izin')->count();

    $dataTerlambat[]  = Perizinan::whereMonth('created_at', $month)
                            ->where('jenis', 'terlambat')->count();
}

    // ===============================
    // Statistik umum
    // ===============================
    $jumlah_dispen = Dispen::count();
    $dispen_hari_ini = Dispen::whereDate('created_at', $today)->count();

    $jumlah_perizinan = Perizinan::count();
    $izin_hari_ini = Perizinan::whereDate('created_at', $today)->count();

    $jumlah_siswa = Siswa::count();
    $jumlah_guru = User::where('role', 'guru')->count();

    $recent_dispen = Dispen::latest()->limit(5)->get();

    // ===============================
    // Render view admin
    // ===============================
    if ($user->role == 'admin') {
        return view('admin.dashboard', compact(
            'user',
            'jumlah_dispen',
            'dispen_hari_ini',
            'jumlah_perizinan',
            'izin_hari_ini',
            'jumlah_siswa',
            'jumlah_guru',
            'recent_dispen',
            'bulan',
            'dataDispen',
            'dataSakit',
            'dataIzin',
            'dataTerlambat'
        ));
    }

        // ===============================
        // GURU
        // ===============================
       elseif ($user->role == 'guru') {

    // ======================
    // GURU BIASA
    // ======================
    if(!$user->is_walikelas){

        $jumlah_dispen = Dispen::where('id_guru',$user->id_user)
                                ->count();

        $jumlah_terbaru = Dispen::where('id_guru',$user->id_user)
                                ->whereDate('created_at',$today)
                                ->count();

        return view('dashboard.guru', compact(
            'user',
            'jumlah_dispen',
            'jumlah_terbaru'
        ));
    }

    // ======================
    // WALI KELAS
    // ======================
    else{

        $jumlah_dispen = Dispen::where('id_guru',$user->id_user)
                                ->count();

        $jumlah_terbaru = Dispen::where('id_guru',$user->id_user)
                                ->whereDate('created_at',$today)
                                ->count();

        $jumlah_perizinan = Perizinan::count();
        $izin_hari_ini = Perizinan::whereDate('created_at',$today)->count();

        return view('dashboard.walikelas', compact(
            'user',
            'jumlah_dispen',
            'jumlah_terbaru',
            'jumlah_perizinan',
            'izin_hari_ini',
            'bulan',
            'dataDispen',
            'dataSakit',
            'dataIzin'
        ));
    }
}

        // ===============================
        // SISWA → redirect ke dashboard siswa
        // ===============================
        elseif ($user->role == 'siswa') {
            return redirect('/siswa/dashboard');
        }

        else {
            abort(403);
        }

        return view('dashboard.index', compact(
            'user',
            'jumlah_dispen',
            'jumlah_terbaru'
        ));
    }

    public function dashboardSiswa()
    {
        $user = Auth::user();

        // proteksi jika bukan siswa
        if ($user->role != 'siswa') {
            abort(403);
        }

        $jumlah_dispen = Dispen::whereHas('detail', function ($q) use ($user) {
            $q->where('nis', $user->nis);
        })->count();

        $jumlah_izin = Perizinan::where('nis', $user->nis)->count();

        $riwayat = Dispen::whereHas('detail', function ($q) use ($user) {
            $q->where('nis', $user->nis);
        })
        ->latest()
        ->limit(5)
        ->get();

        return view('siswa.dashboard', compact(
            'jumlah_dispen',
            'jumlah_izin',
            'riwayat'
        ));
    }
}