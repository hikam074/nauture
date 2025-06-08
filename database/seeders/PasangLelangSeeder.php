<?php

namespace Database\Seeders;

use App\Models\M_Lelang;
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
        // Generate data dengan factory untuk user ID 1-20
        M_PasangLelang::factory(100)->create();

        // Tambahkan bid untuk user ID 23 pada setiap lelang
        $lelangs = M_Lelang::all();

        foreach ($lelangs as $lelang) {
            // Cek bid tertinggi
            $highestBid = $lelang->pasangLelang()->max('harga_pengajuan') ?? $lelang->harga_dibuka;

            // Tambahkan bid ID 23 jika belum ada
            $existingBid = $lelang->pasangLelang()->where('user_id', 23)->exists();
            if (!$existingBid) {
                M_PasangLelang::create([
                    'lelang_id' => $lelang->id,
                    'user_id' => 23,
                    'harga_pengajuan' => $highestBid + 20000,
                    'waktu_dimenangkan' => null,
                ]);
            }
        }
    }
}
