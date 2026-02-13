<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\IdentitasRod;
use App\Models\Penerimaan;
use App\Models\HistoryPenerimaan;
use Illuminate\Support\Facades\Session;
use App\Events\DataChanged;
use Carbon\Carbon;
use App\Exports\HistoryPenerimaanExport;
use Maatwebsite\Excel\Facades\Excel;

class PenerimaanController extends Controller
{
    //realtime untuk form entry_data/penerimaan
    public function table(Request $request)
    {
        $query = Penerimaan::with(['identitasRod', 'karyawan'])
            ->whereHas('identitasRod', function ($q) use ($request) {
                $q->where('status', 'Diterima');

                if ($request->filled('caripenerimaan')) {
                    $q->where('nomor_rod', 'like', '%' . $request->caripenerimaan . '%');
                }
            })
            ->latest('tanggal_penerimaan');

        $penerimaan = $query->paginate(30);

        return view('entry_data.partials.penerimaan.penerimaan-table-body', compact('penerimaan'));
    }

    //realtime untuk form entry_data/penerimaan
    public function info(Request $request)
    {
        $query = Penerimaan::with('identitasRod')
            ->whereHas('identitasRod', function ($q) use ($request) {
                $q->where('status', 'Diterima');

                if ($request->filled('caripenerimaan')) {
                    $q->where('nomor_rod', 'like', '%' . $request->caripenerimaan . '%');
                }
            })
            ->latest('tanggal_penerimaan');

        $penerimaan = $query->paginate(30);

        return view('entry_data.partials.penerimaan.penerimaan-info', compact('penerimaan'));
    }

    //tampil dan cari untuk form entry_data/penerimaan
    public function index(Request $request)
    {
        $hariPerputaran = DB::table('perputaran_rod')->value('hari') ?? 24;

        $query = Penerimaan::with(['identitasRod', 'karyawan'])
            ->whereHas('identitasRod', function ($q) use ($request) {

                $q->where('status', 'Diterima');

                if ($request->filled('caripenerimaan')) {
                    $q->where('nomor_rod', 'like', '%' . $request->caripenerimaan . '%');
                }
            })
            ->orderBy('tanggal_penerimaan', 'desc');

        $penerimaan = $query->paginate(30)->withQueryString();

        return view('entry_data.penerimaan', compact('penerimaan', 'hariPerputaran'));
    }

    //realtime untuk form edit_data/editpenerimaan
    public function ep_table(Request $request)
    {
        $query = Penerimaan::with(['identitasRod', 'karyawan'])

            ->when($request->filled('nomor_rod'), function ($q) use ($request) {
                $q->whereHas('identitasRod', function ($sub) use ($request) {
                    $sub->where('nomor_rod', 'like', '%' . $request->nomor_rod . '%');
                });
            })

            ->when($request->filled('tanggal'), function ($q) use ($request) {
                $q->whereDate('tanggal_penerimaan', $request->tanggal);
            })

            ->orderBy('tanggal_penerimaan', 'desc');

        $penerimaan = $query->paginate(30)->withQueryString();

        return view('edit_data.partials.penerimaan.editpenerimaan-table-body', compact('penerimaan'));
    }

    //realtime untuk form edit_data/editpenerimaan
    public function ep_info(Request $request)
    {
        $query = Penerimaan::with(['identitasRod', 'karyawan'])

            ->when($request->filled('nomor_rod'), function ($q) use ($request) {
                $q->whereHas('identitasRod', function ($sub) use ($request) {
                    $sub->where('nomor_rod', 'like', '%' . $request->nomor_rod . '%');
                });
            })

            ->when($request->filled('tanggal'), function ($q) use ($request) {
                $q->whereDate('tanggal_penerimaan', $request->tanggal);
            })

            ->orderBy('tanggal_penerimaan', 'desc');

        $penerimaan = $query->paginate(30)->withQueryString();

        return view('edit_data.partials.penerimaan.editpenerimaan-info', compact('penerimaan'));
    }

    //tampil dan cari untuk form edit_data/editpenerimaan
    public function indexEdit(Request $request)
    {
        $query = Penerimaan::with(['identitasRod', 'karyawan'])

            ->when($request->filled('nomor_rod'), function ($q) use ($request) {
                $q->whereHas('identitasRod', function ($sub) use ($request) {
                    $sub->where('nomor_rod', 'like', '%' . $request->nomor_rod . '%');
                });
            })

            ->when($request->filled('tanggal'), function ($q) use ($request) {
                $q->whereDate('tanggal_penerimaan', $request->tanggal);
            })

            ->orderBy('tanggal_penerimaan', 'desc');

        $penerimaan = $query->paginate(30)->withQueryString();

        return view('edit_data.editpenerimaan', compact('penerimaan'));
    }

    //realtime untuk history_data/hsitorypenerimaan
    public function hs_table(Request $request)
    {
        $query = Penerimaan::with(['identitasRod', 'karyawan'])

            // Nomor ROD
            ->when($request->filled('nomor_rod'), function ($q) use ($request) {
                $q->whereHas('identitasRod', function ($sub) use ($request) {
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

            // jenis
            ->when($request->filled('jenis'), function ($q) use ($request) {
                $q->where('jenis', $request->jenis);
            })

            // Stasiun
            ->when($request->filled('stasiun'), function ($q) use ($request) {
                $q->where('stasiun', $request->stasiun);
            })

            // Shift
            ->when($request->filled('shift'), function ($q) use ($request) {
                $q->where('shift', $request->shift);
            });

        if ($request->filled('tanggalMulai') && $request->filled('tanggalAkhir')) {

            $mulai = Carbon::parse($request->tanggalMulai)->startOfDay();
            $akhir = Carbon::parse($request->tanggalAkhir)->endOfDay();

            $query->whereBetween('tanggal_penerimaan', [$mulai, $akhir]);
        } elseif ($request->filled('tanggalMulai')) {

            $query->whereBetween('tanggal_penerimaan', [
                Carbon::parse($request->tanggalMulai)->startOfDay(),
                Carbon::parse($request->tanggalMulai)->endOfDay()
            ]);
        } elseif ($request->filled('tanggalAkhir')) {

            $query->whereBetween('tanggal_penerimaan', [
                Carbon::parse($request->tanggalAkhir)->startOfDay(),
                Carbon::parse($request->tanggalAkhir)->endOfDay()
            ]);
        }
        $penerimaan = $query
            ->orderBy('tanggal_penerimaan', 'desc')
            ->paginate(30)
            ->withQueryString();

        return view('history_data.partials.penerimaan.historypenerimaan-table-body', compact('penerimaan'));
    }

    //realtime untuk form history_data/hsitorypenerimaan
    public function hs_info(Request $request)
    {
        $query = Penerimaan::with(['identitasRod', 'karyawan'])

            // Nomor ROD
            ->when($request->filled('nomor_rod'), function ($q) use ($request) {
                $q->whereHas('identitasRod', function ($sub) use ($request) {
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

            // jenis
            ->when($request->filled('jenis'), function ($q) use ($request) {
                $q->where('jenis', $request->jenis);
            })

            // Stasiun
            ->when($request->filled('stasiun'), function ($q) use ($request) {
                $q->where('stasiun', $request->stasiun);
            })

            // Shift
            ->when($request->filled('shift'), function ($q) use ($request) {
                $q->where('shift', $request->shift);
            });

        if ($request->filled('tanggalMulai') && $request->filled('tanggalAkhir')) {

            $mulai = Carbon::parse($request->tanggalMulai)->startOfDay();
            $akhir = Carbon::parse($request->tanggalAkhir)->endOfDay();

            $query->whereBetween('tanggal_penerimaan', [$mulai, $akhir]);
        } elseif ($request->filled('tanggalMulai')) {

            $query->whereBetween('tanggal_penerimaan', [
                Carbon::parse($request->tanggalMulai)->startOfDay(),
                Carbon::parse($request->tanggalMulai)->endOfDay()
            ]);
        } elseif ($request->filled('tanggalAkhir')) {

            $query->whereBetween('tanggal_penerimaan', [
                Carbon::parse($request->tanggalAkhir)->startOfDay(),
                Carbon::parse($request->tanggalAkhir)->endOfDay()
            ]);
        }
        $penerimaan = $query
            ->orderBy('tanggal_penerimaan', 'desc')
            ->paginate(30)
            ->withQueryString();

        return view('history_data.partials.penerimaan.historypenerimaan-info', compact('penerimaan'));
    }

    //tampil dan cari untuk form history_data/historypenerimaan
    public function indexHistory(Request $request)
    {
        $query = Penerimaan::with(['identitasRod', 'karyawan'])

            // Nomor ROD
            ->when($request->filled('nomor_rod'), function ($q) use ($request) {
                $q->whereHas('identitasRod', function ($sub) use ($request) {
                    $sub->where('nomor_rod', 'like', '%' . $request->nomor_rod . '%');
                });
            })

            // Penginput
            ->when($request->filled('penginput'), function ($q) use ($request) {
                $q->whereHas('karyawan', function ($sub) use ($request) {
                    $sub->where('nama_lengkap', 'like', '%' . $request->penginput . '%');
                });
            })

            // jenis
            ->when($request->filled('jenis'), function ($q) use ($request) {
                $q->where('jenis', $request->jenis);
            })

            // tim
            ->when($request->filled('tim'), function ($q) use ($request) {
                $q->where('tim', $request->tim);
            })

            // Stasiun
            ->when($request->filled('stasiun'), function ($q) use ($request) {
                $q->where('stasiun', $request->stasiun);
            })

            // Shift
            ->when($request->filled('shift'), function ($q) use ($request) {
                $q->where('shift', $request->shift);
            });

        if ($request->filled('tanggalMulai') && $request->filled('tanggalAkhir')) {

            $mulai = Carbon::parse($request->tanggalMulai)->startOfDay();
            $akhir = Carbon::parse($request->tanggalAkhir)->endOfDay();

            $query->whereBetween('tanggal_penerimaan', [$mulai, $akhir]);
        } elseif ($request->filled('tanggalMulai')) {

            $query->whereBetween('tanggal_penerimaan', [
                Carbon::parse($request->tanggalMulai)->startOfDay(),
                Carbon::parse($request->tanggalMulai)->endOfDay()
            ]);
        } elseif ($request->filled('tanggalAkhir')) {

            $query->whereBetween('tanggal_penerimaan', [
                Carbon::parse($request->tanggalAkhir)->startOfDay(),
                Carbon::parse($request->tanggalAkhir)->endOfDay()
            ]);
        }
        $penerimaan = $query
            ->orderBy('tanggal_penerimaan', 'desc')
            ->paginate(30)
            ->withQueryString();

        return view('history_data.historypenerimaan', compact('penerimaan'));
    }

    //tampil untuk form history_data/historypenerimaan/identitasawal
    public function identitasAwal($id)
    {
        $awal = HistoryPenerimaan::where('penerimaan_id', $id)
            ->orderBy('created_at', 'asc')
            ->first();

        if ($awal) {
            $awal->load([
                'penerimaan.identitasRod',
                'karyawan'
            ]);

            return response()->json([$awal]);
        }

        $data = Penerimaan::with([
            'identitasRod',
            'karyawan'
        ])->find($id);

        if (!$data) {
            return response()->json([]);
        }

        $data->nomor_rod = $data->identitasRod->nomor_rod ?? null;

        return response()->json([$data]);
    }

    //tampil untuk form history_data/historypenerimaan/riawayatperubahan
    public function riwayatPerubahan($penerimaanId)
    {
        $awal = HistoryPenerimaan::where('penerimaan_id', $penerimaanId)
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$awal) {
            return response()->json([]);
        }

        $data = HistoryPenerimaan::with([
            'penerimaan.identitasRod',
            'karyawan'
        ])
            ->where('penerimaan_id', $penerimaanId)
            ->where('id', '!=', $awal->id)
            ->get();

        return response()->json($data);
    }

    ////tampil untuk form history_data/historypenerimaan/datasekarang
    public function dataSekarang($id)
    {
        $data = Penerimaan::with([
            'identitasRod',
            'karyawan'
        ])->find($id);

        if (!$data) {
            return response()->json(null);
        }

        return response()->json($data);
    }

    //Simpan Penerimaan
    public function store(Request $request)
    {
        if (!$request->filled('nomor_rod')) {
            return back()->with('warning', 'Nomor ROD wajib diisi')->withInput();
        }

        if (!$request->filled('jenis')) {
            return back()->with('warning', 'Jenis wajib diisi')->withInput();
        }

        if (!$request->filled('stasiun')) {
            return back()->with('warning', 'Stasiun wajib diisi')->withInput();
        }

        // Uppercast
        $request->merge([
            'nomor_rod' => strtoupper($request->nomor_rod),
            'jenis'     => strtoupper($request->jenis ?? ''),
            'stasiun'   => strtoupper($request->stasiun ?? ''),
            'catatan'   => strtoupper($request->catatan ?? ''),
        ]);

        $forceInsert = $request->boolean('force_insert');

        //ALIDASI 1
        $cek = IdentitasRod::where('nomor_rod', $request->nomor_rod)
            ->whereIn('status', ['Diterima', 'Diperbaiki'])
            ->first();

        if ($cek) {
            return back()->with(
                'warning',
                $cek->status === 'Diterima'
                    ? 'Nomor ROD ini sudah ada di data Penerimaan dan belum diperbaiki.'
                    : 'Nomor ROD ini sudah ada di data Perbaikan dan belum dikirim.'
            )->withInput();
        }

        //VALIDASI 2
        $rodExist = IdentitasRod::where('nomor_rod', $request->nomor_rod)->first();

        if ($rodExist) {
            $cekTanggal = Penerimaan::where('rod_id', $rodExist->id)
                ->whereDate('tanggal_penerimaan', Carbon::parse($request->master_datetime))
                ->exists();

            if ($cekTanggal) {
                return back()->with(
                    'warning',
                    "Nomor ROD {$request->nomor_rod} sudah pernah diterima pada tanggal yang sama."
                )->withInput();
            }
        }

        //VALIDASI 3 — PERPUTARAN ROD (± HARI)
        if (! $forceInsert) {

            $hariRentang = 24;

            $setting = DB::table('perputaran_rod')->first();
            if ($setting && is_numeric($setting->hari)) {
                $hariRentang = (int) $setting->hari;
            }

            $tanggalInput = Carbon::parse($request->master_datetime);

            $bentrok = Penerimaan::whereHas('identitasRod', function ($q) use ($request) {
                $q->where('nomor_rod', $request->nomor_rod);
            })
                ->whereBetween('tanggal_penerimaan', [
                    $tanggalInput->copy()->subDays($hariRentang),
                    $tanggalInput->copy()->addDays($hariRentang),
                ])
                ->orderBy('tanggal_penerimaan', 'desc')
                ->first();

            if ($bentrok) {

                $tglBentrok = Carbon::parse($bentrok->tanggal_penerimaan)
                    ->format('d-m-Y H:i');

                return back()
                    ->with(
                        'warning_confirm',
                        "Nomor ROD {$request->nomor_rod} terakhir diterima pada tanggal 
            {$tglBentrok}. 
            Jarak penerimaan masih berada dalam rentang perputaran 
            (±{$hariRentang} hari). 
            Apakah Anda yakin ingin melanjutkan?"
                    )
                    ->withInput();
            }
        }

        DB::transaction(function () use ($request) {

            $rod = IdentitasRod::create([
                'nomor_rod' => $request->nomor_rod,
                'status'    => 'Diterima',
            ]);

            $total =
                ($request->e1 ?? 0) +
                ($request->e2 ?? 0) +
                ($request->e3 ?? 0) +
                (($request->s ?? 0) > 0 ? 1 : 0) +
                ($request->d ?? 0) +
                ($request->b ?? 0) +
                ($request->ba ?? 0) +
                ($request->r ?? 0) +
                ($request->m ?? 0) +
                (($request->cr ?? 0) > 0 ? 1 : 0) +
                (($request->c ?? 0) > 0 ? 1 : 0) +
                ($request->rl ?? 0);

            Penerimaan::create([
                'rod_id' => $rod->id,
                'tanggal_penerimaan' => $request->master_datetime,
                'shift' => $request->master_shift,
                'jenis' => $request->jenis,
                'stasiun' => $request->stasiun,

                'e1' => $request->e1 ?? 0,
                'e2' => $request->e2 ?? 0,
                'e3' => $request->e3 ?? 0,
                'd'  => $request->d ?? 0,
                'm'  => $request->m ?? 0,
                'b'  => $request->b ?? 0,
                'ba' => $request->ba ?? 0,
                'cr' => $request->cr ?? 0,
                'c'  => $request->c ?? 0,
                's'  => $request->s ?? 0,
                'r'  => $request->r ?? 0,
                'rl' => $request->rl ?? 0,

                'jumlah' => $total,
                'catatan' => $request->catatan,
                'id_karyawan' => Session::get('karyawan_id'),
                'tim' => Session::get('tim'),
            ]);
        });

        return back()->with('success', 'Data penerimaan berhasil disimpan');
    }

    //update penerimaan
    public function update(Request $request)
    {
        if (!$request->filled('nomor_rod')) {
            return back()->with('warning', 'Nomor ROD wajib diisi')->withInput();
        }

        if (!$request->filled('jenis')) {
            return back()->with('warning', 'Jenis wajib diisi')->withInput();
        }

        if (!$request->filled('stasiun')) {
            return back()->with('warning', 'Stasiun wajib diisi')->withInput();
        }

        // AMBIL DATA AWAL
        $penerimaan = Penerimaan::with('identitasRod')
            ->findOrFail($request->ep_penerimaan_id);

        $nomorRodLama = optional($penerimaan->identitasRod)->nomor_rod;
        $nomorRodBaru = strtoupper($request->nomor_rod);

        // apakah benar-benar ganti nomor ROD?
        $gantiRod = $nomorRodBaru && $nomorRodBaru !== $nomorRodLama;

        if ($gantiRod) {

            // VALIDASI 1
            $cek = IdentitasRod::where('nomor_rod', $nomorRodBaru)
                ->whereIn('status', ['Diterima', 'Diperbaiki'])
                ->where('id', '!=', optional($penerimaan->identitasRod)->id)
                ->first();

            if ($cek) {
                return back()->with(
                    'warning',
                    $cek->status === 'Diterima'
                        ? 'Nomor ROD ini sudah ada di data Penerimaan dan belum diperbaiki.'
                        : 'Nomor ROD ini sudah ada di data Perbaikan dan belum dikirim.'
                );
            }

            // VALIDASI 2
            $cekTanggal = Penerimaan::whereHas('identitasRod', function ($q) use ($nomorRodBaru) {
                $q->where('nomor_rod', $nomorRodBaru);
            })
                ->whereDate(
                    'tanggal_penerimaan',
                    $penerimaan->tanggal_penerimaan
                )
                ->where('id', '!=', $penerimaan->id)
                ->exists();

            if ($cekTanggal) {
                return back()->with(
                    'warning',
                    "Nomor ROD {$nomorRodBaru} sudah pernah diterima pada tanggal yang sama."
                );
            }

            // VALIDASI 3
            $hariRentang = DB::table('perputaran_rod')->value('hari') ?? 24;
            $tanggalInput = Carbon::parse($penerimaan->tanggal_penerimaan);

            $bentrok = Penerimaan::whereHas('identitasRod', function ($q) use ($nomorRodBaru) {
                $q->where('nomor_rod', $nomorRodBaru);
            })
                ->where('id', '!=', $penerimaan->id)
                ->whereBetween('tanggal_penerimaan', [
                    $tanggalInput->copy()->subDays($hariRentang),
                    $tanggalInput->copy()->addDays($hariRentang),
                ])
                ->orderByDesc('tanggal_penerimaan')
                ->first();

            if ($bentrok && ! $request->boolean('force_update')) {
                return back()
                    ->with(
                        'warning_confirm',
                        "Nomor ROD {$nomorRodBaru} terakhir diterima pada tanggal 
                        {$bentrok->tanggal_penerimaan}. 
                        Jarak penerimaan masih berada dalam rentang perputaran 
                        (±{$hariRentang} hari). 
                        Apakah Anda yakin ingin melanjutkan?"
                    )
                    ->withInput();
            }
        }

        DB::transaction(function () use ($request, $penerimaan, $nomorRodLama, $nomorRodBaru, $gantiRod) {

            // SIMPAN HISTORY (DATA LAMA)
            HistoryPenerimaan::create([
                'penerimaan_id'      => $penerimaan->id,
                'nomor_rod'          => $nomorRodLama,
                'tanggal_penerimaan' => $penerimaan->tanggal_penerimaan,
                'shift'              => $penerimaan->shift,
                'jenis'              => $penerimaan->jenis,
                'stasiun'            => $penerimaan->stasiun,

                'e1' => $penerimaan->e1,
                'e2' => $penerimaan->e2,
                'e3' => $penerimaan->e3,
                's'  => $penerimaan->s,
                'd'  => $penerimaan->d,
                'b'  => $penerimaan->b,
                'ba' => $penerimaan->ba,
                'r'  => $penerimaan->r,
                'm'  => $penerimaan->m,
                'cr' => $penerimaan->cr,
                'c'  => $penerimaan->c,
                'rl' => $penerimaan->rl,

                'jumlah'      => $penerimaan->jumlah,
                'catatan'     => $penerimaan->catatan,
                'id_karyawan' => Session::get('karyawan_id'),
                'tim'         => Session::get('tim'),
            ]);

            // UPDATE MASTER ROD (jika ganti)
            if ($gantiRod && $penerimaan->identitasRod) {
                $penerimaan->identitasRod->update([
                    'nomor_rod' => $nomorRodBaru,
                ]);

                $penerimaan->touch();
                $penerimaan->perbaikan->touch();
            }

            $total =
                ($request->e1 ?? 0) +
                ($request->e2 ?? 0) +
                ($request->e3 ?? 0) +
                (($request->s  ?? 0) > 0 ? 1 : 0) +
                ($request->d  ?? 0) +
                ($request->b  ?? 0) +
                ($request->ba ?? 0) +
                ($request->r  ?? 0) +
                ($request->m  ?? 0) +
                (($request->cr ?? 0) > 0 ? 1 : 0) +
                (($request->c  ?? 0) > 0 ? 1 : 0) +
                ($request->rl ?? 0);

            // UPDATE PENERIMAAN
            $penerimaan->update([
                'jenis'   => strtoupper($request->jenis),
                'stasiun' => strtoupper($request->stasiun),

                'e1' => $request->e1 ?? 0,
                'e2' => $request->e2 ?? 0,
                'e3' => $request->e3 ?? 0,
                's'  => $request->s  ?? 0,
                'd'  => $request->d  ?? 0,
                'b'  => $request->b  ?? 0,
                'ba' => $request->ba ?? 0,
                'r'  => $request->r  ?? 0,
                'm'  => $request->m  ?? 0,
                'cr' => $request->cr ?? 0,
                'c'  => $request->c  ?? 0,
                'rl' => $request->rl ?? 0,

                'jumlah'  => $total,
                'catatan' => strtoupper($request->catatan),
            ]);
        });

        return back()->with('success', 'Data Penerimaan berhasil diupdate + history tersimpan');
    }

    //hapus penerimaan
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $rod = IdentitasRod::with([
                'penerimaan.perbaikan.pengiriman'
            ])->findOrFail($id);

            // hapus semua relasi (kalau belum cascade DB)
            optional($rod->penerimaan)->each(function ($p) {
                optional($p->perbaikan)->each(function ($pb) {
                    $pb->pengiriman()->delete();
                    $pb->delete();
                });
                $p->delete();
            });

            // hapus master
            $rod->delete();
        });

        // 🔔 SATU TERIAKAN GLOBAL
        broadcast(new DataChanged('penerimaan'))->toOthers();
        broadcast(new DataChanged('perbaikan'))->toOthers();
        broadcast(new DataChanged('pengiriman'))->toOthers();

        return back()->with('success', 'Nomor ROD berhasil dihapus.');
    }

    //Update perputaran_rod
    public function updatePerputaran(Request $request)
    {
        $request->validate([
            'hari' => 'required|integer|min:1'
        ], [
            'hari.required' => 'Jumlah hari perputaran wajib diisi',
            'hari.integer'  => 'Jumlah hari harus berupa angka',
            'hari.min'      => 'Jumlah hari minimal 1',
        ]);

        DB::transaction(function () use ($request) {

            DB::table('perputaran_rod')
                ->where('id', 1)
                ->update([
                    'hari'        => $request->hari,
                    'id_karyawan' => Session::get('karyawan_id'),
                    'updated_at'  => now(),
                ]);
        });

        return back()->with('success', 'Perputaran ROD berhasil diperbarui');
    }

    //Export penerimaan
    public function export(Request $request)
    {
        return Excel::download(
            new HistoryPenerimaanExport($request->all()),
            'Data-Penerimaan-' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }
}
