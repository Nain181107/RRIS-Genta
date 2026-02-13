<?php

namespace App\Http\Controllers;

use App\Models\Penerimaan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function setMasterTime(Request $request)
    {
        session([
            'master_datetime' => $request->master_datetime,
            'master_shift'    => $request->master_shift,
        ]);

        return response()->json(['status' => 'ok']);
    }


    public function index()
    {
        $shift   = session('master_shift');
        $tanggal = session('master_datetime');

        // RRS (TIDAK pakai tanggal & shift)
        $data = DB::table('identitas_rod')
            ->selectRaw("
            SUM(CASE WHEN status = 'Diterima' THEN 1 ELSE 0 END) AS stock_reject,
            SUM(CASE WHEN status = 'Diperbaiki' THEN 1 ELSE 0 END) AS stock_ready
        ")
            ->whereIn('status', ['Diterima', 'Diperbaiki'])
            ->first();

        $total_rrs = ($data->stock_reject ?? 0) + ($data->stock_ready ?? 0);

        // Ambil tanggal saja dari datetime
        $tanggal_only = $tanggal ? date('Y-m-d', strtotime($tanggal)) : date('Y-m-d');

        // Penerimaan - hitung semua baris di tanggal dan shift ini
        $penerimaan = DB::table('penerimaan')
            ->whereDate('tanggal_penerimaan', $tanggal_only)
            ->where('shift', $shift)
            ->count();

        // Perbaikan - hitung semua baris di tanggal dan shift ini
        $perbaikan = DB::table('perbaikan')
            ->whereDate('tanggal_perbaikan', $tanggal_only)
            ->where('shift', $shift)
            ->count();

        // Pengiriman - hitung semua baris di tanggal dan shift ini
        $pengiriman = DB::table('pengiriman')
            ->whereDate('tanggal_pengiriman', $tanggal_only)
            ->where('shift', $shift)
            ->count();

        [$labels, $values] = $this->stockreject();

        return view('dashboard', compact(
            'total_rrs',
            'data',
            'penerimaan',
            'perbaikan',
            'pengiriman',
            'labels',
            'values'
        ));
    }

    public function filter(Request $request)
    {
        $jenisData = $request->jenis_data;
        $rentangWaktu = $request->rentang_waktu;
        $tipe = $request->tipe;

        $labels = [];
        $values = [];

        if ($jenisData === 'stock-rod-stasiun') {

            if ($rentangWaktu === 'hari') {
                $tanggal = $request->tanggal;
                list($labels, $values) = $this->stockRejectByStationHari($tanggal);
            } elseif ($rentangWaktu === 'bulan') {
                $bulan = $request->bulan;
                list($labels, $values) = $this->stockRejectByStationBulan($bulan);
            } elseif ($rentangWaktu === 'tahun') {
                $tahun = $request->tahun;
                list($labels, $values) = $this->stockRejectByStationTahun($tahun);
            } elseif ($rentangWaktu === 'custom') {
                $tanggalMulai = $request->tanggal_mulai;
                $tanggalAkhir = $request->tanggal_akhir;
                list($labels, $values) = $this->stockRejectByStationCustom($tanggalMulai, $tanggalAkhir);
            }
        }

        if ($jenisData === 'stock-rod') {
            list($labels, $values) = $this->stockreject();
        }

        if ($jenisData === 'trend-kerusakan') {

            if ($tipe === 'Penerimaan') {
                if ($rentangWaktu === 'hari') {
                    $tanggal = $request->tanggal;
                    list($labels, $values) = $this->penerimaanJKhari($tanggal);
                } elseif ($rentangWaktu === 'bulan') {
                    $bulan = $request->bulan;
                    list($labels, $values) = $this->penerimaanJKBulan($bulan);
                } elseif ($rentangWaktu === 'tahun') {
                    $tahun = $request->tahun;
                    list($labels, $values) = $this->penerimaanJKTahun($tahun);
                } elseif ($rentangWaktu === 'custom') {
                    $tanggalMulai = $request->tanggal_mulai;
                    $tanggalAkhir = $request->tanggal_akhir;
                    list($labels, $values) = $this->penerimaanJKCustom($tanggalMulai, $tanggalAkhir);
                }
            } else {
                if ($rentangWaktu === 'hari') {
                    $tanggal = $request->tanggal;
                    list($labels, $values) = $this->perbaikanJKhari($tanggal);
                } elseif ($rentangWaktu === 'bulan') {
                    $bulan = $request->bulan;
                    list($labels, $values) = $this->perbaikanJKBulan($bulan);
                } elseif ($rentangWaktu === 'tahun') {
                    $tahun = $request->tahun;
                    list($labels, $values) = $this->perbaikanJKTahun($tahun);
                } elseif ($rentangWaktu === 'custom') {
                    $tanggalMulai = $request->tanggal_mulai;
                    $tanggalAkhir = $request->tanggal_akhir;
                    list($labels, $values) = $this->perbaikanJKCustom($tanggalMulai, $tanggalAkhir);
                }
            }
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values
        ]);
    }

    public function stockreject()
    {
        $labels = ['E', 'S', 'D', 'B', 'BA', 'CR', 'M', 'R', 'C', 'RL'];

        $chartData = DB::table(DB::raw("(
            SELECT 
                CASE 
                    WHEN SUBSTRING_INDEX(p.jenis,'/',1) = 'SP' THEN 'R'
                    WHEN SUBSTRING_INDEX(p.jenis,'/',1) = 'L'  THEN 'C'
                    ELSE SUBSTRING_INDEX(p.jenis,'/',1)
                END AS jenis_mapped
            FROM penerimaan p
            JOIN identitas_rod i ON p.rod_id = i.id
            WHERE i.status = 'Diterima'
        ) as x"))
            ->selectRaw("
            SUM(CASE WHEN jenis_mapped = 'E'  THEN 1 ELSE 0 END) AS E,
            SUM(CASE WHEN jenis_mapped = 'S'  THEN 1 ELSE 0 END) AS S,
            SUM(CASE WHEN jenis_mapped = 'D'  THEN 1 ELSE 0 END) AS D,
            SUM(CASE WHEN jenis_mapped = 'B'  THEN 1 ELSE 0 END) AS B,
            SUM(CASE WHEN jenis_mapped = 'BA' THEN 1 ELSE 0 END) AS BA,
            SUM(CASE WHEN jenis_mapped = 'CR' THEN 1 ELSE 0 END) AS CR,
            SUM(CASE WHEN jenis_mapped = 'M'  THEN 1 ELSE 0 END) AS M,
            SUM(CASE WHEN jenis_mapped = 'R'  THEN 1 ELSE 0 END) AS R,
            SUM(CASE WHEN jenis_mapped = 'C'  THEN 1 ELSE 0 END) AS C,
            SUM(CASE WHEN jenis_mapped = 'RL' THEN 1 ELSE 0 END) AS RL
        ")
            ->first();

        $values = collect($labels)->map(fn($l) => $chartData->$l ?? 0);

        return [$labels, $values];
    }

    public function stockRejectByStationHari($tanggal)
    {
        $labels = ['A', 'B', 'C', 'D', 'E', 'F', 'G-Z'];

        $chartData = DB::table('penerimaan')
            ->whereDate('tanggal_penerimaan', $tanggal)
            ->selectRaw("
            SUM(CASE WHEN stasiun = 'A' THEN 1 ELSE 0 END) AS A,
            SUM(CASE WHEN stasiun = 'B' THEN 1 ELSE 0 END) AS B,
            SUM(CASE WHEN stasiun = 'C' THEN 1 ELSE 0 END) AS C,
            SUM(CASE WHEN stasiun = 'D' THEN 1 ELSE 0 END) AS D,
            SUM(CASE WHEN stasiun = 'E' THEN 1 ELSE 0 END) AS E,
            SUM(CASE WHEN stasiun = 'F' THEN 1 ELSE 0 END) AS F,
            SUM(CASE WHEN stasiun >= 'G' AND stasiun <= 'Z' THEN 1 ELSE 0 END) AS GZ
        ")
            ->first();

        $values = [
            $chartData->A ?? 0,
            $chartData->B ?? 0,
            $chartData->C ?? 0,
            $chartData->D ?? 0,
            $chartData->E ?? 0,
            $chartData->F ?? 0,
            $chartData->GZ ?? 0
        ];

        return [$labels, $values];
    }

    public function stockRejectByStationBulan($bulan)
    {
        $labels = ['A', 'B', 'C', 'D', 'E', 'F', 'G-Z'];

        // $bulan format: 2025-01
        list($tahun, $bulanAngka) = explode('-', $bulan);

        $chartData = DB::table('penerimaan')
            ->whereMonth('tanggal_penerimaan', $bulanAngka)
            ->whereYear('tanggal_penerimaan', $tahun)
            ->selectRaw("
            SUM(CASE WHEN stasiun = 'A' THEN 1 ELSE 0 END) AS A,
            SUM(CASE WHEN stasiun = 'B' THEN 1 ELSE 0 END) AS B,
            SUM(CASE WHEN stasiun = 'C' THEN 1 ELSE 0 END) AS C,
            SUM(CASE WHEN stasiun = 'D' THEN 1 ELSE 0 END) AS D,
            SUM(CASE WHEN stasiun = 'E' THEN 1 ELSE 0 END) AS E,
            SUM(CASE WHEN stasiun = 'F' THEN 1 ELSE 0 END) AS F,
            SUM(CASE WHEN stasiun >= 'G' AND stasiun <= 'Z' THEN 1 ELSE 0 END) AS GZ
        ")
            ->first();

        $values = [
            $chartData->A ?? 0,
            $chartData->B ?? 0,
            $chartData->C ?? 0,
            $chartData->D ?? 0,
            $chartData->E ?? 0,
            $chartData->F ?? 0,
            $chartData->GZ ?? 0
        ];

        return [$labels, $values];
    }

    public function stockRejectByStationTahun($tahun)
    {
        $labels = ['A', 'B', 'C', 'D', 'E', 'F', 'G-Z'];

        $chartData = DB::table('penerimaan')
            ->whereYear('tanggal_penerimaan', $tahun)
            ->selectRaw("
            SUM(CASE WHEN stasiun = 'A' THEN 1 ELSE 0 END) AS A,
            SUM(CASE WHEN stasiun = 'B' THEN 1 ELSE 0 END) AS B,
            SUM(CASE WHEN stasiun = 'C' THEN 1 ELSE 0 END) AS C,
            SUM(CASE WHEN stasiun = 'D' THEN 1 ELSE 0 END) AS D,
            SUM(CASE WHEN stasiun = 'E' THEN 1 ELSE 0 END) AS E,
            SUM(CASE WHEN stasiun = 'F' THEN 1 ELSE 0 END) AS F,
            SUM(CASE WHEN stasiun >= 'G' AND stasiun <= 'Z' THEN 1 ELSE 0 END) AS GZ
        ")
            ->first();

        $values = [
            $chartData->A ?? 0,
            $chartData->B ?? 0,
            $chartData->C ?? 0,
            $chartData->D ?? 0,
            $chartData->E ?? 0,
            $chartData->F ?? 0,
            $chartData->GZ ?? 0
        ];

        return [$labels, $values];
    }

    public function stockRejectByStationCustom($tanggalMulai, $tanggalAkhir)
    {
        $labels = ['A', 'B', 'C', 'D', 'E', 'F', 'G-Z'];

        $chartData = DB::table('penerimaan')
            ->whereRaw('DATE(tanggal_penerimaan) BETWEEN ? AND ?', [$tanggalMulai, $tanggalAkhir])
            ->selectRaw("
            SUM(CASE WHEN stasiun = 'A' THEN 1 ELSE 0 END) AS A,
            SUM(CASE WHEN stasiun = 'B' THEN 1 ELSE 0 END) AS B,
            SUM(CASE WHEN stasiun = 'C' THEN 1 ELSE 0 END) AS C,
            SUM(CASE WHEN stasiun = 'D' THEN 1 ELSE 0 END) AS D,
            SUM(CASE WHEN stasiun = 'E' THEN 1 ELSE 0 END) AS E,
            SUM(CASE WHEN stasiun = 'F' THEN 1 ELSE 0 END) AS F,
            SUM(CASE WHEN stasiun >= 'G' AND stasiun <= 'Z' THEN 1 ELSE 0 END) AS GZ
        ")
            ->first();

        $values = [
            $chartData->A ?? 0,
            $chartData->B ?? 0,
            $chartData->C ?? 0,
            $chartData->D ?? 0,
            $chartData->E ?? 0,
            $chartData->F ?? 0,
            $chartData->GZ ?? 0
        ];

        return [$labels, $values];
    }

    public function penerimaanJKhari($tanggal)
    {
        $labels = ['E1', 'E2', 'E3', 'S', 'D', 'B', 'BA', 'R', 'M', 'CR', 'C', 'RL'];

        $chartData = DB::table('penerimaan')
            ->whereDate('tanggal_penerimaan', $tanggal)
            ->selectRaw("
                        SUM(e1) AS TotalE1,
                        SUM(e2) AS TotalE2,
                        SUM(e3) AS TotalE3,
                        SUM(s)  AS TotalS,
                        SUM(d)  AS TotalD,
                        SUM(b)  AS TotalB,
                        SUM(ba) AS TotalBA,
                        SUM(r) AS TotalR,
                        SUM(m)  AS TotalM,
                        SUM(cr)  AS TotalCR,
                        SUM(c)  AS TotalC,
                        SUM(rl) AS TotalRL
            ")
            ->first();

        $values = [
            $chartData->TotalE1 ?? 0,
            $chartData->TotalE2 ?? 0,
            $chartData->TotalE3 ?? 0,
            $chartData->TotalS ?? 0,
            $chartData->TotalD ?? 0,
            $chartData->TotalB ?? 0,
            $chartData->TotalBA ?? 0,
            $chartData->TotalR ?? 0,
            $chartData->TotalM ?? 0,
            $chartData->TotalCR ?? 0,
            $chartData->TotalC ?? 0,
            $chartData->TotalRL ?? 0
        ];

        return [$labels, $values];
    }

    public function penerimaanJKBulan($bulan)
    {
        $labels = ['E1', 'E2', 'E3', 'S', 'D', 'B', 'BA', 'R', 'M', 'CR', 'C', 'RL'];

        list($tahun, $bulanAngka) = explode('-', $bulan);

        $chartData = DB::table('penerimaan')
            ->whereMonth('tanggal_penerimaan', $bulanAngka)
            ->whereYear('tanggal_penerimaan', $tahun)
            ->selectRaw("
                        SUM(e1) AS TotalE1,
                        SUM(e2) AS TotalE2,
                        SUM(e3) AS TotalE3,
                        SUM(s)  AS TotalS,
                        SUM(d)  AS TotalD,
                        SUM(b)  AS TotalB,
                        SUM(ba) AS TotalBA,
                        SUM(r) AS TotalR,
                        SUM(m)  AS TotalM,
                        SUM(cr)  AS TotalCR,
                        SUM(c)  AS TotalC,
                        SUM(rl) AS TotalRL
            ")
            ->first();

        $values = [
            $chartData->TotalE1 ?? 0,
            $chartData->TotalE2 ?? 0,
            $chartData->TotalE3 ?? 0,
            $chartData->TotalS ?? 0,
            $chartData->TotalD ?? 0,
            $chartData->TotalB ?? 0,
            $chartData->TotalBA ?? 0,
            $chartData->TotalR ?? 0,
            $chartData->TotalM ?? 0,
            $chartData->TotalCR ?? 0,
            $chartData->TotalC ?? 0,
            $chartData->TotalRL ?? 0
        ];

        return [$labels, $values];
    }

    public function penerimaanJKTahun($tahun)
    {
        $labels = ['E1', 'E2', 'E3', 'S', 'D', 'B', 'BA', 'R', 'M', 'CR', 'C', 'RL'];

        $chartData = DB::table('penerimaan')
            ->whereYear('tanggal_penerimaan', $tahun)
            ->selectRaw("
                        SUM(e1) AS TotalE1,
                        SUM(e2) AS TotalE2,
                        SUM(e3) AS TotalE3,
                        SUM(s)  AS TotalS,
                        SUM(d)  AS TotalD,
                        SUM(b)  AS TotalB,
                        SUM(ba) AS TotalBA,
                        SUM(r) AS TotalR,
                        SUM(m)  AS TotalM,
                        SUM(cr)  AS TotalCR,
                        SUM(c)  AS TotalC,
                        SUM(rl) AS TotalRL
            ")
            ->first();

        $values = [
            $chartData->TotalE1 ?? 0,
            $chartData->TotalE2 ?? 0,
            $chartData->TotalE3 ?? 0,
            $chartData->TotalS ?? 0,
            $chartData->TotalD ?? 0,
            $chartData->TotalB ?? 0,
            $chartData->TotalBA ?? 0,
            $chartData->TotalR ?? 0,
            $chartData->TotalM ?? 0,
            $chartData->TotalCR ?? 0,
            $chartData->TotalC ?? 0,
            $chartData->TotalRL ?? 0
        ];

        return [$labels, $values];
    }

    public function penerimaanJKCustom($tanggalMulai, $tanggalAkhir)
    {
        $labels = ['E1', 'E2', 'E3', 'S', 'D', 'B', 'BA', 'R', 'M', 'CR', 'C', 'RL'];

        $chartData = DB::table('penerimaan')
            ->whereRaw('DATE(tanggal_penerimaan) BETWEEN ? AND ?', [$tanggalMulai, $tanggalAkhir])
            ->selectRaw("
                        SUM(e1) AS TotalE1,
                        SUM(e2) AS TotalE2,
                        SUM(e3) AS TotalE3,
                        SUM(s)  AS TotalS,
                        SUM(d)  AS TotalD,
                        SUM(b)  AS TotalB,
                        SUM(ba) AS TotalBA,
                        SUM(r) AS TotalR,
                        SUM(m)  AS TotalM,
                        SUM(cr)  AS TotalCR,
                        SUM(c)  AS TotalC,
                        SUM(rl) AS TotalRL
            ")
            ->first();

        $values = [
            $chartData->TotalE1 ?? 0,
            $chartData->TotalE2 ?? 0,
            $chartData->TotalE3 ?? 0,
            $chartData->TotalS ?? 0,
            $chartData->TotalD ?? 0,
            $chartData->TotalB ?? 0,
            $chartData->TotalBA ?? 0,
            $chartData->TotalR ?? 0,
            $chartData->TotalM ?? 0,
            $chartData->TotalCR ?? 0,
            $chartData->TotalC ?? 0,
            $chartData->TotalRL ?? 0
        ];

        return [$labels, $values];
    }

    public function perbaikanJKhari($tanggal)
    {
        $labels = ['E1', 'E2', 'E3', 'E4', 'S', 'D', 'B', 'BA', 'BA-1', 'R', 'M', 'CR', 'C', 'RL'];

        $chartData = DB::table('perbaikan')
            ->whereDate('tanggal_perbaikan', $tanggal)
            ->selectRaw("
                        SUM(e1_jumlah) AS TotalE1,
                        SUM(e2_jumlah) AS TotalE2,
                        SUM(e3) AS TotalE3,
                        SUM(e4) AS TotalE4,
                        SUM(s)  AS TotalS,
                        SUM(d)  AS TotalD,
                        SUM(b)  AS TotalB,
                        SUM(ba) AS TotalBA,
                        SUM(ba1) AS TotalBA1,
                        SUM(r) AS TotalR,
                        SUM(m)  AS TotalM,
                        SUM(cr)  AS TotalCR,
                        SUM(c)  AS TotalC,
                        SUM(rl) AS TotalRL
            ")
            ->first();

        $values = [
            $chartData->TotalE1 ?? 0,
            $chartData->TotalE2 ?? 0,
            $chartData->TotalE3 ?? 0,
            $chartData->TotalE4 ?? 0,
            $chartData->TotalS ?? 0,
            $chartData->TotalD ?? 0,
            $chartData->TotalB ?? 0,
            $chartData->TotalBA ?? 0,
            $chartData->TotalBA1 ?? 0,
            $chartData->TotalR ?? 0,
            $chartData->TotalM ?? 0,
            $chartData->TotalCR ?? 0,
            $chartData->TotalC ?? 0,
            $chartData->TotalRL ?? 0
        ];

        return [$labels, $values];
    }

    public function perbaikanJKBulan($bulan)
    {
        $labels = ['E1', 'E2', 'E3', 'E4', 'S', 'D', 'B', 'BA', 'BA-1', 'R', 'M', 'CR', 'C', 'RL'];

        list($tahun, $bulanAngka) = explode('-', $bulan);

        $chartData = DB::table('perbaikan')
            ->whereMonth('tanggal_perbaikan', $bulanAngka)
            ->whereYear('tanggal_perbaikan', $tahun)
            ->selectRaw("
                        SUM(e1_jumlah) AS TotalE1,
                        SUM(e2_jumlah) AS TotalE2,
                        SUM(e3) AS TotalE3,
                        SUM(e4) AS TotalE4,
                        SUM(s)  AS TotalS,
                        SUM(d)  AS TotalD,
                        SUM(b)  AS TotalB,
                        SUM(ba) AS TotalBA,
                        SUM(ba1) AS TotalBA1,
                        SUM(r) AS TotalR,
                        SUM(m)  AS TotalM,
                        SUM(cr)  AS TotalCR,
                        SUM(c)  AS TotalC,
                        SUM(rl) AS TotalRL
            ")
            ->first();

        $values = [
            $chartData->TotalE1 ?? 0,
            $chartData->TotalE2 ?? 0,
            $chartData->TotalE3 ?? 0,
            $chartData->TotalE4 ?? 0,
            $chartData->TotalS ?? 0,
            $chartData->TotalD ?? 0,
            $chartData->TotalB ?? 0,
            $chartData->TotalBA ?? 0,
            $chartData->TotalBA1 ?? 0,
            $chartData->TotalR ?? 0,
            $chartData->TotalM ?? 0,
            $chartData->TotalCR ?? 0,
            $chartData->TotalC ?? 0,
            $chartData->TotalRL ?? 0
        ];

        return [$labels, $values];
    }

    public function perbaikanJKTahun($tahun)
    {
        $labels = ['E1', 'E2', 'E3', 'E4', 'S', 'D', 'B', 'BA', 'BA-1', 'R', 'M', 'CR', 'C', 'RL'];

        $chartData = DB::table('perbaikan')
            ->whereYear('tanggal_perbaikan', $tahun)
            ->selectRaw("
                        SUM(e1_jumlah) AS TotalE1,
                        SUM(e2_jumlah) AS TotalE2,
                        SUM(e3) AS TotalE3,
                        SUM(e4) AS TotalE4,
                        SUM(s)  AS TotalS,
                        SUM(d)  AS TotalD,
                        SUM(b)  AS TotalB,
                        SUM(ba) AS TotalBA,
                        SUM(ba1) AS TotalBA1,
                        SUM(r) AS TotalR,
                        SUM(m)  AS TotalM,
                        SUM(cr)  AS TotalCR,
                        SUM(c)  AS TotalC,
                        SUM(rl) AS TotalRL
            ")
            ->first();

        $values = [
            $chartData->TotalE1 ?? 0,
            $chartData->TotalE2 ?? 0,
            $chartData->TotalE3 ?? 0,
            $chartData->TotalE4 ?? 0,
            $chartData->TotalS ?? 0,
            $chartData->TotalD ?? 0,
            $chartData->TotalB ?? 0,
            $chartData->TotalBA ?? 0,
            $chartData->TotalBA1 ?? 0,
            $chartData->TotalR ?? 0,
            $chartData->TotalM ?? 0,
            $chartData->TotalCR ?? 0,
            $chartData->TotalC ?? 0,
            $chartData->TotalRL ?? 0
        ];

        return [$labels, $values];
    }

    public function perbaikanJKCustom($tanggalMulai, $tanggalAkhir)
    {
        $labels = ['E1', 'E2', 'E3', 'E4', 'S', 'D', 'B', 'BA', 'BA-1', 'R', 'M', 'CR', 'C', 'RL'];

        $chartData = DB::table('perbaikan')
            ->whereRaw('DATE(tanggal_perbaikan) BETWEEN ? AND ?', [$tanggalMulai, $tanggalAkhir])
            ->selectRaw("
                        SUM(e1_jumlah) AS TotalE1,
                        SUM(e2_jumlah) AS TotalE2,
                        SUM(e3) AS TotalE3,
                        SUM(e4) AS TotalE4,
                        SUM(s)  AS TotalS,
                        SUM(d)  AS TotalD,
                        SUM(b)  AS TotalB,
                        SUM(ba) AS TotalBA,
                        SUM(ba1) AS TotalBA1,
                        SUM(r) AS TotalR,
                        SUM(m)  AS TotalM,
                        SUM(cr)  AS TotalCR,
                        SUM(c)  AS TotalC,
                        SUM(rl) AS TotalRL
            ")
            ->first();

        $values = [
            $chartData->TotalE1 ?? 0,
            $chartData->TotalE2 ?? 0,
            $chartData->TotalE3 ?? 0,
            $chartData->TotalE4 ?? 0,
            $chartData->TotalS ?? 0,
            $chartData->TotalD ?? 0,
            $chartData->TotalB ?? 0,
            $chartData->TotalBA ?? 0,
            $chartData->TotalBA1 ?? 0,
            $chartData->TotalR ?? 0,
            $chartData->TotalM ?? 0,
            $chartData->TotalCR ?? 0,
            $chartData->TotalC ?? 0,
            $chartData->TotalRL ?? 0
        ];

        return [$labels, $values];
    }
}
