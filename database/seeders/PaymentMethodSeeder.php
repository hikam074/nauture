<?php

namespace Database\Seeders;

use App\Models\M_PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_methods')->truncate();
        $arr = [
            'Mandiri Bill',
            'PermataBank',
            'QRIS[GoPay/ShopeePay/Dana/OVO/LinkAja]',
        ];
        $arrKode = [
            'echannel',
            'bank_transfer',
            'qris',
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
