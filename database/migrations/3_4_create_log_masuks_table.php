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
        Schema::create('log_masuks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id')->nullable(); // FK ke transaksis
            $table->text('deskripsi');
            $table->integer('jumlah_masuk');
            $table->unsignedBigInteger('user_id')->nullable();  // FK ke users

            $table->timestamps();

            // reference transaksi_id ke transaksis
            $table->foreign('transaksi_id')->references('id')->on('transaksis')->onDelete('set null');
            // reference user_id ke users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // hapus reference ke transaksis
        Schema::table('log_masuks', function (Blueprint $table) {
            $table->dropForeign(['transaksi_id']);
            $table->dropColumn('transaksi_id');
        });
        // hapus reference ke users
        Schema::table('log_masuks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        // hapus tabel
        Schema::dropIfExists('log_masuks');
    }
};
