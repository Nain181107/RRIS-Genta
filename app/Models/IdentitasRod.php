<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdentitasRod extends Model
{
    protected $table = 'identitas_rod';

    protected $fillable = [
        'nomor_rod',
        'status',
    ];

    public function penerimaan()
    {
        return $this->hasOne(Penerimaan::class, 'rod_id');
    }

    public function perbaikan()
    {
        return $this->hasOne(Perbaikan::class, 'rod_id');
    }
}
