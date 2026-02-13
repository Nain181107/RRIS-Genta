<?php

namespace App\Exports;

use App\Models\Penerimaan;
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

class HistoryPenerimaanExport implements
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

    public function collection()
    {
        $q = Penerimaan::with(['identitasRod', 'karyawan']);

        // FILTER (samakan dengan tabel)
        if (!empty($this->filter['tanggalMulai']) && !empty($this->filter['tanggalAkhir'])) {

            $mulai = Carbon::parse($this->filter['tanggalMulai'])->startOfDay();
            $akhir = Carbon::parse($this->filter['tanggalAkhir'])->endOfDay();

            $q->whereBetween('tanggal_penerimaan', [$mulai, $akhir]);
        } elseif (!empty($this->filter['tanggalMulai'])) {

            $mulai = Carbon::parse($this->filter['tanggalMulai'])->startOfDay();
            $akhir = Carbon::parse($this->filter['tanggalMulai'])->endOfDay();

            $q->whereBetween('tanggal_penerimaan', [$mulai, $akhir]);
        } elseif (!empty($this->filter['tanggalAkhir'])) {

            $mulai = Carbon::parse($this->filter['tanggalAkhir'])->startOfDay();
            $akhir = Carbon::parse($this->filter['tanggalAkhir'])->endOfDay();

            $q->whereBetween('tanggal_penerimaan', [$mulai, $akhir]);
        }

        if (!empty($this->filter['nomor_rod'])) {
            $nomorRod = $this->filter['nomor_rod'];
            $q->whereHas('identitasRod', function ($q2) use ($nomorRod) {
                $q2->where('nomor_rod', 'like', '%' . $nomorRod . '%');
            });
        }

        if (!empty($this->filter['jenis'])) {
            $q->where('jenis', 'like', '%' . $this->filter['jenis'] . '%');
        }

        if (!empty($this->filter['penginput'])) {
            $namalengkap = $this->filter['penginput'];
            $q->whereHas('karyawan', function ($q2) use ($namalengkap) {
                $q2->where('nama_lengkap', 'like', '%' . $namalengkap . '%');
            });
        }

        if (!empty($this->filter['stasiun'])) {
            $q->where('stasiun', $this->filter['stasiun']);
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
                $row->tanggal_penerimaan,
                $row->shift,
                $row->identitasRod->nomor_rod ?? '-',
                $row->jenis,
                $row->stasiun,
                $row->e1,
                $row->e2,
                $row->e3,
                $row->s,
                $row->d,
                $row->b,
                $row->ba,
                $row->r,
                $row->m,
                $row->cr,
                $row->c,
                $row->rl,
                $row->jumlah,
                $row->catatan,
                $row->updated_at,
                $row->karyawan->nama_lengkap ?? '-',
                $row->tim,
            ];
        });
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Judul
                $sheet->mergeCells('A1:W1');
                $sheet->setCellValue('A1', 'DATA PENERIMAAN');
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
                $sheet->mergeCells('A2:W2');
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
                    'TANGGAL PENERIMAAN',
                    'SHIFT',
                    'NOMOR ROD',
                    'JENIS',
                    'STASIUN',
                    'E1',
                    'E2',
                    'E3',
                    'S',
                    'D',
                    'B',
                    'BA',
                    'R',
                    'M',
                    'CR',
                    'C',
                    'RL',
                    'JUMLAH',
                    'CATATAN',
                    'DIUBAH',
                    'PEGINPUT',
                    'TIM',
                ];

                $sheet->fromArray($headers, null, 'A4');
                $sheet->getStyle('A4:W4')->applyFromArray([
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
                $sheet->setAutoFilter('A4:W4');

                $highestRow = $sheet->getHighestRow();

                // Border table
                $sheet->getStyle("A4:W{$highestRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // FORMAT TANGGAL
                $sheet->getStyle('B5:B' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('dd-mm-yyyy hh:mm:ss');

                // Semua isi tabel rata tengah
                $sheet->getStyle("A4:W{$highestRow}")
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
            'E' => 11,
            'F' => 11,
            'G' => 11,
            'H' => 11,
            'I' => 11,
            'J' => 11,
            'K' => 11,
            'L' => 11,
            'M' => 11,
            'N' => 11,
            'O' => 11,
            'P' => 11,
            'Q' => 11,
            'R' => 11,
            'S' => 12,
            'T' => 14,
            'U' => 26,
            'V' => 35,
            'W' => 11,
        ];
    }
}
