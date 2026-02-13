<?php

namespace App\Http\Controllers;

use App\Models\IdentitasRod;
use App\Models\Pengiriman;
use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Exports\HistoryPengirimanExport;
use Maatwebsite\Excel\Facades\Excel;

class PengirimanController extends Controller
{
    //realtime untuk form entry_data/pengiriman
    public function table(Request $request)
    {
        $query = Perbaikan::with(['penerimaan.identitasRod', 'karyawan'])
            ->whereHas('penerimaan.identitasRod', function ($q) use ($request) {
                $q->where('status', 'Diperbaiki');

                if ($request->filled('cariperbaikan')) {
                    $q->where('nomor_rod', 'like', '%' . $request->cariperbaikan . '%');
                }
            })
            ->latest('tanggal_perbaikan');

        $perbaikan = $query->paginate(30);

        return view('entry_data.partials.pengiriman.perbaikan-table-body', compact('perbaikan'));
    }

    //realtime untuk form entry_data/pengiriman
    public function info(Request $request)
    {
        $query = Perbaikan::with(['penerimaan.identitasRod', 'karyawan'])
            ->whereHas('penerimaan.identitasRod', function ($q) use ($request) {
                $q->where('status', 'Diperbaiki');

                if ($request->filled('cariperbaikan')) {
                    $q->where('nomor_rod', 'like', '%' . $request->cariperbaikan . '%');
                }
            })
            ->latest('tanggal_perbaikan');

        $perbaikan = $query->paginate(30);

        return view('entry_data.partials.pengiriman.perbaikan-info', compact('perbaikan'));
    }

    //tampil dan cari untuk form entry_data/pengiriman
    public function index(Request $request)
    {
        $query = Perbaikan::with(['penerimaan.identitasRod', 'karyawan'])
            ->whereHas('penerimaan.identitasRod', function ($q) use ($request) {
                $q->where('status', 'Diperbaiki');

                if ($request->filled('cariperbaikan')) {
                    $q->where('nomor_rod', 'like', '%' . $request->cariperbaikan . '%');
                }
            })
            ->latest('tanggal_perbaikan');

        $perbaikan = $query->paginate(30);

        return view('entry_data.pengiriman', compact('perbaikan'));
    }

    //realtime untuk history_data/hsitoryperbaikan
    public function hs_table(Request $request)
    {
        $query = Pengiriman::with(['perbaikan.penerimaan.identitasRod', 'karyawan'])

            // Nomor ROD
            ->when($request->filled('nomor_rod'), function ($q) use ($request) {
                $q->whereHas('perbaikan.penerimaan.identitasRod', function ($sub) use ($request) {
                    $sub->where('nomor_rod', 'like', '%' . $request->nomor_rod . '%');
                });
            })

            // Penginput
            ->when($request->filled('penginput'), function ($q) use ($request) {
                $q->whereHas('karyawan', function ($sub) use ($request) {
                    $sub->where('nama_lengkap', 'like', '%' . $request->penginput . '%');
                });
            })

            // tim
            ->when($request->filled('tim'), function ($q) use ($request) {
                $q->where('tim', $request->tim);
            })

            // Shift
            ->when($request->filled('shift'), function ($q) use ($request) {
                $q->where('shift', $request->shift);
            });

        if ($request->filled('tanggalMulai') && $request->filled('tanggalAkhir')) {

            $mulai = Carbon::parse($request->tanggalMulai)->startOfDay();
            $akhir = Carbon::parse($request->tanggalAkhir)->endOfDay();

            $query->whereBetween('tanggal_pengiriman', [$mulai, $akhir]);
        } elseif ($request->filled('tanggalMulai')) {

            $query->whereBetween('tanggal_pengiriman', [
                Carbon::parse($request->tanggalMulai)->startOfDay(),
                Carbon::parse($request->tanggalMulai)->endOfDay()
            ]);
        } elseif ($request->filled('tanggalAkhir')) {

            $query->whereBetween('tanggal_pengiriman', [
                Carbon::parse($request->tanggalAkhir)->startOfDay(),
                Carbon::parse($request->tanggalAkhir)->endOfDay()
            ]);
        }
        $pengiriman = $query
            ->orderBy('tanggal_pengiriman', 'desc')
            ->paginate(30)
            ->withQueryString();

        return view('history_data.partials.pengiriman.historypengiriman-table-body', compact('pengiriman'));
    }

    //realtime untuk form history_data/hsitoryperbaikan
    public function hs_info(Request $request)
    {
        $query = Pengiriman::with(['perbaikan.penerimaan.identitasRod', 'karyawan'])

            // Nomor ROD
            ->when($request->filled('nomor_rod'), function ($q) use ($request) {
                $q->whereHas('perbaikan.penerimaan.identitasRod', function ($sub) use ($request) {
                    $sub->where('nomor_rod', 'like', '%' . $request->nomor_rod . '%');
                });
            })

            // Penginput
            ->when($request->filled('penginput'), function ($q) use ($request) {
                $q->whereHas('karyawan', function ($sub) use ($request) {
                    $sub->where('nama_lengkap', 'like', '%' . $request->penginput . '%');
                });
            })

            // tim
            ->when($request->filled('tim'), function ($q) use ($request) {
                $q->where('tim', $request->tim);
            })

            // Shift
            ->when($request->filled('shift'), function ($q) use ($request) {
                $q->where('shift', $request->shift);
            });

        if ($request->filled('tanggalMulai') && $request->filled('tanggalAkhir')) {

            $mulai = Carbon::parse($request->tanggalMulai)->startOfDay();
            $akhir = Carbon::parse($request->tanggalAkhir)->endOfDay();

            $query->whereBetween('tanggal_pengiriman', [$mulai, $akhir]);
        } elseif ($request->filled('tanggalMulai')) {

            $query->whereBetween('tanggal_pengiriman', [
                Carbon::parse($request->tanggalMulai)->startOfDay(),
                Carbon::parse($request->tanggalMulai)->endOfDay()
            ]);
        } elseif ($request->filled('tanggalAkhir')) {

            $query->whereBetween('tanggal_pengiriman', [
                Carbon::parse($request->tanggalAkhir)->startOfDay(),
                Carbon::parse($request->tanggalAkhir)->endOfDay()
            ]);
        }
        $pengiriman = $query
            ->orderBy('tanggal_pengiriman', 'desc')
            ->paginate(30)
            ->withQueryString();

        return view('history_data.partials.pengiriman.historypengiriman-info', compact('pengiriman'));
    }

    public function indexHistory(Request $request)
    {
        $query = Pengiriman::with(['perbaikan.penerimaan.identitasRod', 'karyawan'])

            // Nomor ROD
            ->when($request->filled('nomor_rod'), function ($q) use ($request) {
                $q->whereHas('perbaikan.penerimaan.identitasRod', function ($sub) use ($request) {
                    $sub->where('nomor_rod', 'like', '%' . $request->nomor_rod . '%');
                });
            })

            // Penginput
            ->when($request->filled('penginput'), function ($q) use ($request) {
                $q->whereHas('karyawan', function ($sub) use ($request) {
                    $sub->where('nama_lengkap', 'like', '%' . $request->penginput . '%');
                });
            })

            // tim
            ->when($request->filled('tim'), function ($q) use ($request) {
                $q->where('tim', $request->tim);
            })

            // Shift
            ->when($request->filled('shift'), function ($q) use ($request) {
                $q->where('shift', $request->shift);
            });

        if ($request->filled('tanggalMulai') && $request->filled('tanggalAkhir')) {

            $mulai = Carbon::parse($request->tanggalMulai)->startOfDay();
            $akhir = Carbon::parse($request->tanggalAkhir)->endOfDay();

            $query->whereBetween('tanggal_pengiriman', [$mulai, $akhir]);
        } elseif ($request->filled('tanggalMulai')) {

            $query->whereBetween('tanggal_pengiriman', [
                Carbon::parse($request->tanggalMulai)->startOfDay(),
                Carbon::parse($request->tanggalMulai)->endOfDay()
            ]);
        } elseif ($request->filled('tanggalAkhir')) {

            $query->whereBetween('tanggal_pengiriman', [
                Carbon::parse($request->tanggalAkhir)->startOfDay(),
                Carbon::parse($request->tanggalAkhir)->endOfDay()
            ]);
        }
        $pengiriman = $query
            ->orderBy('tanggal_pengiriman', 'desc')
            ->paginate(30)
            ->withQueryString();

        return view('history_data.historypengiriman', compact('pengiriman'));
    }

    //cek Nomor ROD di input pengiriman
    public function cekRod($nomor)
    {
        $ada = \App\Models\IdentitasRod::where('nomor_rod', $nomor)
            ->where('status', 'Diperbaiki')
            ->exists();

        return response()->json([
            'exists' => $ada
        ]);
    }

    //simpan pengiriman
    public function store(Request $request)
    {
        $rods = collect($request->only([
            'rod1',
            'rod2',
            'rod3',
            'rod4',
            'rod5',
            'rod6',
            'rod7',
            'rod8',
            'rod9',
            'rod10'
        ]))
            ->filter()
            ->map(fn($r) => strtoupper(trim($r)))
            ->unique();

        if ($rods->isEmpty()) {
            return back()->with('warning', 'Tidak ada ROD yang diinput.');
        }

        DB::transaction(function () use ($rods, $request) {

            foreach ($rods as $nomorRod) {

                $rod = IdentitasRod::where('nomor_rod', $nomorRod)
                    ->where('status', 'Diperbaiki')
                    ->first();

                if (!$rod) {
                    throw new \Exception("ROD $nomorRod tidak ditemukan atau belum diperbaiki.");
                }

                $perbaikan = Perbaikan::where('penerimaan_id', $rod->penerimaan->id)
                    ->latest('tanggal_perbaikan')
                    ->first();

                if (!$perbaikan) {
                    throw new \Exception("Data perbaikan untuk ROD $nomorRod tidak ditemukan.");
                }

                Pengiriman::create([
                    'perbaikan_id' => $perbaikan->id,
                    'tanggal_pengiriman' => $request->master_datetime,
                    'shift' => $request->master_shift,
                    'id_karyawan' => Session::get('karyawan_id'),
                    'tim' => Session::get('tim'),
                ]);

                $rod->update([
                    'status' => 'Selesai'
                ]);

                $perbaikan->touch();
            }
        });

        return back()->with('success', 'Pengiriman berhasil disimpan.');
    }

    //Export pengiriman
    public function export(Request $request)
    {
        return Excel::download(
            new HistoryPengirimanExport($request->all()),
            'Data-Pengiriman-' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }
}
