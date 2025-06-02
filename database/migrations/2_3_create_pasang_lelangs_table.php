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
        Schema::create('pasang_lelangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lelang_id');  // FK ke lelangs
            $table->unsignedBigInteger('user_id');  // FK ke users
            $table->integer('harga_pengajuan');
            $table->datetime('waktu_dimenangkan')->nullable();
            $table->softDeletes();  // deleted_at

            $table->timestamps();

            // reference user_id ke users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // reference kode_lelang ke lelangs
            $table->foreign('lelang_id')->references('id')->on('lelangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // hapus reference ke users
        Schema::table('pasang_lelangs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        // hapus reference ke lelangs
        Schema::table('pasang_lelangs', function (Blueprint $table) {
            $table->dropForeign(['lelang_id']);
            $table->dropColumn('lelang_id');
        });

        // hapus tabel
        Schema::dropIfExists('pasang_lelangs');
    }
};
