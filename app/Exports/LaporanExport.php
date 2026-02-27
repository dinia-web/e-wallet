<?php

namespace App\Exports;

use App\Models\Dispen;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

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
        $query = Dispen::with(['siswa', 'guru', 'guruPiket', 'detail']) // load relasi
            ->whereBetween('created_at', [
                Carbon::parse($this->tanggal_awal)->startOfDay(),
                Carbon::parse($this->tanggal_akhir)->endOfDay()
            ])
            ->whereIn('status', ['disetujui','ditolak']);

        if ($this->user->role == 'guru') {
            $query->where('id_guru', $this->user->id_user);
        }

        $result = collect();
        $no = 1;

        foreach ($query->orderBy('id_dispen', 'asc')->get() as $row) {

            // 🔥 SISWA UTAMA
            $result->push([
                $no++,
                $row->nis,
                $row->nama,
                $row->siswa->kelas ?? '-',
                $row->email,
                $row->no_hp,
                Carbon::parse($row->created_at)->format('d-m-Y H:i'),
                $row->guru->username ?? '-',
                $row->guruPiket->gurpi ?? '-',
                $row->alasan,
                $row->status
            ]);

            // 🔥 SISWA TAMBAHAN
            foreach ($row->detail as $d) {
                $result->push([
                    $no++,
                    $d->nis,
                    $d->nama . ' (Tambahan)',
                    $row->siswa->kelas ?? '-', 
                    $row->email,
                    $row->no_hp,
                    Carbon::parse($row->created_at)->format('d-m-Y H:i'),
                    $row->guru->username ?? '-',
                    $row->guruPiket->gurpi ?? '-',
                    $row->alasan,
                    $row->status
                ]);
            }
        }

        return $result;
    }

    public function headings(): array
    {
        return [
            'No', 'NIS', 'Nama', 'Kelas', 'Email', 'No Hp','Tanggal', 'Guru Pengajar', 'Guru Piket', 'Keperluan', 'Keterangan',
        ];
    }

    public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {

            $sheet = $event->sheet->getDelegate();

            // Tambah 4 baris kosong di atas
            $sheet->insertNewRowBefore(1, 4);

            // =========================
            // LOGO SEKOLAH
            // =========================
            $logo = new Drawing();
            $logo->setName('Logo Sekolah');
            $logo->setDescription('Logo Sekolah');
            $logo->setPath(public_path('images/tu.png')); 
            $logo->setHeight(60);
            $logo->setCoordinates('B1');
            $logo->setWorksheet($sheet);

            // =========================
            // HEADER SEKOLAH
            // =========================
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

            // =========================
            // TANGGAL CETAK
            // =========================
            $sheet->setCellValue('K4', 'Tanggal Cetak: ' . \Carbon\Carbon::now()->format('d-m-Y'));

            // =========================
            // STYLING HEADER TABEL
            // =========================
            $sheet->getStyle('A5:K5')->getFont()->setBold(true);
            $sheet->getStyle('A5:K5')->getFont()->getColor()->setRGB('FFFFFF');
            $sheet->getStyle('A5:K5')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('00B050');
            $sheet->getStyle('A5:K5')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // =========================
            // BORDER TABEL
            // =========================
            $lastRow = $sheet->getHighestRow();
            $sheet->getStyle('A5:K'.$lastRow)
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);

            // =========================
            // TANDA TANGAN
            // =========================
            $ttdRow = $lastRow + 3;
            $sheet->setCellValue('K'.$ttdRow, 'Kebumen, ' . \Carbon\Carbon::now()->format('d-m-Y'));
            $sheet->setCellValue('K'.($ttdRow+1), 'Mengetahui,');
            $sheet->setCellValue('K'.($ttdRow+2), 'Kepala Sekolah');
            $sheet->setCellValue('K'.($ttdRow+6), 'UMI ROKHAYATUN, S.Pd., M.Si., Ak., CA');
            $sheet->setCellValue('K'.($ttdRow+7), 'NIP. 19710509 199903 2 006');
        },
    ];
}
}