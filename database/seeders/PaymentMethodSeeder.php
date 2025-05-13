<?php

namespace Database\Seeders;

use App\Models\M_PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            'credit_card',
            'bca_va',
            'bni_va',
            'bri_va',
            'permata_va',
            'mandiri_bill',
            'cimb_va',
            'gopay',
            'shopeepay',
            'qris',
            'indomaret',
            'alfamart',
            'akulaku',
            'kredivo',
            'bca_klikpay',
            'klikbca',
            'cimb_clicks',
            'danamon_online',
            'bri_epay',
            'uob_ezpay',
        ];

        foreach ($arr as $index => $name) {
            M_PaymentMethod::firstOrCreate([
                'id' => $index + 1, // ID akan dimulai dari 1
            ], [
                'nama_payment_method' => $name,
                'kode_payment_method' => $arrKode[$index]
            ]);
        }
    }
}
