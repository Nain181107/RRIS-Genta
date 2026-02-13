<?php

namespace App\Http\Controllers;

use App\Models\IdentitasRod;
use Illuminate\Http\Request;

class IdentitasRodController extends Controller
{
    public function destroy($id)
    {
        $rod = IdentitasRod::findOrFail($id);
        $rod->delete();

        return back()->with('success', 'Semua data penerimaan, perbaikan, dan pengiriman berhasil dihapus permanen.');
    }
}
