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
            $table->unsignedBigInteger('lelang_id');    // FK ke lelangs
            $table->string('title_notif');
            $table->string('body_notif')->nullable();
            $table->string('link_click_action');
            $table->string('image_notif')->nullable();
            $table->softDeletes();  // deleted_at

            $table->timestamps();

            // reference lelang_id ke lelangs
            $table->foreign('lelang_id')->references('id')->on('lelangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // hapus reference ke lelangs
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['lelang_id']);
            $table->dropColumn('lelang_id');
        });
       // Hapus folder notifications dan isinya
        if (Storage::disk('public')->exists('notifications')) {
            Storage::disk('public')->deleteDirectory('notifications');
        }

        // Hapus tabel
        Schema::dropIfExists('notifications');
    }
};
