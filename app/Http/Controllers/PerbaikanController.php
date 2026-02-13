<?php

namespace App\Http\Controllers;

use App\Models\HistoryPerbaikan;
use App\Models\Penerimaan;
use Illuminate\Support\Facades\DB;
use App\Models\Perbaikan;
use App\Models\IdentitasRod;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Exports\HistoryPerbaikanExport;
use Maatwebsite\Excel\Facades\Excel;

class PerbaikanController extends Controller
{
    //realtime untuk form entry_data/perbaikan
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

        return view('entry_data.partials.perbaikan.penerimaan-table-body', compact('penerimaan'));
    }

    //realtime untuk form entry_data/perbaikan
    public function info(Request $request)
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

        return view('entry_data.partials.perbaikan.penerimaan-info', compact('penerimaan'));
    }

    //tampil dan cari untuk form entry_data/perbaikan
    public function index(Request $request)
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

        return view('entry_data.perbaikan', compact('penerimaan'));
    }

    //realtime untuk form edit_data/editperbaikan
    public function eper_table(Request $request)
    {
        $query = Perbaikan::with(['penerimaan.identitasRod', 'karyawan'])

            ->when($request->filled('nomor_rod'), function ($q) use ($request) {
                $q->whereHas('penerimaan.identitasRod', function ($sub) use ($request) {
                    $sub->where('nomor_rod', 'like', '%' . $request->nomor_rod . '%');
                });
            })

            ->when($request->filled('tanggal'), function ($q) use ($request) {
                $q->whereDate('tanggal_perbaikan', $request->tanggal);
            })

            ->orderBy('tanggal_perbaikan', 'desc');

        $perbaikan = $query->paginate(30)->withQueryString();

        return view('edit_data.partials.perbaikan.editperbaikan-table-body', compact('perbaikan'));
    }

    //realtime untuk form edit_data/editperbaikan
    public function eper_info(Request $request)
    {
        $query = Perbaikan::with(['penerimaan.identitasRod', 'karyawan'])

            ->when($request->filled('nomor_rod'), function ($q) use ($request) {
                $q->whereHas('penerimaan.identitasRod', function ($sub) use ($request) {
                    $sub->where('nomor_rod', 'like', '%' . $request->nomor_rod . '%');
                });
            })

            ->when($request->filled('tanggal'), function ($q) use ($request) {
                $q->whereDate('tanggal_perbaikan', $request->tanggal);
            })

            ->orderBy('tanggal_perbaikan', 'desc');

        $perbaikan = $query->paginate(30)->withQueryString();

        return view('edit_data.partials.perbaikan.editperbaikan-info', compact('perbaikan'));
    }

    //tampil dan cari untuk form edit_data/editperbaikan
    public function indexEdit(Request $request)
    {
        $perbaikan = Perbaikan::with(['penerimaan.identitasRod', 'karyawan'])
            ->when($request->filled('cariperbaikan'), function ($query) use ($request) {
                $query->whereHas('penerimaan.identitasRod', function ($q) use ($request) {
                    $q->where('nomor_rod', 'like', '%' . $request->cariperbaikan . '%');
                });
            })
            ->orderBy('tanggal_perbaikan', 'desc')
            ->paginate(30)
            ->withQueryString();


        return view('edit_data.editperbaikan', compact('perbaikan'));
    }

    //realtime untuk history_data/hsitoryperbaikan
    public function hs_table(Request $request)
    {
        $query = Perbaikan::with(['penerimaan.identitasRod', 'karyawan'])

            // Nomor ROD
            ->when($request->filled('nomor_rod'), function ($q) use ($request) {
                $q->whereHas('penerimaan.identitasRod', function ($sub) use ($request) {
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

            // Shift
            ->when($request->filled('shift'), function ($q) use ($request) {
                $q->where('shift', $request->shift);
            });

        if ($request->filled('tanggalMulai') && $request->filled('tanggalAkhir')) {

            $mulai = Carbon::parse($request->tanggalMulai)->startOfDay();
            $akhir = Carbon::parse($request->tanggalAkhir)->endOfDay();

            $query->whereBetween('tanggal_perbaikan', [$mulai, $akhir]);
        } elseif ($request->filled('tanggalMulai')) {

            $query->whereBetween('tanggal_perbaikan', [
                Carbon::parse($request->tanggalMulai)->startOfDay(),
                Carbon::parse($request->tanggalMulai)->endOfDay()
            ]);
        } elseif ($request->filled('tanggalAkhir')) {

            $query->whereBetween('tanggal_perbaikan', [
                Carbon::parse($request->tanggalAkhir)->startOfDay(),
                Carbon::parse($request->tanggalAkhir)->endOfDay()
            ]);
        }
        $perbaikan = $query
            ->orderBy('tanggal_perbaikan', 'desc')
            ->paginate(30)
            ->withQueryString();

        return view('history_data.partials.perbaikan.historyperbaikan-table-body', compact('perbaikan'));
    }

    //realtime untuk form history_data/hsitoryperbaikan
    public function hs_info(Request $request)
    {
        $query = Perbaikan::with(['penerimaan.identitasRod', 'karyawan'])

            // Nomor ROD
            ->when($request->filled('nomor_rod'), function ($q) use ($request) {
                $q->whereHas('penerimaan.identitasRod', function ($sub) use ($request) {
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

            // Shift
            ->when($request->filled('shift'), function ($q) use ($request) {
                $q->where('shift', $request->shift);
            });

        if ($request->filled('tanggalMulai') && $request->filled('tanggalAkhir')) {

            $mulai = Carbon::parse($request->tanggalMulai)->startOfDay();
            $akhir = Carbon::parse($request->tanggalAkhir)->endOfDay();

            $query->whereBetween('tanggal_perbaikan', [$mulai, $akhir]);
        } elseif ($request->filled('tanggalMulai')) {

            $query->whereBetween('tanggal_perbaikan', [
                Carbon::parse($request->tanggalMulai)->startOfDay(),
                Carbon::parse($request->tanggalMulai)->endOfDay()
            ]);
        } elseif ($request->filled('tanggalAkhir')) {

            $query->whereBetween('tanggal_perbaikan', [
                Carbon::parse($request->tanggalAkhir)->startOfDay(),
                Carbon::parse($request->tanggalAkhir)->endOfDay()
            ]);
        }
        $perbaikan = $query
            ->orderBy('tanggal_perbaikan', 'desc')
            ->paginate(30)
            ->withQueryString();

        return view('history_data.partials.perbaikan.historyperbaikan-info', compact('perbaikan'));
    }

    //tampil dan cari untuk form history_data/historyperbaikan
    public function indexHistory(Request $request)
    {
        $query = Perbaikan::with(['penerimaan.identitasRod', 'karyawan'])

            // Nomor ROD
            ->when($request->filled('nomor_rod'), function ($q) use ($request) {
                $q->whereHas('penerimaan.identitasRod', function ($sub) use ($request) {
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

            // Shift
            ->when($request->filled('shift'), function ($q) use ($request) {
                $q->where('shift', $request->shift);
            });

        if ($request->filled('tanggalMulai') && $request->filled('tanggalAkhir')) {

            $mulai = Carbon::parse($request->tanggalMulai)->startOfDay();
            $akhir = Carbon::parse($request->tanggalAkhir)->endOfDay();

            $query->whereBetween('tanggal_perbaikan', [$mulai, $akhir]);
        } elseif ($request->filled('tanggalMulai')) {

            $query->whereBetween('tanggal_perbaikan', [
                Carbon::parse($request->tanggalMulai)->startOfDay(),
                Carbon::parse($request->tanggalMulai)->endOfDay()
            ]);
        } elseif ($request->filled('tanggalAkhir')) {

            $query->whereBetween('tanggal_perbaikan', [
                Carbon::parse($request->tanggalAkhir)->startOfDay(),
                Carbon::parse($request->tanggalAkhir)->endOfDay()
            ]);
        }
        $perbaikan = $query
            ->orderBy('tanggal_perbaikan', 'desc')
            ->paginate(30)
            ->withQueryString();

        return view('history_data.historyperbaikan', compact('perbaikan'));
    }

    //tampil untuk form history_data/historyperbaikan/identitasawal
    public function identitasAwal($id)
    {
        $awal = HistoryPerbaikan::where('perbaikan_id', $id)
            ->orderBy('created_at', 'asc')
            ->first();

        if ($awal) {
            $awal->load([
                'perbaikan.penerimaan.identitasRod',
                'karyawan'
            ]);
            return response()->json([$awal]);
        }

        $data = Perbaikan::with([
            'penerimaan.identitasRod',
            'karyawan'
        ])->find($id);

        if (!$data) {
            return response()->json([]);
        }

        $data->nomor_rod = $data->penerimaan->identitasRod->nomor_rod ?? null;

        return response()->json([$data]);
    }

    //tampil untuk form history_data/historyperbaikan/riawayatperubahan
    public function riwayatPerubahan($perbaikanId)
    {
        $awal = HistoryPerbaikan::where('perbaikan_id', $perbaikanId)
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$awal) {
            return response()->json([]);
        }

        $data = HistoryPerbaikan::with([
            'perbaikan.penerimaan.identitasRod',
            'karyawan'
        ])
            ->where('perbaikan_id', $perbaikanId)
            ->where('id', '!=', $awal->id)
            ->get();

        return response()->json($data);
    }

    //tampil untuk form history_data/historyperbaikan/datasekarang
    public function dataSekarang($id)
    {
        $data = Perbaikan::with([
            'penerimaan.identitasRod',
            'karyawan'
        ])->find($id);

        if (!$data) {
            return response()->json(null);
        }

        return response()->json($data);
    }

    //simpan perbaikan
    public function store(Request $request)
    {

        if (!$request->filled('jenis')) {
            return back()->with('warning', 'Jenis wajib diisi')->withInput();
        }

        DB::transaction(function () use ($request) {

            $request['nomor_rod'] = strtoupper($request['nomor_rod']);
            $request['jenis'] = strtoupper($request['jenis']);
            $request['stasiun'] = strtoupper($request['stasiun']);
            $request['catatan'] = strtoupper($request['catatan']);

            $penerimaan = Penerimaan::findOrFail($request->penerimaan_id);

            $ba = ($request->bac ?? 0) + ($request->nba ?? 0);

            $e1 = ($request->e1_ers ?? 0) + ($request->e1_est ?? 0);

            $e2 = ($request->e2_ers ?? 0) + ($request->e2_cst ?? 0) + ($request->e2_cstub ?? 0);

            $total_perbaikan =
                $e1 +
                $e2 +
                ($request->e3 ?? 0) +
                ($request->e4 ?? 0) +
                (($request->s  ?? 0) > 0 ? 1 : 0) +
                ($request->d  ?? 0) +
                ($request->b  ?? 0) +
                $ba +
                ($request->ba1 ?? 0) +
                ($request->r  ?? 0) +
                ($request->m  ?? 0) +
                (($request->cr ?? 0) > 0 ? 1 : 0) +
                (($request->c  ?? 0) > 0 ? 1 : 0) +
                ($request->rl ?? 0);

            $perbaikan = Perbaikan::create([
                'penerimaan_id' => $penerimaan->id,
                'tanggal_perbaikan' => $request->master_datetime,
                'shift' => $request->master_shift,
                'jenis' => $request->jenis,

                'e1_ers' => $request->e1_ers ?? 0,
                'e1_est' => $request->e1_est ?? 0,
                'e1_jumlah' => $e1,
                'e2_ers' => $request->e2_ers ?? 0,
                'e2_cst' => $request->e2_cst ?? 0,
                'e2_cstub' => $request->e2_cstub ?? 0,
                'e2_jumlah' => $e2,

                'e3' => $request->e3 ?? 0,
                'e4'  => $request->e4  ?? 0,

                's'  => $request->s ?? 0,
                'd'  => $request->d ?? 0,
                'b'  => $request->b ?? 0,

                'bac'  => $request->bac ?? 0,
                'nba'  => $request->nba ?? 0,
                'ba'  => $ba,

                'ba1' => $request->ba1 ?? 0,
                'r' => $request->r ?? 0,
                'm'  => $request->m ?? 0,
                'cr'  => $request->cr  ?? 0,
                'c'  => $request->c ?? 0,
                'rl' => $request->rl ?? 0,

                'jumlah' => $total_perbaikan,
                'catatan' => $request->catatan,
                'tanggal_penerimaan' => $request->tanggal_penerimaan,
                'id_karyawan' => Session::get('karyawan_id'),
                'tim' => Session::get('tim'),
            ]);

            if ($request->hasFile('fotobuktiperubahan')) {

                $path = $request->file('fotobuktiperubahan')
                    ->store("perbaikan/{$perbaikan->id}", 'public');

                $perbaikan->update([
                    'fotobuktiperubahan' => $path
                ]);
            }

            $penerimaan->identitasRod->update([
                'status' => 'Diperbaiki'
            ]);

            $penerimaan->touch();

            $total_penerimaan =
                $e1 +
                $e2 +
                ($request->e3 ?? 0) +
                (($request->s  ?? 0) > 0 ? 1 : 0) +
                ($request->d  ?? 0) +
                ($request->b  ?? 0) +
                $ba +
                ($request->r  ?? 0) +
                ($request->m  ?? 0) +
                (($request->cr ?? 0) > 0 ? 1 : 0) +
                (($request->c  ?? 0) > 0 ? 1 : 0) +
                ($request->rl ?? 0);


            $tanggal = Carbon::parse($request->master_datetime)->format('Y-m-d');

            $affected = DB::table('penerimaan')
                ->where('id', $penerimaan->id)
                ->whereDate('tanggal_penerimaan', $tanggal)
                ->where('shift', $request->master_shift)
                ->update([
                    'jenis' => $request->jenis,
                    'e1'     => $e1,
                    'e2'     => $e2,
                    'e3'     => $request->e3 ?? 0,
                    's'      => $request->s ?? 0,
                    'd'      => $request->d ?? 0,
                    'b'      => $request->b ?? 0,
                    'ba'     => $ba,
                    'cr'     => $request->cr ?? 0,
                    'm'      => $request->m ?? 0,
                    'r'      => $request->r ?? 0,
                    'c'      => $request->c ?? 0,
                    'rl'     => $request->rl ?? 0,
                    'jumlah' => $total_penerimaan,
                    'catatan' => $request->catatan,
                    'updated_at' => now(),
                ]);
        });

        return back()->with('success', 'Data Perbaikan berhasil disimpan');
    }

    //update perbaikan
    public function update(Request $request)
    {
        if (!$request->filled('nomor_rod')) {
            return back()->with('warning', 'Nomor ROD wajib diisi')->withInput();
        }

        if (!$request->filled('jenis')) {
            return back()->with('warning', 'Jenis wajib diisi')->withInput();
        }

        $perbaikan = Perbaikan::with('penerimaan.identitasRod')
            ->findOrFail($request->eper_perbaikan_id);

        $penerimaan = $perbaikan->penerimaan;

        // Ubah nomor ROD jadi uppercase
        $nomorRodBaru = strtoupper($request->nomor_rod);

        // Ambil ROD yang lama
        $nomorRodLama = $perbaikan->penerimaan->identitasRod->nomor_rod;

        // Cek apakah ganti ROD
        $gantiRod = $nomorRodBaru && $nomorRodBaru !== $nomorRodLama;

        if ($gantiRod) {

            // VALIDASI 1: Nomor ROD sudah ada di Penerimaan/Perbaikan
            $cek = IdentitasRod::where('nomor_rod', $nomorRodBaru)
                ->whereIn('status', ['Diterima', 'Diperbaiki'])
                ->where('id', '!=', optional($perbaikan->identitasRod)->id)
                ->first();

            if ($cek) {
                return back()->with(
                    'warning',
                    $cek->status === 'Diterima'
                        ? 'Nomor ROD ini sudah ada di data Penerimaan dan belum diperbaiki.'
                        : 'Nomor ROD ini sudah ada di data Perbaikan dan belum dikirim.'
                );
            }

            // VALIDASI 2: Nomor ROD sudah diterima pada tanggal yang sama
            $rod = IdentitasRod::where('nomor_rod', $nomorRodBaru)->first();

            if ($rod) {
                $cekTanggal = Penerimaan::whereHas('identitasRod', function ($q) use ($nomorRodBaru) {
                    $q->where('nomor_rod', $nomorRodBaru);
                })
                    ->whereDate('tanggal_penerimaan', $perbaikan->tanggal_penerimaan)
                    ->where('id', '!=', $perbaikan->penerimaan_id)
                    ->exists();


                if ($cekTanggal) {
                    return back()->with(
                        'warning',
                        "Nomor ROD {$nomorRodBaru} sudah pernah Diterima pada tanggal yang sama."
                    );
                }
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

        DB::transaction(function () use ($request, $perbaikan, $nomorRodLama, $nomorRodBaru, $gantiRod) {

            $fotoHistory = null;

            // 🔥 ARSIP FOTO LAMA (JIKA ADA)
            if ($perbaikan->fotobuktiperubahan) {

                $ext = pathinfo($perbaikan->fotobuktiperubahan, PATHINFO_EXTENSION);

                $fotoHistory = "historyperbaikan/{$perbaikan->id}/" . Str::uuid() . '.' . $ext;

                Storage::disk('public')->copy(
                    $perbaikan->fotobuktiperubahan,
                    $fotoHistory
                );
            }

            // SIMPAN HISTORY (DATA LAMA)
            HistoryPerbaikan::create([
                'perbaikan_id'      => $perbaikan->id,
                'nomor_rod'          => $nomorRodLama,
                'tanggal_perbaikan' => $perbaikan->tanggal_perbaikan,
                'shift'              => $perbaikan->shift,
                'jenis'              => $perbaikan->jenis,

                'e1_ers' => $perbaikan->e1_ers,
                'e1_est' => $perbaikan->e1_est,
                'e1_jumlah' => $perbaikan->e1_jumlah,

                'e2_ers' => $perbaikan->e2_ers,
                'e2_cst' => $perbaikan->e2_cst,
                'e2_cstub' => $perbaikan->e2_cstub,
                'e2_jumlah' => $perbaikan->e2_jumlah,

                'e3' => $perbaikan->e3,
                'e4' => $perbaikan->e4,

                's'  => $perbaikan->s,
                'd'  => $perbaikan->d,
                'b'  => $perbaikan->b,

                'nba' => $perbaikan->nba,
                'bac' => $perbaikan->bac,
                'ba' => $perbaikan->ba,

                'ba1' => $perbaikan->ba1,
                'r'  => $perbaikan->r,
                'm'  => $perbaikan->m,
                'cr' => $perbaikan->cr,
                'c'  => $perbaikan->c,
                'rl' => $perbaikan->rl,

                'jumlah'      => $perbaikan->jumlah,
                'catatan'     => $perbaikan->catatan,
                'tanggal_penerimaan' => $perbaikan->tanggal_penerimaan,
                'fotobuktiperubahan' => $fotoHistory,
                'id_karyawan' => Session::get('karyawan_id'),
                'tim'         => Session::get('tim'),
            ]);

            // UPDATE MASTER ROD (jika ganti)
            if ($gantiRod && $perbaikan->penerimaan->identitasRod) {
                $perbaikan->penerimaan->identitasRod->update([
                    'nomor_rod' => $nomorRodBaru
                ]);

                $perbaikan->touch();
                $perbaikan->penerimaan->touch();
            }

            $ba = ($request->bac ?? 0) + ($request->nba ?? 0);

            $e1 = ($request->e1_ers ?? 0) + ($request->e1_est ?? 0);

            $e2 = ($request->e2_ers ?? 0) + ($request->e2_cst ?? 0) + ($request->e2_cstub ?? 0);

            $total_perbaikan =
                $e1 +
                $e2 +
                ($request->e3 ?? 0) +
                ($request->e4 ?? 0) +
                (($request->s  ?? 0) > 0 ? 1 : 0) +
                ($request->d  ?? 0) +
                ($request->b  ?? 0) +
                $ba +
                ($request->ba1 ?? 0) +
                ($request->r  ?? 0) +
                ($request->m  ?? 0) +
                (($request->cr ?? 0) > 0 ? 1 : 0) +
                (($request->c  ?? 0) > 0 ? 1 : 0) +
                ($request->rl ?? 0);


            $fotoLama = $perbaikan->fotobuktiperubahan;

            if ($request->hapus_foto == 1 && $fotoLama) {
                Storage::disk('public')->delete($fotoLama);
                $perbaikan->fotobuktiperubahan = null;
            }

            if ($request->hasFile('eper_fotobuktiperubahan')) {

                if ($fotoLama) {
                    Storage::disk('public')->delete($fotoLama);
                }

                $path = $request->file('eper_fotobuktiperubahan')
                    ->store("perbaikan/{$perbaikan->id}", 'public');

                $perbaikan->fotobuktiperubahan = $path;
            }

            $perbaikan->save();

            // UPDATE PERBAIKAN
            $perbaikan->update([

                'jenis'   => strtoupper($request->jenis),

                'e1_ers' => $request->e1_ers ?? 0,
                'e1_est' => $request->e1_est ?? 0,
                'e1_jumlah' => $e1,
                'e2_ers' => $request->e2_ers ?? 0,
                'e2_cst' => $request->e2_cst ?? 0,
                'e2_cstub' => $request->e2_cstub ?? 0,
                'e2_jumlah' => $e2,

                'e3' => $request->e3 ?? 0,
                'e4'  => $request->e4  ?? 0,

                's'  => $request->s ?? 0,
                'd'  => $request->d ?? 0,
                'b'  => $request->b ?? 0,

                'bac'  => $request->bac ?? 0,
                'nba'  => $request->nba ?? 0,
                'ba'  => $ba,

                'ba1' => $request->ba1 ?? 0,
                'r' => $request->r ?? 0,
                'm'  => $request->m ?? 0,
                'cr'  => $request->cr  ?? 0,
                'c'  => $request->c ?? 0,
                'rl' => $request->rl ?? 0,

                'jumlah' => $total_perbaikan,
                'catatan' => strtoupper($request->catatan),
            ]);
        });

        return back()->with('success', 'Data Perbaikan berhasil diupdate + history tersimpan');
    }

    //Export perbaikan
    public function export(Request $request)
    {
        return Excel::download(
            new HistoryPerbaikanExport($request->all()),
            'Data-Perbaikan-' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }
}
