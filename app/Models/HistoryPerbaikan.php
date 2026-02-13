<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryPerbaikan extends Model
{
    protected $table = 'history_perbaikan';

    protected $fillable = [
        'perbaikan_id',
        'tanggal_perbaikan',
        'shift',
        'nomor_rod',
        'jenis',

        'e1_ers',
        'e1_est',
        'e1_jumlah',

        'e2_ers',
        'e2_cst',
        'e2_cstub',
        'e2_jumlah',

        'e3',
        'e4',

        's',
        'd',
        'b',

        'bac',
        'nba',
        'ba',

        'ba1',
        'r',
        'm',
        'cr',
        'c',
        'rl',
        'jumlah',

        'catatan',
        'tanggal_penerimaan',
        'fotobuktiperubahan',

        'id_karyawan',
        'tim'
    ];

    public function perbaikan()
    {
        return $this->belongsTo(Perbaikan::class, 'perbaikan_id');
    }


    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
