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
        Schema::create('katalogs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk')->unique();
            $table->text('deskripsi_produk')->nullable();
            $table->integer('harga_perkilo');
            $table->string('foto_produk');
            $table->softDeletes();  //deleted_at

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus folder katalog dan isinya
        if (Storage::disk('public')->exists('katalogs')) {
            Storage::disk('public')->deleteDirectory('katalogs');
        }

        // Hapus tabel
        Schema::dropIfExists('katalogs');
    }
};
