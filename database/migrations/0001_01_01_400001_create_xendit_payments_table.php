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
        Schema::create('xendit_payments', function (Blueprint $table) {
            $table->id();
            $table->string('xendit_id');
            $table->unsignedBigInteger('transaksi_id'); // FK ke transaksis
            $table->integer('amount');
            $table->datetime('paid_at')->nullable();
            $table->datetime('expiry_date');
            $table->unsignedBigInteger('status_transaksi_id');  // FK ke status_transaksis
            $table->unsignedBigInteger('metode_pembayaran_id'); // FK ke metode_pembayarans
            $table->string('bank_code')->nullable();
            $table->string('account_number')->nullable();
            $table->string('qr_string')->nullable();
            $table->json('raw_response');

            $table->timestamps();

            // reference transaksi_id ke transaksis
            $table->foreign('transaksi_id')->references('id')->on('transaksis')->onDelete('cascade');
            // reference status_transaksi_id ke status_transaksis
            $table->foreign('status_transaksi_id')->references('id')->on('status_transaksis')->onDelete('cascade');
            // reference metode_pembayaran_id ke metode_pembayarans
            $table->foreign('metode_pembayaran_id')->references('id')->on('metode_pembayarans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // hapus reference ke transaksis
        Schema::table('xendit_payments', function (Blueprint $table) {
            $table->dropForeign(['transaksi_id']);
            $table->dropColumn('transaksi_id');
        });
        // hapus reference ke status_transaksis
        Schema::table('xendit_payments', function (Blueprint $table) {
            $table->dropForeign(['status_transaksi_id']);
            $table->dropColumn('status_transaksi_id');
        });
        // hapus reference ke metode_pembayarans
        Schema::table('xendit_payments', function (Blueprint $table) {
            $table->dropForeign(['metode_pembayaran_id']);
            $table->dropColumn('metode_pembayaran_id');
        });

        // hapus tabel
        Schema::dropIfExists('xendit_payments');
    }
};
