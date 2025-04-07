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
        Schema::create('log_keluars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_dana_id')->nullable(); // FK ke transaksis
            $table->text('deskripsi');
            $table->integer('jumlah_masuk');
            $table->unsignedBigInteger('user_id')->nullable();  // FK ke users

            $table->timestamps();

            // reference pengajuan_dana_id ke pengajuan_danas
            $table->foreign('pengajuan_dana_id')->references('id')->on('pengajuan_danas')->onDelete('set null');
            // reference user_id ke users
            $table->foreign('user_id')->references('id')->on('transaksis')->onDelete('set  null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // hapus reference ke pengajuan_danas
        Schema::table('log_keluars', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_dana_id']);
            $table->dropColumn('pengajuan_dana_id');
        });
        // hapus reference ke users
        Schema::table('log_keluars', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        // hapus tabel
        Schema::dropIfExists('log_keluars');
    }
};
