<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dispen;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
   public function index(Request $request)
{
    $tanggal_awal  = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;
    $user = auth()->user();

    $data = collect();

    if ($tanggal_awal && $tanggal_akhir) {

        $query = Dispen::with([
            'guru',
            'guruPiket',
            'detail.siswa' // 🔥 ambil siswa lewat detail
        ])
        ->whereBetween('created_at', [
            Carbon::parse($tanggal_awal)->startOfDay(),
            Carbon::parse($tanggal_akhir)->endOfDay()
        ])
        ->whereIn('status', ['disetujui', 'ditolak']);

        if ($user->role == 'guru') {
            $query->where('id_guru', $user->id_user);
        }

        $result = $query->orderBy('id_dispen', 'asc')->get();

        $finalData = collect();

        foreach ($result as $row) {

            foreach ($row->detail as $index => $d) {

                $finalData->push([
                    'nis' => $d->nis,
                    'nama' => $d->nama,
                    'kelas' => $d->siswa->kelas ?? '-',
                    'email' => $row->email,
                    'no_hp' => $row->no_hp,
                    'tanggal' => $row->created_at,
                    'guru' => $row->guru->username ?? '-',
                    'gurpik' => $row->guruPiket->gurpi ?? '-',
                    'alasan' => $index == 0 
                        ? $row->alasan 
                        : '(Tambahan) ' . $row->alasan,
                    'status' => $row->status
                ]);
            }
        }

        $data = $finalData;
    }

    return view('laporan.index', compact(
        'data',
        'tanggal_awal',
        'tanggal_akhir'
    ));
}

    public function export(Request $request)
    {
        $tanggal_awal  = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $user = auth()->user();

        return Excel::download(
            new LaporanExport($tanggal_awal, $tanggal_akhir, $user),
            'laporan_dispensasi.xlsx'
        );
    }
}