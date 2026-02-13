<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';

    protected $fillable = [
        'perbaikan_id',
        'tanggal_pengiriman',
        'shift',
        'id_karyawan',
        'tim'
    ];

    protected $casts = [
        'tanggal_pengiriman' => 'datetime',
    ];

    public function perbaikan()
    {
        return $this->belongsTo(Perbaikan::class);
    }

    public function getIdentitasRodAttribute()
    {
        return $this->perbaikan?->penerimaan?->identitasRod;
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
