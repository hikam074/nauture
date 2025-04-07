<?php

namespace Database\Seeders;

use App\Models\M_MetodePembayaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MetodePembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan metode_pembayaran yang sama tidak dibuat dua kali
        $arrManual = [
            'direct_debit',
        ];

        // pembayaran publishing mode, pastikan metode_pembayaran yang sama tidak dibuat dua kali
        $arr = [
            'Visa',
            'Mastercard',
            'JCB',
            'American Express',
            'Virtual Account BRI',
            'Virtual Account BNI',
            'Virtual Account BCA',
            'Virtual Account CIMB Niaga',
            'Virtual Account PermataBank',
            'Alfamart',
            'Q-ris',
            'OVO',
            'DANA',
            'LinkAja',
            'Direct Debit',
        ];
        $arrKode = [
            'visa',
            'mastercard',
            'JCB',
            'AMEX',
            'VA_BRI',
            'VA_BNI',
            'VA_BCA',
            'VA_CIMB',
            'VA_Permata',
            'alfamart',
            'qris',
            'ovo',
            'dana',
            'linkaja',
            'direct_debit',
        ];

        foreach ($arr as $index => $name) {
            M_MetodePembayaran::firstOrCreate([
                'id' => $index + 1, // ID akan dimulai dari 1
            ], [
                'kode_metode_pembayaran' => $arrKode[$index],
                'nama_metode_pembayaran' => $name
            ]);
        }
    }
}
