<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        // Jalankan command dengan opsi --initial-only dan --no-notify
        Artisan::call('lelang-set-winner', [
            '--initial-only' => true,
            '--no-notify' => true
        ]);
        $this->command->info('Initial winners for lelang have been set successfully without notification.');

        $this->call(TransaksiSeeder::class);
        $this->call(RatingSeeder::class);
    }
}
