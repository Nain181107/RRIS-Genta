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
        Schema::create('perbaikan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('penerimaan_id')
                ->constrained('penerimaan')
                ->onDelete('cascade');

            $table->datetime('tanggal_perbaikan');
            $table->integer('shift');
            $table->string('jenis');

            $table->integer('e1_ers')->default(0);
            $table->integer('e1_est')->default(0);
            $table->integer('e1_jumlah')->default(0);

            $table->integer('e2_ers')->default(0);
            $table->integer('e2_cst')->default(0);
            $table->integer('e2_cstub')->default(0);
            $table->integer('e2_jumlah')->default(0);

            $table->integer('e3')->default(0);
            $table->integer('e4')->default(0);

            $table->integer('s')->default(0);
            $table->integer('d')->default(0);
            $table->integer('b')->default(0);

            $table->integer('bac')->default(0);
            $table->integer('nba')->default(0);
            $table->integer('ba')->default(0);

            $table->integer('ba1')->default(0);
            $table->integer('r')->default(0);
            $table->integer('m')->default(0);
            $table->integer('cr')->default(0);
            $table->integer('c')->default(0);
            $table->integer('rl')->default(0);
            $table->integer('jumlah')->default(0);

            $table->text('catatan')->nullable();
            $table->datetime('tanggal_penerimaan');
            $table->string('fotobuktiperubahan')->nullable();

            $table->foreignId('id_karyawan')->constrained('karyawan')->onDelete('restrict');
            $table->string('tim')->nullable();

            $table->timestamps();

            $table->index('penerimaan_id');
            $table->index('tanggal_perbaikan');
            $table->index('tanggal_penerimaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbaikan');
    }
};
