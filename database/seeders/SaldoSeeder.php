<?php

namespace Database\Seeders;

use App\Models\M_Saldo;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaldoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('saldos')->delete();
        // masukkan saldo awal
        $saldo = 0;

        M_Saldo::firstOrCreate([
            'id' => 1,
        ], [
            'saldo' => $saldo
        ]);
    }
}
