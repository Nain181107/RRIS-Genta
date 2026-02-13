<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    protected $table = 'perbaikan';

    protected $fillable = [
        'penerimaan_id',
        'tanggal_perbaikan',
        'shift',
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

    protected $casts = [
        'tanggal_perbaikan' => 'datetime',
        'tanggal_penerimaan' => 'datetime',
    ];

    public function penerimaan()
    {
        return $this->belongsTo(Penerimaan::class);
    }

    public function identitasRod()
    {
        return $this->hasOneThrough(
            IdentitasRod::class,
            Penerimaan::class,
            'id',
            'id',
            'penerimaan_id',
            'rod_id'
        );
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class);
    }

    public function histories()
    {
        return $this->hasMany(HistoryPerbaikan::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
