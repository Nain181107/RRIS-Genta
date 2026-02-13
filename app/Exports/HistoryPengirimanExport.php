<?php

namespace App\Exports;

use App\Models\Pengiriman;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithEvents,
    WithColumnWidths
};
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Carbon\Carbon;

class HistoryPengirimanExport implements
    FromCollection,
    WithEvents,
    WithColumnWidths,
    WithCustomStartCell
{
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function collection()
    {
        $q = Pengiriman::with(['perbaikan.penerimaan.identitasRod', 'karyawan']);

        // FILTER (samakan dengan tabel)
        if (!empty($this->filter['tanggalMulai']) && !empty($this->filter['tanggalAkhir'])) {

            $mulai = Carbon::parse($this->filter['tanggalMulai'])->startOfDay();
            $akhir = Carbon::parse($this->filter['tanggalAkhir'])->endOfDay();

            $q->whereBetween('tanggal_pengiriman', [$mulai, $akhir]);
        } elseif (!empty($this->filter['tanggalMulai'])) {

            $mulai = Carbon::parse($this->filter['tanggalMulai'])->startOfDay();
            $akhir = Carbon::parse($this->filter['tanggalMulai'])->endOfDay();

            $q->whereBetween('tanggal_pengiriman', [$mulai, $akhir]);
        } elseif (!empty($this->filter['tanggalAkhir'])) {

            $mulai = Carbon::parse($this->filter['tanggalAkhir'])->startOfDay();
            $akhir = Carbon::parse($this->filter['tanggalAkhir'])->endOfDay();

            $q->whereBetween('tanggal_pengiriman', [$mulai, $akhir]);
        }

        if (!empty($this->filter['nomor_rod'])) {
            $nomorRod = $this->filter['nomor_rod'];
            $q->whereHas('perbaikan.penerimaan.identitasRod', function ($q2) use ($nomorRod) {
                $q2->where('nomor_rod', 'like', '%' . $nomorRod . '%');
            });
        }

        if (!empty($this->filter['penginput'])) {
            $namalengkap = $this->filter['penginput'];
            $q->whereHas('karyawan', function ($q2) use ($namalengkap) {
                $q2->where('nama_lengkap', 'like', '%' . $namalengkap . '%');
            });
        }

        if (!empty($this->filter['shift'])) {
            $q->where('shift', $this->filter['shift']);
        }

        if (!empty($this->filter['tim'])) {
            $q->where('tim', $this->filter['tim']);
        }

        return $q->get()->values()->map(function ($row, $i) {
            return [
                $i + 1,
                $row->tanggal_pengiriman,
                $row->shift,
                $row->perbaikan->penerimaan->identitasRod->nomor_rod ?? '-',
                $row->perbaikan->penerimaan->tanggal_penerimaan ?? '-',
                $row->perbaikan->tanggal_perbaikan ?? '-',
                $row->updated_at,
                $row->karyawan->nama_lengkap ?? '-',
                $row->tim,
            ];
        });
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Judul
                $sheet->mergeCells('A1:I1');
                $sheet->setCellValue('A1', 'DATA PENGIRIMAN');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'name' => 'Arial Narrow'
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                ]);

                // Sub Judul
                $sheet->mergeCells('A2:I2');
                $filterText = 'Filter : ';

                $filters = [];

                if (!empty($this->filter['tanggalMulai']) && !empty($this->filter['tanggalAkhir'])) {
                    $filters[] = 'Tanggal = ' .
                        Carbon::parse($this->filter['tanggalMulai'])->format('d-m-Y') .
                        ' s/d ' .
                        Carbon::parse($this->filter['tanggalAkhir'])->format('d-m-Y');
                } elseif (!empty($this->filter['tanggalMulai'])) {
                    $filters[] = 'Tanggal = ' .
                        Carbon::parse($this->filter['tanggalMulai'])->format('d-m-Y');
                } elseif (!empty($this->filter['tanggalAkhir'])) {
                    $filters[] = 'Tanggal = ' .
                        Carbon::parse($this->filter['tanggalAkhir'])->format('d-m-Y');
                }

                if (!empty($this->filter['nomor_rod'])) {
                    $filters[] = 'Nomor ROD = ' . $this->filter['nomor_rod'];
                }

                if (!empty($this->filter['jenis'])) {
                    $filters[] = 'Jenis = ' . $this->filter['jenis'];
                }

                if (!empty($this->filter['stasiun'])) {
                    $filters[] = 'Stasiun = ' . $this->filter['stasiun'];
                }

                if (!empty($this->filter['shift'])) {
                    $filters[] = 'Shift = ' . $this->filter['shift'];
                }

                if (!empty($this->filter['tim'])) {
                    $filters[] = 'Tim = ' . $this->filter['tim'];
                }

                if (!empty($this->filter['penginput'])) {
                    $filters[] = 'Penginput = ' . $this->filter['penginput'];
                }

                if (count($filters) > 0) {
                    $filterText .= implode(' | ', $filters);
                } else {
                    $filterText .= 'Semua Data';
                }
                $sheet->setCellValue('A2', $filterText);
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'size' => 14,
                        'name' => 'Arial Narrow'
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(41);
                $sheet->getRowDimension(2)->setRowHeight(41);

                // Header manual di row 4
                $headers = [
                    'NO',
                    'TANGGAL PENGIRIMAN',
                    'SHIFT',
                    'NOMOR ROD',
                    'TANGGAL PENERIMAAN',
                    'TANGGAL PERBAIKAN',
                    'DIUBAH',
                    'PEGINPUT',
                    'TIM',
                ];

                $sheet->fromArray($headers, null, 'A4');
                $sheet->getStyle('A4:I4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'name' => 'Arial Narrow'
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9D9D9'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
                $sheet->getRowDimension(4)->setRowHeight(39);


                $sheet->freezePane('A5');
                $sheet->setAutoFilter('A4:I4');

                $highestRow = $sheet->getHighestRow();

                // Border table
                $sheet->getStyle("A4:I{$highestRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // FORMAT TANGGAL
                $sheet->getStyle('B5:B' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('dd-mm-yyyy hh:mm:ss');

                // Semua isi tabel rata tengah
                $sheet->getStyle("A4:I{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                // Tinggi semua baris data
                for ($i = 5; $i <= $highestRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(24);
                }
            }
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 26,
            'C' => 11,
            'D' => 17,
            'E' => 26,
            'F' => 26,
            'G' => 26,
            'H' => 35,
            'I' => 11,
        ];
    }
}
