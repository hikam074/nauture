<?php

namespace Database\Seeders;

use App\Models\M_StatusTransaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role yang sama tidak dibuat dua kali
        $methods = [
            'menunggu pembayaran',
            'dibayar',
            'diproses',
            'dikirim',
            'selesai',
            'dibatalkan oleh sistem',
            'kedaluwarsa'];

        foreach ($methods as $index => $method) {
            M_StatusTransaksi::firstOrCreate([
                'id' => $index + 1, // ID akan dimulai dari 1
            ], [
                'nama_metode_pembayaran' => $method
            ]);
        }
    }
}
