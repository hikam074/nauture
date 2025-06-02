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
        // reference pemenang_id ke pasang_lelangs
        Schema::table('lelangs', function (Blueprint $table) {
            $table->foreign('pemenang_id')->references('id')->on('pasang_lelangs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // hapus reference ke pasang_lelangs
        Schema::table('lelangs', function (Blueprint $table) {
            $table->dropForeign(['pemenang_id']);
            $table->dropColumn('pemenang_id');
        });
    }
};
