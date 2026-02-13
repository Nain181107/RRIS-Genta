<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerputaranRodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exist = DB::table('perputaran_rod')->exists();

        if (! $exist) {
            DB::table('perputaran_rod')->insert([
                'hari' => 2,
                'id_karyawan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
