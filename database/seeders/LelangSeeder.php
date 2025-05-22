<?php

namespace Database\Seeders;

use App\Models\M_Lelang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LelangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $availableDates = [
            ['start' => '2025-05-21 10:00:00', 'end' => '2025-05-23 18:00:00'],
            ['start' => '2025-05-22 10:00:00', 'end' => '2025-05-24 18:00:00'],
            ['start' => '2025-05-23 10:00:00', 'end' => '2025-05-25 18:00:00'],
            ['start' => '2025-05-24 10:00:00', 'end' => '2025-05-26 18:00:00'],
            ['start' => '2025-05-25 10:00:00', 'end' => '2025-05-27 18:00:00'],
            ['start' => '2025-05-26 10:00:00', 'end' => '2025-05-28 18:00:00'],
            ['start' => '2025-05-27 10:00:00', 'end' => '2025-05-29 18:00:00'],
            ['start' => '2025-05-28 10:00:00', 'end' => '2025-05-30 18:00:00'],
            ['start' => '2025-05-29 10:00:00', 'end' => '2025-05-31 18:00:00'],
            ['start' => '2025-05-30 10:00:00', 'end' => '2025-06-01 18:00:00'],
        ];

        foreach ($availableDates as $date) {
            M_Lelang::factory()->withDate($date)->create();
        }
    }
}
