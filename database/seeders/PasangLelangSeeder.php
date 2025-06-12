<?php

namespace Database\Seeders;

use App\Models\M_Lelang;
use App\Models\M_PasangLelang;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PasangLelangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lelangs = M_Lelang::all();
        $userKhususId = 21; // ID user yang harus selalu ada

        // Pastikan user dengan ID 21 ada di database
        if (!User::where('id', $userKhususId)->exists()) {
            $this->command->warn("User dengan ID {$userKhususId} tidak ditemukan, proses bid khusus dilewati.");
        }

        foreach ($lelangs as $lelang) {
            // Ambil semua ID user kecuali user khusus dan acak urutannya
            $userIds = User::where('id', '!=', $userKhususId)->pluck('id')->shuffle();

            // Tentukan jumlah penawar acak (misalnya, antara 1 sampai 4)
            $jumlahPenawarAcak = rand(1, min(4, $userIds->count()));

            // Buat bid dari user acak
            for ($i = 0; $i < $jumlahPenawarAcak; $i++) {
                $userId = $userIds->pop();
                if ($userId) {
                    M_PasangLelang::factory()->create([
                        'lelang_id' => $lelang->id,
                        'user_id' => $userId,
                    ]);
                }
            }

            // Selalu tambahkan bid untuk user_id 21
            // Cek dulu apakah user dengan ID 21 sudah ada
            if (User::where('id', $userKhususId)->exists()) {
                // Gunakan factory untuk membuat bid agar logikanya konsisten
                M_PasangLelang::factory()->create([
                    'lelang_id' => $lelang->id,
                    'user_id' => $userKhususId,
                ]);
            }
        }
    }
}
