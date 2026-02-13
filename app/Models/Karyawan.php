<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';

    protected $fillable = [
        'id_karyawan',
        'nama_lengkap',
        'tim',
        'tgl_lahir',
        'nohp',
        'jabatan',
        'password',
        'foto',
    ];

    protected $hidden = [
        'password',
    ];
}
