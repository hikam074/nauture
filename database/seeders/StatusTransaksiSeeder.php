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
            'Applying : Mengirim permintaan invoice',
            'Pending : Transaksi belum dibayar',
            'Settlement : Pembayaran Berhasil, Mengemas barang',
            'Expire : Waktu pembayaran telah habis',
            'Cancel : Transaksi dibatalkan',
            'Capture : Pembayaran kartu kredit telah ditangkap',
            'Deny : Transaksi ditolak'
            ];
        $arrKode = [
            'applying',
            'pending',
            'settlement',
            'expire',
            'cancel',
            'capture',
            'deny'
            ];

        foreach ($arr as $index => $nama) {
            M_StatusTransaksi::firstOrCreate([
                'id' => $index + 1, // ID akan dimulai dari 1
            ], [
                'nama_status_transaksi' => $nama,
                'kode_status_transaksi' => $arrKode[$index]
            ]);
        }
    }
}
