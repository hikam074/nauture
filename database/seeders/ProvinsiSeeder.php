<?php

namespace Database\Seeders;

use App\Models\M_Provinsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get('https://hikam074.github.io/api-wilayah-indonesia/api/provinces.json'); // Sesuaikan dengan endpoint Anda
        if ($response->failed()) {
            Log::error("Failed to fetch province");
        }
        $provinces = $response->json(); // Pastikan respons berupa array data

        foreach ($provinces as $province) {
            M_Provinsi::updateOrCreate([
                'id' => $province['id'], // ID dari API
            ], [
                'nama_provinsi' => $province['name'], // Nama provinsi
            ]);
        }
    }
}
