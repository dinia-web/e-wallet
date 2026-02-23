<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Dispen;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // ===============================
        // ADMIN → lihat semua data
        // ===============================
        if ($user->role == 'admin') {

            $jumlah_dispen = Dispen::whereDate('created_at', $today)
                                   ->where('status', 'dalam proses')
                                   ->count();

            $jumlah_terbaru = Dispen::whereDate('created_at', $today)
                                     ->count();
        }

        // ===============================
        // GURU → hanya data sesuai guru login
        // ===============================
        elseif ($user->role == 'guru') {

                $jumlah_dispen = Dispen::whereDate('created_at', $today)
                                        ->where('id_guru', $user->id_user)
                                        ->where('status', 'dalam proses')
                                        ->count();

                $jumlah_terbaru = Dispen::whereDate('created_at', $today)
                                        ->where('id_guru', $user->id_user)
                                        ->count();
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
}
