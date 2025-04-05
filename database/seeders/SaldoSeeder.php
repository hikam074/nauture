<?php

namespace Database\Seeders;

use App\Models\M_Saldo;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaldoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // MASUKKAN SALDO AWAL
        $saldo = 0;

        M_Saldo::firstOrCreate([
            'saldo' => $saldo
        ]);
    }
}
