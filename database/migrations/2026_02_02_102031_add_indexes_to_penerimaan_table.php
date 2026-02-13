<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penerimaan', function (Blueprint $table) {
            $table->index('rod_id');
            $table->index('tanggal_penerimaan');
            $table->index('jenis');
            $table->index('stasiun');
            $table->index('id_karyawan');
            $table->index('tim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penerimaan', function (Blueprint $table) {
            $table->dropIndex(['rod_id']);
            $table->dropIndex(['tanggal_penerimaan']);
            $table->dropIndex(['jenis']);
            $table->dropIndex(['stasiun']);
            $table->dropIndex(['id_karyawan']);
            $table->dropIndex(['tim']);
        });
    }
};
