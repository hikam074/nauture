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
        Schema::create('pengajuan_danas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan')->unique();
            $table->unsignedBigInteger('monitoring_id');    // FK ke monitorings
            $table->text('deskripsi');
            $table->unsignedBigInteger('inventori_id');     // FK ke inventoris
            $table->integer('total_nominal_diajukan');
            $table->integer('jumlah_akan_dibeli');
            $table->unsignedBigInteger('status_pengajuan_id');  // FK ke status_pengajuans
            $table->softDeletes();  // deleted_at

            $table->timestamps();

            // reference monitoring_id ke monitorings
            $table->foreign('monitoring_id')->references('id')->on('monitorings')->onDelete('cascade');
            // reference inventori_id ke inventoris
            $table->foreign('inventori_id')->references('id')->on('inventoris')->onDelete('cascade');
            // reference status_pengajuan_id ke status_pengajuans
            $table->foreign('status_pengajuan_id')->references('id')->on('status_pengajuans')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // hapus reference ke monitorings
        Schema::table('pengajuan_danas', function (Blueprint $table) {
            $table->dropForeign(['monitoring_id']);
            $table->dropColumn('monitoring_id');
        });
        // hapus reference ke inventoris
        Schema::table('pengajuan_danas', function (Blueprint $table) {
            $table->dropForeign(['inventori_id']);
            $table->dropColumn('inventori_id');
        });
        // hapus reference ke status_pengajuans
        Schema::table('pengajuan_danas', function (Blueprint $table) {
            $table->dropForeign(['status_pengajuan_id']);
            $table->dropColumn('status_pengajuan_id');
        });

        // hapus tabel
        Schema::dropIfExists('pengajuan_danas');
    }
};
