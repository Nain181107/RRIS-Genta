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
        Schema::table('perbaikan', function (Blueprint $table) {
            $table->index('shift');
            $table->index('jenis');
            $table->index('id_karyawan');
            $table->index('tim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perbaikan', function (Blueprint $table) {
            $table->dropIndex(['shift']);
            $table->dropIndex(['jenis']);
            $table->dropIndex(['id_karyawan']);
            $table->dropIndex(['tim']);
        });
    }
};
