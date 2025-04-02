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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title_notif');
            $table->string('body_notif')->nullable();
            $table->string('link_click_action');
            $table->string('image_notif')->nullable();
            $table->string('kode_lelang');

            $table->timestamps();

            // references ke tabel lelangs
            $table->foreign('kode_lelang')->references('kode_lelang')->on('lelangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       // Hapus folder notifications dan isinya
        if (Storage::disk('public')->exists('notifications')) {
            Storage::disk('public')->deleteDirectory('notifications');
        }

        // Hapus tabel
        Schema::dropIfExists('notifications');
    }
};
