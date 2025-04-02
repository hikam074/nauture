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
            $table->integer('harga_pengajuan');
            $table->string('kode_lelang');
            $table->unsignedBigInteger('user_id');
            $table->softDeletes();  //deleted_at

            $table->timestamps();

            // references kode_lelang ke tabel lelangs
            $table->foreign('kode_lelang')->references('kode_lelang')->on('lelangs')->onDelete('cascade');
            // references id ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasang_lelangs');
    }
};
