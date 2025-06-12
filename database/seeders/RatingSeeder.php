<?php

namespace Database\Seeders;

use App\Models\M_Rating;
use App\Models\M_StatusTransaksi;
use App\Models\M_Transaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID untuk status 'delivered'
        $statusDeliveredId = M_StatusTransaksi::where('kode_status_transaksi', 'delivered')->first()->id;

        // Ambil semua transaksi yang sudah 'delivered'
        $transaksis = M_Transaksi::where('status_transaksi_id', $statusDeliveredId)->get();

        $ulasan = [
            'Barang bagus sesuai deskripsi, pengiriman cepat!',
            'Sangat memuaskan, kualitas produknya top.',
            'Respon penjual cepat dan ramah. Recommended!',
            'Packingnya rapi dan aman. Terima kasih.',
            'Luar biasa, akan pesan lagi nanti.',
        ];

        foreach ($transaksis as $transaksi) {
            M_Rating::updateOrCreate(
                ['transaksi_id' => $transaksi->id],
                [
                    'rating' => rand(3, 5), // Rating acak antara 4 dan 5
                    'ulasan' => $ulasan[array_rand($ulasan)],
                ]
            );
        }
    }
}
