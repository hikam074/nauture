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
            $table->id();
            $table->string('kode_lelang')->unique();
            $table->string('nama_produk_lelang');
            $table->text('keterangan')->nullable();
            $table->integer('jumlah_kg');
            $table->integer('harga_dibuka');
            $table->datetime('tanggal_dibuka');
            $table->datetime('tanggal_ditutup');
            $table->unsignedBigInteger('pemenang_id')->nullable(); // FK ke pasang_lelangs
            $table->string('foto_produk');
            $table->unsignedBigInteger('katalog_id');  // FK ke katalogs
            $table->softDeletes();  // deleted_at

            $table->timestamps();

            // reference katalog_id ke katalogs
            $table->foreign('katalog_id')->references('id')->on('katalogs')->onDelete('cascade');

            // buat reference ke pasang_lelangs ditambahkan terpisah karena ini circular relationship
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // hapus reference ke katalogs
        Schema::table('lelangs', function (Blueprint $table) {
            $table->dropForeign(['katalog_id']);
            $table->dropColumn('katalog_id');
        });
        // Hapus folder lelangs dan isinya
        if (Storage::disk('public')->exists('lelangs')) {
            Storage::disk('public')->deleteDirectory('lelangs');
        }

        // Hapus tabel
        Schema::dropIfExists('lelangs');
    }
};
