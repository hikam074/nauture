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
        // Pastikan status_pembayaran yang sama tidak dibuat dua kali
        $arr = [
            'Menunggu Pembayaran',
            'Dibayar, Mengemas barang',
            'Kedaluwarsa',
            'Gagal',
            'Sudah diterima, memproses pembayaran',
            //
            'Dibatalkan oleh Sistem',
            'Barang di perjalanan',
            'Barang diterima, Transaksi Selesai'
            ];
        $arrKode = [
            'PENDING',
            'PAID',
            'EXPIRED',
            'FAILED',
            'SETTLED',
            //
            'CANCELED BY SYSTEM',
            'PACKAGE SENT',
            'FINISH',
            ];

        foreach ($arr as $index => $nama) {
            M_StatusTransaksi::firstOrCreate([
                'id' => $index + 1, // ID akan dimulai dari 1
            ], [
                'kode_status_transaksi' => $arrKode[$index],
                'nama_status_transaksi' => $nama
            ]);
        }
    }
}
