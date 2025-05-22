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
            $table->string('order_id')->unique();   // kode transaksi
            $table->unsignedBigInteger('lelang_id');        // FK ke lelangs
            $table->unsignedBigInteger('pasang_lelang_id'); // FK ke pasang_lelangs
            $table->integer('gross_amount');
            $table->string('alamat');
            $table->string('snap_token')->nullable();
            $table->string('url_midtrans')->nullable();
            $table->datetime('payment_time')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();    // FK ke payment_methods
            $table->unsignedBigInteger('status_transaksi_id');              // FK ke status_transaksis

            $table->timestamps();

            // reference lelang_id ke lelangs
            $table->foreign('lelang_id')->references('id')->on('lelangs')->onDelete('cascade');
            // reference pasang_lelang_id ke pasang_lelangs
            $table->foreign('pasang_lelang_id')->references('id')->on('pasang_lelangs')->onDelete('cascade');
            // reference metode_pembayaran_id ke payment_methods
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');
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
        // hapus reference ke payment_methods
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn('payment_method_id');
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
