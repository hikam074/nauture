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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id'); // FK ke transaksis
            $table->integer('rating');
            $table->text('ulasan')->nullable();

            $table->timestamps();

            // reference transaksi_id ke transaksis
            $table->foreign('transaksi_id')->references('id')->on('transaksis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // hapus reference ke metode_pembayarans
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['transaksi_id']);
            $table->dropColumn('transaksi_id');
        });

        // hapus tabel
        Schema::dropIfExists('ratings');
    }
};
