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
        Schema::create('penerimaan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rod_id')->constrained('identitas_rod')->onDelete('cascade');

            $table->datetime('tanggal_penerimaan');
            $table->integer('shift');
            $table->string('jenis');
            $table->string('stasiun');

            $table->integer('e1')->default(0);
            $table->integer('e2')->default(0);
            $table->integer('e3')->default(0);
            $table->integer('s')->default(0);
            $table->integer('d')->default(0);
            $table->integer('b')->default(0);
            $table->integer('ba')->default(0);
            $table->integer('r')->default(0);
            $table->integer('m')->default(0);
            $table->integer('cr')->default(0);
            $table->integer('c')->default(0);
            $table->integer('rl')->default(0);
            $table->integer('jumlah')->default(0);

            $table->text('catatan')->nullable();

            $table->foreignId('id_karyawan')->constrained('karyawan')->onDelete('restrict');
            $table->string('tim')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaan');
    }
};
