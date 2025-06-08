<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // jalankan seeder untuk input data & enum
        $this->call(RoleSeeder::class);
        $this->call(ProvinsiSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(AkunSeeder::class);
        $this->call(SaldoSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(StatusTransaksiSeeder::class);
        $this->call(KatalogSeeder::class);
        $this->call(LelangSeeder::class);
        $this->call(PasangLelangSeeder::class);

        // Jalankan command artisan untuk set winner
        Artisan::call('lelang-set-winner');
        $this->command->info('Winners for lelang have been set successfully.');
    }
}

