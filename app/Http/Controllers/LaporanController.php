<?php

namespace App\Http\Controllers;

use App\Models\Penerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function preview(Request $request)
    {
        $tanggal = $request->tanggal;
        $shift   = trim($request->shift);
        $tim     = trim($request->tim);
        $jenis   = $request->jenis;

        if ($jenis == 'penerimaan') {

            $data = Penerimaan::whereDate('tanggal_penerimaan', $tanggal)
                ->where('shift', $shift)
                ->where('tim', $tim)
                ->get();

            return view('laporan._penerimaan', compact('data', 'tanggal', 'shift', 'tim'));
        }
    }

    public function pdf(Request $request)
    {
        $tanggal = $request->tanggal;
        $shift   = trim($request->shift);
        $tim     = trim($request->tim);

        $data = Penerimaan::with('identitasRod')
            ->whereDate('tanggal_penerimaan', $tanggal)
            ->where('shift', $shift)
            ->where('tim', $tim)
            ->get();

        $pdf = Pdf::loadView('laporan._penerimaan_pdf', compact(
            'data',
            'tanggal',
            'shift',
            'tim'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('laporan_penerimaan_' . $tanggal . '.pdf');
    }
}
