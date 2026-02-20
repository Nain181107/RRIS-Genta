<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Penerimaan;
use App\Models\Pengiriman;
use App\Models\Perbaikan;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan'); // Sesuaikan dengan path view Anda
    }

    // Method untuk preview PDF
    public function previewPDF(Request $request)
    {
        // Ambil data filter dari form
        $jenisLaporan = $request->jenisLaporan;
        $tanggal = $request->tanggal;
        $shift = $request->shift;
        $tim = $request->tim;

        // Inisialisasi data kosong
        $data = collect();
        $dataAllShift = collect();

        // Query berdasarkan jenis laporan
        if ($jenisLaporan == 'penerimaan') {

            $query = Penerimaan::with(['identitasRod', 'karyawan']);

            // Filter berdasarkan tanggal
            if ($tanggal) {
                $query->whereDate('tanggal_penerimaan', $tanggal);
            }

            // Filter berdasarkan shift
            if ($shift) {
                $query->where('shift', $shift);
            }

            // Filter berdasarkan tim
            if ($tim) {
                $query->where('tim', $tim);
            }

            // Ambil data (filtered by shift/tim)
            $data = $query->orderBy('tanggal_penerimaan', 'desc')
                ->orderBy('shift')
                ->get();

            // Query semua shift di tanggal yang sama (untuk footer rekapitulasi)
            $dataAllShift = Penerimaan::with(['identitasRod', 'karyawan'])
                ->when($tanggal, fn($q) => $q->whereDate('tanggal_penerimaan', $tanggal))
                ->orderBy('shift')
                ->get()
                ->each(function ($row) {
                    foreach (['s', 'cr', 'c'] as $f) {
                        if ($row->$f > 0) $row->$f = 1;
                    }
                });

            // Hitung jumlah rod per jenis per shift (untuk footer tabel ke-2)
            // Mapping: SP -> R, L -> C
            $jenisMap = ['SP' => 'R', 'L' => 'C'];
            $jenisList = ['E', 'S', 'D', 'B', 'BA', 'CR', 'M', 'R', 'C', 'RL'];

            $dataJenis = collect([1, 2, 3])->mapWithKeys(function ($s) use ($dataAllShift, $jenisMap, $jenisList) {
                $rows = $dataAllShift->where('shift', $s);
                $counts = collect($jenisList)->mapWithKeys(function ($j) use ($rows, $jenisMap) {
                    $count = $rows->filter(function ($row) use ($j, $jenisMap) {
                        // Ambil teks sebelum "/" dan trim
                        $raw = trim(explode('/', $row->jenis)[0]);
                        // Mapping
                        $mapped = $jenisMap[$raw] ?? $raw;
                        return $mapped === $j;
                    })->count();
                    return [$j => $count ?: null];
                });
                $total = $counts->filter()->sum();
                return [$s => $counts->put('TOTAL', $total ?: null)->put('BUTT', null)];
            });
        } else if ($jenisLaporan == 'perbaikan') {

            $query = Perbaikan::with(['penerimaan.identitasRod', 'karyawan']);

            if ($tanggal) {
                $query->whereDate('tanggal_perbaikan', $tanggal);
            }

            // Filter berdasarkan shift
            if ($shift) {
                $query->where('shift', $shift);
            }

            // Filter berdasarkan tim
            if ($tim) {
                $query->where('tim', $tim);
            }

            // Ambil data (filtered by shift/tim)
            $data = $query->orderBy('tanggal_perbaikan', 'desc')
                ->orderBy('shift')
                ->get();

            // Query semua shift di tanggal yang sama (untuk footer rekapitulasi)
            $dataAllShift = Perbaikan::with(['penerimaan.identitasRod', 'karyawan'])
                ->when($tanggal, fn($q) => $q->whereDate('tanggal_perbaikan', $tanggal))
                ->orderBy('shift')
                ->get()
                ->each(function ($row) {
                    foreach (['s', 'cr', 'c'] as $f) {
                        if ($row->$f > 0) $row->$f = 1;
                    }
                });

            // Hitung jumlah rod per jenis per shift (untuk footer tabel ke-2)
            // Mapping: SP -> R, L -> C
            $jenisMap = ['SP' => 'R', 'L' => 'C'];
            $jenisList = ['E', 'S', 'D', 'B', 'BA', 'CR', 'M', 'R', 'C', 'RL'];

            $dataJenis = collect([1, 2, 3])->mapWithKeys(function ($s) use ($dataAllShift, $jenisMap, $jenisList) {
                $rows = $dataAllShift->where('shift', $s);
                $counts = collect($jenisList)->mapWithKeys(function ($j) use ($rows, $jenisMap) {
                    $count = $rows->filter(function ($row) use ($j, $jenisMap) {
                        // Ambil teks sebelum "/" dan trim
                        $raw = trim(explode('/', $row->jenis)[0]);
                        // Mapping
                        $mapped = $jenisMap[$raw] ?? $raw;
                        return $mapped === $j;
                    })->count();
                    return [$j => $count ?: null];
                });
                $total = $counts->filter()->sum();
                return [$s => $counts->put('TOTAL', $total ?: null)->put('BUTT', null)];
            });
        } else if ($jenisLaporan == 'pengiriman') {

            $query = Pengiriman::with(['perbaikan.penerimaan.identitasRod', 'karyawan']);

            if ($tanggal) {
                $query->whereDate('tanggal_pengiriman', $tanggal);
            }

            if ($shift) {
                $query->where('shift', $shift);
            }

            if ($tim) {
                $query->where('tim', $tim);
            }

            $data = $query->orderBy('tanggal_pengiriman', 'asc')
                ->orderBy('shift')
                ->get();

            // dataAllShift tidak diperlukan untuk pengiriman (tidak ada footer rekapitulasi)
            $dataAllShift = collect();
        } else if ($jenisLaporan == 'buktiperubahan') {
            $query = Perbaikan::with(['identitasRod', 'karyawan']);

            if ($tanggal) {
                $query->whereDate('tanggal_perbaikan', $tanggal);
            }

            if ($shift) {
                $query->where('shift', $shift);
            }

            if ($tim) {
                $query->where('tim', $tim);
            }

            $query->whereNotNull('fotobuktiperubahan')
                ->where('fotobuktiperubahan', '!=', '');

            $data = $query->orderBy('tanggal_perbaikan', 'desc')
                ->orderBy('shift')
                ->get();
        }


        // Siapkan data untuk template
        $templateData = [
            'tanggal'      => $tanggal ? \Carbon\Carbon::parse($tanggal)->format('d/m/Y') : date('d/m/Y'),
            'shift'        => $shift ?: 'Semua',
            'tim'          => $tim ?: 'Semua',
            'data'         => $data,
            'dataAllShift' => $dataAllShift,
            'dataJenis'    => $dataJenis ?? collect(),
        ];

        // Load template sesuai jenis laporan
        $viewName = 'laporan._' . $jenisLaporan;

        // Generate PDF
        $pdf = Pdf::loadView($viewName, $templateData);

        // Set paper dengan margin minimal
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOption('margin-top', 2);
        $pdf->setOption('margin-right', 2);
        $pdf->setOption('margin-bottom', 2);
        $pdf->setOption('margin-left', 2);

        // Return PDF untuk preview
        return response($pdf->output(), 200, [
            'Content-Type'  => 'application/pdf',
            'X-Jumlah-Data' => $data->count(),
        ]);
        return $pdf->stream('preview-laporan-' . $jenisLaporan . '.pdf');
    }
}
