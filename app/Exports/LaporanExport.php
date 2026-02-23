<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;

class LaporanExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $tanggal_awal;
    protected $tanggal_akhir;
    protected $user;

    public function __construct($tanggal_awal, $tanggal_akhir, $user)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->user = $user;
    }

    public function collection()
{
    $data = DB::table('dispen as d')
        ->leftJoin('kelas as k', 'd.kelas', '=', 'k.id_kelas')
        ->leftJoin('jampel as jk', 'd.jam_keluar', '=', 'jk.id_jampel')
        ->leftJoin('jampel as jb', 'd.jam_kembali', '=', 'jb.id_jampel')
        ->leftJoin('users as u', 'd.id_guru', '=', 'u.id_user')
        ->leftJoin('gurpik as gp', 'd.gurpi', '=', 'gp.id_guru')
        ->whereBetween('d.created_at', [$this->tanggal_awal, $this->tanggal_akhir])
        ->when($this->user->role == 'guru', function ($query) {
            $query->where('d.id_guru', $this->user->id_user);
        })
        ->whereIn('d.status', ['disetujui','ditolak'])
        ->select(
            'd.id_dispen',
            'd.nis',
            'd.nama',
            'k.klas as kelas',
            'jk.jam as jam_keluar',
            'jb.jam as jam_kembali',
            'd.created_at',
            'u.username as guru',
            'gp.gurpi as guru_piket',
            'd.alasan',
            'd.status'
        )
        ->get();

    $result = collect();
    $no = 1;

    foreach ($data as $row) {

        // =========================
        // 🔥 SISWA UTAMA
        // =========================
        $result->push([
            $no++,
            $row->nis,
            $row->nama,
            $row->kelas,
            $row->jam_keluar,
            $row->jam_kembali,
            Carbon::parse($row->created_at)->format('d-m-Y H:i'),
            $row->guru,
            $row->guru_piket ?? '-',
            $row->alasan,
            $row->status,
        ]);

        // =========================
        // 🔥 SISWA TAMBAHAN
        // =========================
        $detail = DB::table('dispen_detail')
            ->where('id_dispen', $row->id_dispen)
            ->get();

        foreach ($detail as $d) {
            $result->push([
                $no++,
                $d->nis,
                $d->nama . ' (Tambahan)',
                $row->kelas,
                $row->jam_keluar,
                $row->jam_kembali,
                Carbon::parse($row->created_at)->format('d-m-Y H:i'),
                $row->guru,
                $row->guru_piket ?? '-',
                $row->alasan,
                $row->status,
            ]);
        }
    }

    return $result;
}
    public function headings(): array
    {
        return [
            'No',
            'NIS',
            'Nama',
            'Kelas',
            'Jam Keluar',
            'Jam Kembali',
            'Tanggal',
            'Guru Pengajar',
            'Guru Piket',
            'Keperluan',
            'Keterangan',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Tambah 4 baris atas
                $sheet->insertNewRowBefore(1, 4);

                // LOGO (WAJIB pakai public_path)
                $logo = new Drawing();
                $logo->setName('Logo Sekolah');
                $logo->setDescription('Logo Sekolah');
                $logo->setPath(public_path('images/tu.png')); 
                $logo->setHeight(60);
                $logo->setCoordinates('A1');
                $logo->setWorksheet($sheet);

                // Header Sekolah
                $sheet->mergeCells('A1:K1');
                $sheet->setCellValue('A1', 'SMK NEGERI 1 KEBUMEN');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A2:K2');
                $sheet->setCellValue('A2', 'Jln. Cemara No.37 Karangsari Kebumen Jawa Tengah | Telp: 0287-381132');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A3:K3');
                $sheet->setCellValue('A3', 'LAPORAN DISPENSASI SISWA');
                $sheet->getStyle('A3')->getFont()->setBold(true);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Tanggal cetak
                $sheet->setCellValue('K4', 'Tanggal Cetak: ' . Carbon::now()->format('d-m-Y'));

                // Styling header tabel
                $sheet->getStyle('A5:K5')->getFont()->setBold(true);
                $sheet->getStyle('A5:K5')->getFont()->getColor()->setRGB('FFFFFF');
                $sheet->getStyle('A5:K5')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('00B050');
                $sheet->getStyle('A5:K5')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Border tabel
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A5:K' . $lastRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Tanda tangan
                $ttdRow = $lastRow + 3;

                $sheet->setCellValue('K' . $ttdRow, 'Kebumen, ' . Carbon::now()->format('d-m-Y'));
                $sheet->setCellValue('K' . ($ttdRow + 1), 'Mengetahui,');
                $sheet->setCellValue('K' . ($ttdRow + 2), 'Kepala Sekolah');
                $sheet->setCellValue('K' . ($ttdRow + 6), 'Drs. HARYOKO, M.M.');
                $sheet->setCellValue('K' . ($ttdRow + 7), 'NIP. 01234567');
            },
        ];
    }
}