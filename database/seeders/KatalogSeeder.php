<?php

namespace Database\Seeders;

use App\Models\M_Katalog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        M_Katalog::factory()->count(10)->create();
    }
}
