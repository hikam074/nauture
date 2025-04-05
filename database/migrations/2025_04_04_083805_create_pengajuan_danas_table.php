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
            $table->string('kode_pengajuan')->primary();
            $table->text('deskripsi');
            $table->integer('total_nominal_diajukan');
            $table->integer('jumlah_akan_dibeli');
            $table->unsignedBigInteger('monitoring_id');
            $table->unsignedBigInteger('inventori_id');
            $table->unsignedBigInteger('status_pengajuan_id');
            $table->softDeletes();

            $table->timestamps();

            // references ke monitorings
            $table->foreign('monitoring_id')->references('id')->on('monitorings')->onDelete('cascade');
            // referensi ke inventoris
            $table->foreign('inventori_id')->references('id')->on('inventoris')->onDelete('cascade');
            // referensi ke status_pengajuans
            $table->foreign('status_pengajuan_id')->references('id')->on('status_pengajuans')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_danas');
    }
};
