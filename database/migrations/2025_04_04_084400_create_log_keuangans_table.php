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
        Schema::create('log_keuangans', function (Blueprint $table) {
            $table->id();
            $table->text('deskripsi');
            $table->integer('jumlah');
            $table->string('kode_transaksi')->nullable(); // MASALAH XOR KODE TRANSAKSI DAN KODE PENGAJUAN
            $table->string('kode_pengajuan')->nullable(); // MASALAH XOR KODE TRANSAKSI DAN KODE PENGAJUAN

            $table->timestamps();


            // references ke transaksis
            $table->foreign('kode_transaksi')->references('kode_transaksi')->on('transaksis')->onDelete('cascade');
            // referensi ke pengajuan_dana
            $table->foreign('kode_pengajuan')->references('kode_pengajuan')->on('pengajuan_danas')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_keuangans');
    }
};
