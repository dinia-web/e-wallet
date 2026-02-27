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
                'kelasRel',
                'guru',
                'guruPiket',
                'detail' // 🔥 relasi siswa tambahan
            ])
            ->whereBetween('created_at', [
                Carbon::parse($tanggal_awal)->startOfDay(),
                Carbon::parse($tanggal_akhir)->endOfDay()
            ])
            ->whereIn('status', ['disetujui', 'ditolak']);

            // 🔥 filter khusus guru
            if ($user->role == 'guru') {
                $query->where('id_guru', $user->id_user);
            }

            $result = $query->orderBy('id_dispen', 'asc')->get();

            // 🔥 gabungkan siswa utama + tambahan
            $finalData = collect();

            foreach ($result as $row) {

                // ✅ siswa utama
                $finalData->push([
                    'nis' => $row->nis,
                    'nama' => $row->nama,
                    'kelas' => $row->siswa->kelas ?? '-',
                    'email' => $row->email,
                    'no_hp' => $row->no_hp,
                    'tanggal' => $row->created_at,
                    'guru' => $row->guru->username ?? '-',
                    'gurpik' => $row->guruPiket->gurpi ?? '-',
                    'alasan' => $row->alasan,
                    'status' => $row->status
                ]);

                // ✅ siswa tambahan
                foreach ($row->detail as $d) {
                    $finalData->push([
                        'nis' => $d->nis,
                        'nama' => $d->nama,
                        'kelas' => $row->siswa->kelas ?? '-',
                        'email' => $row->email,
                        'no_hp' => $row->no_hp,
                        'tanggal' => $row->created_at,
                        'guru' => $row->guru->username ?? '-',
                        'gurpik' => $row->guruPiket->gurpi ?? '-',
                        'alasan' => '(Tambahan) ' . $row->alasan,
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