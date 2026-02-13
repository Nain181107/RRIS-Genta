<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerputaranRod extends Model
{
    protected $table = 'perputaran_rod';

    protected $fillable = [
        'hari',
        'id_karyawan'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
