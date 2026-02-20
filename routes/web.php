<?php

use App\Exports\HistoryPenerimaanExport;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\IdentitasRodController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\PerbaikanController;

Route::get('/registrasi', function () {
    return view('registrasi');
})
    ->name('registrasi');
Route::post('/karyawan/store', [KaryawanController::class, 'store'])
    ->name('karyawan.store');

Route::get('/master', function () {
    return view('master.master');
});

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth.karyawan'])->group(function () {

    Route::post('/master-time/set', [DashboardController::class, 'setMasterTime'])
        ->name('master.time.set');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

Route::post('/api/rod-reject/filter', [DashboardController::class, 'filter']);

Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index')->middleware('auth.karyawan');

Route::post('/laporan/preview-pdf', [LaporanController::class, 'previewPDF'])->name('laporan.preview')->middleware('auth.karyawan');

//penerimaan
Route::get('/penerimaan', [PenerimaanController::class, 'index'])->name('penerimaan.index')
    ->middleware('auth.karyawan');

Route::get('/penerimaan/table', [PenerimaanController::class, 'table'])->name('penerimaan.table')
    ->middleware('auth.karyawan');

Route::get('/penerimaan/info', [PenerimaanController::class, 'info'])->name('penerimaan.info')
    ->middleware('auth.karyawan');

Route::post('/penerimaan', [PenerimaanController::class, 'store'])->name('penerimaan.store')
    ->middleware('auth.karyawan');

Route::post('/perputaran-rod/update', [PenerimaanController::class, 'updatePerputaran'])->name('perputaran-rod.update')
    ->middleware('auth.karyawan');

//perbaikan
Route::get('/perbaikan', [PerbaikanController::class, 'index'])->name('perbaikan.index')
    ->middleware('auth.karyawan');

Route::get('/perbaikan/table', [PerbaikanController::class, 'table'])->name('perbaikan.table')
    ->middleware('auth.karyawan');

Route::get('/perbaikan/info', [PerbaikanController::class, 'info'])->name('perbaikan.info')
    ->middleware('auth.karyawan');

Route::post('/perbaikan', [PerbaikanController::class, 'store'])->name('perbaikan.store')
    ->middleware('auth.karyawan');

//pengiriman
Route::get('/pengiriman', [PengirimanController::class, 'index'])->name('pengiriman.index')
    ->middleware('auth.karyawan');

Route::get('/pengiriman/table', [PengirimanController::class, 'table'])->name('pengiriman.table')
    ->middleware('auth.karyawan');

Route::get('/pengiriman/info', [PengirimanController::class, 'info'])->name('pengiriman.info')
    ->middleware('auth.karyawan');

Route::get('/cek-rod/{nomor}', [PengirimanController::class, 'cekRod']);

Route::post('/pengiriman', [PengirimanController::class, 'store'])->name('pengiriman.store')
    ->middleware('auth.karyawan');

//butt man
Route::get('/buttman', function () {
    return view('entry_data.formbuttman');
});


//edit data
//edit penerimaan
Route::get('/editpenerimaan', [PenerimaanController::class, 'indexEdit'])->name('editpenerimaan.indexEdit')
    ->middleware('auth.karyawan');

Route::get('/editpenerimaan/table', [PenerimaanController::class, 'ep_table'])->name('editpenerimaan.table')
    ->middleware('auth.karyawan');

Route::get('/editpenerimaan/info', [PenerimaanController::class, 'ep_info'])->name('editpenerimaan.info')
    ->middleware('auth.karyawan');

Route::post('/penerimaan/edit/update', [PenerimaanController::class, 'update'])->name('editpenerimaan.updated')
    ->middleware('auth.karyawan');

//hapus rod
Route::delete('/identitas-rod/{id}', [IdentitasRodController::class, 'destroy'])->name('identitas-rod.destroy')
    ->middleware('auth.karyawan');

//edit perbaikan
Route::get('/editperbaikan', [PerbaikanController::class, 'indexEdit'])->name('editperbaikan.indexEdit')
    ->middleware('auth.karyawan');

Route::get('/editperbaikan/table', [PerbaikanController::class, 'eper_table'])->name('editperbaikan.table')
    ->middleware('auth.karyawan');

Route::get('/editperbaikan/info', [PerbaikanController::class, 'eper_info'])->name('editperbaikan.info')
    ->middleware('auth.karyawan');

Route::post('/perbaikan/edit/update', [PerbaikanController::class, 'update'])->name('editperbaikan.updated')
    ->middleware('auth.karyawan');


//history data
//history penerimaan
Route::get('/historypenerimaan', [PenerimaanController::class, 'indexHistory'])->name('historypenerimaan.indexHistory')
    ->middleware('auth.karyawan');

Route::get('/historypenerimaan/table', [PenerimaanController::class, 'hs_table'])->name('historypenerimaan.table')
    ->middleware('auth.karyawan');

Route::get('/historypenerimaan/info', [PenerimaanController::class, 'hs_info'])->name('historypenerimaan.info')
    ->middleware('auth.karyawan');

Route::get('/history/penerimaan/export', [PenerimaanController::class, 'export'])->name('history.penerimaan.export')
    ->middleware('auth.karyawan');

//history data/identitasawal
Route::get('/historypenerimaan/{id}/identitas-awal', [PenerimaanController::class, 'identitasAwal'])
    ->middleware('auth.karyawan');

//history data/riwayatperubahan
Route::get('/historypenerimaan/{id}/riwayat-perubahan', [PenerimaanController::class, 'riwayatPerubahan'])
    ->middleware('auth.karyawan');

//history data/datasekarang
Route::get('/penerimaan/{id}/data-sekarang', [PenerimaanController::class, 'dataSekarang'])
    ->middleware('auth.karyawan');


//history perbaikan
Route::get('/historyperbaikan', [PerbaikanController::class, 'indexHistory'])->name('historyperbaikan.indexHistory')
    ->middleware('auth.karyawan');

Route::get('/historyperbaikan/table', [PerbaikanController::class, 'hs_table'])->name('historyperbaikan.table')
    ->middleware('auth.karyawan');

Route::get('/historyperbaikan/info', [PerbaikanController::class, 'hs_info'])->name('historyperbaikan.info')
    ->middleware('auth.karyawan');

Route::get('/history/perbaikan/export', [PerbaikanController::class, 'export'])->name('history.perbaikan.export')
    ->middleware('auth.karyawan');

//history data/identitasawal
Route::get('/historyperbaikan/{id}/identitas-awal', [PerbaikanController::class, 'identitasAwal'])
    ->middleware('auth.karyawan');

//history data/riwayatperubahan
Route::get('/historyperbaikan/{id}/riwayat-perubahan', [PerbaikanController::class, 'riwayatPerubahan'])
    ->middleware('auth.karyawan');

//history data/datasekarang
Route::get('/perbaikan/{id}/data-sekarang', [PerbaikanController::class, 'dataSekarang'])
    ->middleware('auth.karyawan');


//history pengiriman
Route::get('/historypengiriman', [PengirimanController::class, 'indexHistory'])->name('historypengiriman.indexHistory')
    ->middleware('auth.karyawan');

Route::get('/historypengiriman/table', [PengirimanController::class, 'hs_table'])->name('historypengiriman.table')
    ->middleware('auth.karyawan');

Route::get('/historypengiriman/info', [PengirimanController::class, 'hs_info'])->name('historypengiriman.info')
    ->middleware('auth.karyawan');

Route::get('/history/pengiriman/export', [PengirimanController::class, 'export'])->name('history.pengiriman.export')
    ->middleware('auth.karyawan');



Route::get('/notifikasi', function () {
    return view('under-development');
})->middleware('auth.karyawan');

Route::get('/profil', function () {
    return view('profil');
})->middleware('auth.karyawan');

Route::post('/profil/update', [KaryawanController::class, 'updateprofil'])
    ->name('profil.update')
    ->middleware('auth.karyawan');
