<?php

namespace Database\Seeders;

use App\Models\M_Rating;
use App\Models\M_StatusTransaksi;
use App\Models\M_Transaksi;
use Carbon\Carbon;
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
            $rating = M_Rating::updateOrCreate(
                ['transaksi_id' => $transaksi->id],
                [
                    'rating' => rand(4, 5),
                    'ulasan' => $ulasan[array_rand($ulasan)],
                ]
            );

            // Atur created_at dan updated_at satu hari setelah lelang berakhir
            if ($transaksi->lelang) {
                $waktuRating = Carbon::parse($transaksi->lelang->tanggal_ditutup)->addDay();
                $rating->created_at = $waktuRating;
                $rating->updated_at = $waktuRating;
                $rating->save();
            }
        }
    }
}
