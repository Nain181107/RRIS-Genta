<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryPenerimaan extends Model
{
    protected $table = 'history_penerimaan';

    protected $fillable = [
        'penerimaan_id',
        'nomor_rod',
        'tanggal_penerimaan',
        'shift',
        'jenis',
        'stasiun',
        'e1',
        'e2',
        'e3',
        's',
        'd',
        'b',
        'ba',
        'r',
        'm',
        'cr',
        'c',
        'rl',
        'jumlah',
        'catatan',
        'id_karyawan',
        'tim'
    ];

    public function penerimaan()
    {
        return $this->belongsTo(Penerimaan::class, 'penerimaan_id');
    }


    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
