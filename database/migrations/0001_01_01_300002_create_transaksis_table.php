<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->unsignedBigInteger('lelang_id');        // FK ke lelangs
            $table->unsignedBigInteger('pasang_lelang_id'); // FK ke pasang_lelangs
            $table->integer('nominal');
            $table->string('alamat');
            $table->datetime('deadline_transaksi');
            $table->unsignedBigInteger('metode_pembayaran_id'); // FK ke metode_pembayarans
            $table->unsignedBigInteger('status_transaksi_id');  // FK ke status_transaksis
            $table->string('bukti_transfer')->nullable();

            $table->timestamps();

            // reference lelang_id ke lelangs
            $table->foreign('lelang_id')->references('id')->on('lelangs')->onDelete('cascade');
            // reference pasang_lelang_id ke pasang_lelangs
            $table->foreign('pasang_lelang_id')->references('id')->on('pasang_lelangs')->onDelete('cascade');
            // reference metode_pembayaran_id ke metode_pembayarans
            $table->foreign('metode_pembayaran_id')->references('id')->on('metode_pembayarans')->onDelete('cascade');
            // reference status_transaksi_id ke status_transaksis
            $table->foreign('status_transaksi_id')->references('id')->on('status_transaksis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // hapus reference ke lelangs
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['lelang_id']);
            $table->dropColumn('lelang_id');
        });
        // hapus reference ke pasang_lelangs
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['pasang_lelang_id']);
            $table->dropColumn('pasang_lelang_id');
        });
        // hapus reference ke metode_pembayarans
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['metode_pembayaran_id']);
            $table->dropColumn('metode_pembayaran_id');
        });
        // hapus reference ke status_transaksis
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['status_transaksi_id']);
            $table->dropColumn('status_transaksi_id');
        });

        // Hapus folder transaksis dan isinya
        if (Storage::disk('public')->exists('transaksis')) {
            Storage::disk('public')->deleteDirectory('transaksis');
        }

        // hapus tabel
        Schema::dropIfExists('transaksis');
    }
};
