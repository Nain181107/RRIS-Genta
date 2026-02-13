<?php

namespace App\Exports;

use App\Models\Perbaikan;
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

class HistoryPerbaikanExport implements
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
        $q = Perbaikan::with(['penerimaan.identitasRod', 'karyawan']);

        // FILTER (samakan dengan tabel)
        if (!empty($this->filter['tanggalMulai']) && !empty($this->filter['tanggalAkhir'])) {

            $mulai = Carbon::parse($this->filter['tanggalMulai'])->startOfDay();
            $akhir = Carbon::parse($this->filter['tanggalAkhir'])->endOfDay();

            $q->whereBetween('tanggal_perbaikan', [$mulai, $akhir]);
        } elseif (!empty($this->filter['tanggalMulai'])) {

            $mulai = Carbon::parse($this->filter['tanggalMulai'])->startOfDay();
            $akhir = Carbon::parse($this->filter['tanggalMulai'])->endOfDay();

            $q->whereBetween('tanggal_perbaikan', [$mulai, $akhir]);
        } elseif (!empty($this->filter['tanggalAkhir'])) {

            $mulai = Carbon::parse($this->filter['tanggalAkhir'])->startOfDay();
            $akhir = Carbon::parse($this->filter['tanggalAkhir'])->endOfDay();

            $q->whereBetween('tanggal_perbaikan', [$mulai, $akhir]);
        }

        if (!empty($this->filter['nomor_rod'])) {
            $nomorRod = $this->filter['nomor_rod'];
            $q->whereHas('penerimaan.identitasRod', function ($q2) use ($nomorRod) {
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

        if (!empty($this->filter['shift'])) {
            $q->where('shift', $this->filter['shift']);
        }

        if (!empty($this->filter['tim'])) {
            $q->where('tim', $this->filter['tim']);
        }

        return $q->get()->values()->map(function ($row, $i) {
            return [
                $i + 1,
                $row->tanggal_perbaikan,
                $row->shift,
                $row->penerimaan->identitasRod->nomor_rod ?? '-',
                $row->jenis,
                $row->e1_ers,
                $row->e1_est,
                $row->e1_jumlah,
                $row->e2_ers,
                $row->e2_cst,
                $row->e2_cstub,
                $row->e2_jumlah,
                $row->e3,
                $row->e4,
                $row->s,
                $row->d,
                $row->b,
                $row->bac,
                $row->nba,
                $row->ba,
                $row->ba1,
                $row->r,
                $row->m,
                $row->cr,
                $row->c,
                $row->rl,
                $row->jumlah,
                $row->catatan,
                $row->tanggal_penerimaan,
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
                $sheet->mergeCells('A1:AF1');
                $sheet->setCellValue('A1', 'DATA PERBAIKAN');
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
                $sheet->mergeCells('A2:AF2');
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
                    'TANGGAL PERBAIKAN',
                    'SHIFT',
                    'NOMOR ROD',
                    'JENIS',
                    'E1 ERS',
                    'E1 EST',
                    'E1 JUMLAH',
                    'E2 ERS',
                    'E2 CST',
                    'E2 CSTUB',
                    'E2 JUMLAH',
                    'E3',
                    'E4',
                    'S',
                    'D',
                    'B',
                    'BAC',
                    'NBA',
                    'BA',
                    'BA-1',
                    'R',
                    'M',
                    'CR',
                    'C',
                    'RL',
                    'JUMLAH',
                    'CATATAN',
                    'TANGGAL PENERIMAAN',
                    'DIUBAH',
                    'PEGINPUT',
                    'TIM',
                ];

                $sheet->fromArray($headers, null, 'A4');
                $sheet->getStyle('A4:AF4')->applyFromArray([
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
                $sheet->setAutoFilter('A4:AF4');

                $highestRow = $sheet->getHighestRow();

                // Border table
                $sheet->getStyle("A4:AF{$highestRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // FORMAT TANGGAL
                $sheet->getStyle('B5:B' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('dd-mm-yyyy hh:mm:ss');

                // Semua isi tabel rata tengah
                $sheet->getStyle("A4:AF{$highestRow}")
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
            'F' => 12,
            'G' => 12,
            'H' => 12,
            'I' => 12,
            'J' => 12,
            'K' => 12,
            'L' => 12,
            'M' => 11,
            'N' => 11,
            'O' => 11,
            'P' => 11,
            'Q' => 11,
            'R' => 11,
            'S' => 11,
            'T' => 11,
            'U' => 11,
            'V' => 11,
            'W' => 11,
            'X' => 11,
            'Y' => 11,
            'Z' => 11,
            'AA' => 12,
            'AB' => 14,
            'AC' => 26,
            'AD' => 26,
            'AE' => 35,
            'AF' => 11,
        ];
    }
}
