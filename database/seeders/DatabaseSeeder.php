<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // jalankan seeder untuk input data & enum
        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(SaldoSeeder::class);
        $this->call(MetodePembayaranSeeder::class);
        $this->call(StatusPengajuanSeeder::class);
        $this->call(StatusTransaksiSeeder::class);
    }
}
