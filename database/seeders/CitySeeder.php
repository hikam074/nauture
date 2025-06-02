<?php

namespace Database\Seeders;

use App\Models\M_City;
use App\Models\M_Provinsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua provinsi dari database
        $provinces = M_Provinsi::all();

        // Array untuk menyimpan nama kota yang sudah diproses
        $processedCities = [];

        foreach ($provinces as $province) {
            // Ambil data kota/kabupaten berdasarkan province_id
            $response = Http::get("https://hikam074.github.io/api-wilayah-indonesia/api/regencies/{$province->id}.json"); // Sesuaikan dengan endpoint Anda
            if ($response->failed()) {
                Log::error("Failed to fetch cities for province ID {$province->id}");
                continue;
            }
            $cities = $response->json(); // Pastikan respons berupa array data

            foreach ($cities as $city) {
                // Ubah nama kota menjadi huruf kapital dan hapus prefiks "Kabupaten" atau "Kota"
                $cleanName = strtoupper(trim(str_replace(['KABUPATEN ', 'KOTA '], '', $city['name'])));
                $cleanName = str_replace(' ', '', $cleanName);

                // Abaikan jika kota sudah diproses
                if (in_array($cleanName, $processedCities)) {
                    continue;
                }

                // Tambahkan nama kota ke array untuk menghindari duplikasi
                $processedCities[] = $cleanName;

                // Simpan ke database
                M_City::updateOrCreate([
                    'id' => $city['id'], // ID dari API
                ], [
                    'nama_city' => $cleanName, // Nama kota yang sudah diproses
                    'provinsi_id' => $province->id, // ID provinsi
                ]);
            }
        }
    }
}
