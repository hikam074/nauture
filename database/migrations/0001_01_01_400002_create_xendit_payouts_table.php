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
        Schema::create('xendit_payouts', function (Blueprint $table) {
            $table->id();
            $table->string('xendit_id');
            $table->unsignedBigInteger('pengajuan_dana_id'); // FK ke pengajuan_danas
            $table->integer('amount');
            $table->datetime('payout_at')->nullable();
            $table->unsignedBigInteger('status_transaksi_id');  // FK ke status_transaksis
            $table->string('bank_code');
            $table->string('account_number');
            $table->string('account_holder_name');
            $table->json('raw_response');

            $table->timestamps();

            // reference pengajuan_dana_id ke pengajuan_danas
            $table->foreign('pengajuan_dana_id')->references('id')->on('pengajuan_danas')->onDelete('cascade');
            // reference status_transaksi_id ke status_transaksis
            $table->foreign('status_transaksi_id')->references('id')->on('status_transaksis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // hapus reference ke pengajuan_danas
        Schema::table('xendit_payouts', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_dana_id']);
            $table->dropColumn('pengajuan_dana_id');
        });
        // hapus reference ke status_transaksis
        Schema::table('xendit_payouts', function (Blueprint $table) {
            $table->dropForeign(['status_transaksi_id']);
            $table->dropColumn('status_transaksi_id');
        });

        // hapus tabel
        Schema::dropIfExists('xendit_payouts');
    }
};
