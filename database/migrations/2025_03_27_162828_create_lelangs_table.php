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
        Schema::create('lelangs', function (Blueprint $table) {
            $table->string('kode_lelang')->unique()->primary();
            $table->string('nama_produk_lelang');
            $table->text('keterangan')->nullable();
            $table->integer('harga_dibuka');
            $table->datetime('tanggal_dibuka');
            $table->datetime('tanggal_ditutup');
            $table->string('foto_produk');
            $table->unsignedBigInteger('pemenang_id')->nullable();
            $table->unsignedBigInteger('katalog_id');
            $table->softDeletes();  // deleted_at

            $table->timestamps();

            // references ke katalog
            $table->foreign('katalog_id')->references('id')->on('katalogs')->onDelete('cascade');
            // referensi ke pasang_lelang
            $table->foreign('pemenang_id')->references('user_id')->on('pasang_lelangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus folder lelangs dan isinya
        if (Storage::disk('public')->exists('lelangs')) {
            Storage::disk('public')->deleteDirectory('lelangs');
        }

        // Hapus tabel
        Schema::dropIfExists('lelangs');
    }
};
