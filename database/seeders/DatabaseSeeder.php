<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // jalankan seeder untuk input data & enum
        $this->call(RoleSeeder::class);
        $this->call(AkunSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(SaldoSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(StatusTransaksiSeeder::class);
    }
}

