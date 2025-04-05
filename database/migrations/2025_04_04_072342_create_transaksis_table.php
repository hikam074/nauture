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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->string('kode_transaksi')->primary();
            $table->string('alamat');
            $table->unsignedBigInteger('status_transaksi_id');
            $table->datetime('deadline_transaksi');
            $table->string('kode_lelang');
            $table->unsignedBigInteger('metode_pembayaran_id');

            $table->timestamps();

            // references ke status_transaksis
            $table->foreign('status_transaksi_id')->references('id')->on('status_transaksis')->onDelete('cascade');
            // referensi ke metode_pembayarans
            $table->foreign('metode_pembayaran_id')->references('id')->on('metode_pembayarans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
