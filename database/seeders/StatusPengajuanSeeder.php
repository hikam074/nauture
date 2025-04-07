<?php

namespace Database\Seeders;

use App\Models\M_StatusPengajuan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusPengajuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan status_pengajuan yang sama tidak dibuat dua kali
        $arr = [
            'diajukan',
            'disetujui',
            'gagal',
            'ditarik',
            // addons
            'ditolak',
            'dibatalkan',
        ];
        $arrKode = [
            'PENDING',
            'COMPLETED',
            'FAILED',
            'REVERSED',
            // addons
            'REJECTED',
            'CANCELED',
        ];

        foreach ($arr as $index => $name) {
            M_StatusPengajuan::firstOrCreate([
                'id' => $index + 1, // ID akan dimulai dari 1
            ], [
                'kode_status_pengajuan' => $arrKode[$index],
                'nama_status_pengajuan' => $name
            ]);
        }
    }
}
