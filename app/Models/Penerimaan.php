<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    protected $table = 'penerimaan';

    protected $fillable = [
        'rod_id',
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

    protected $casts = [
        'tanggal_penerimaan' => 'datetime',
    ];

    public function identitasRod()
    {
        return $this->belongsTo(IdentitasRod::class, 'rod_id');
    }

    public function perbaikan()
    {
        return $this->hasOne(Perbaikan::class);
    }

    public function histories()
    {
        return $this->hasMany(HistoryPenerimaan::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
