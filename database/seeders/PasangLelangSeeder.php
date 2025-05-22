<?php

namespace Database\Seeders;

use App\Models\M_PasangLelang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PasangLelangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        M_PasangLelang::factory()->count(50)->create();
    }
}
