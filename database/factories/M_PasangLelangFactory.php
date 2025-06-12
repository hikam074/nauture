<?php

namespace Database\Factories;

use App\Models\M_Lelang;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\M_PasangLelang>
 */
class M_PasangLelangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // lelang_id dan user_id akan di-override dari seeder
            'lelang_id' => M_Lelang::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,

            // Closure ini akan dijalankan setelah lelang_id ditetapkan
            'harga_pengajuan' => function (array $attributes) {
                // Cari lelang berdasarkan ID yang diberikan
                $lelang = M_Lelang::find($attributes['lelang_id']);

                if (!$lelang) {
                    // Fallback jika lelang tidak ditemukan
                    return 10000;
                }

                // Harga pengajuan lebih tinggi dari harga pembukaan dan kelipatan 10,000
                $minimalHarga = $lelang->harga_dibuka + 10000;
                $hargaPengajuan = $this->faker->numberBetween($minimalHarga, $minimalHarga + 500000);

                // Pembulatan ke kelipatan 10000 terdekat
                return ceil($hargaPengajuan / 10000) * 10000;
            },
            'waktu_dimenangkan' => null,
        ];
    }
}
