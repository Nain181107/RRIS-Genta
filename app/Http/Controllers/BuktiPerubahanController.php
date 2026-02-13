<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use Illuminate\Http\Request;

class BuktiPerubahanController extends Controller
{
    //tampil dan cari untuk form edit_data/editbuktiperubahan
    public function indexEdit(Request $request)
    {
        $buktiperubahan = Perbaikan::with(['penerimaan.identitasRod', 'karyawan'])
            ->whereNull('fotobuktiperubahan')
            ->when($request->filled('cariperbaikan'), function ($query) use ($request) {
                $query->whereHas('penerimaan.identitasRod', function ($q) use ($request) {
                    $q->where('nomor_rod', 'like', '%' . $request->caribuktiperubahan . '%');
                });
            })
            ->orderBy('tanggal_perbaikan', 'desc')
            ->paginate(30)
            ->withQueryString();

        return view('edit_data.editbuktiperubahan', compact('buktiperubahan'));
    }
}
