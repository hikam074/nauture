<?php

namespace Database\Factories;

use App\Models\M_Lelang;
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
        // Pilih lelang secara random
        $lelang = M_Lelang::inRandomOrder()->first();

        if (!$lelang) {
            throw new \Exception("Tidak ada data lelang di database.");
        }

        // Pilih user secara random
        $userId = $this->faker->numberBetween(1, 20);

        // Harga pengajuan lebih tinggi dari harga pembukaan dan kelipatan 10,000
        $minimalHarga = $lelang->harga_dibuka + 10000;
        $hargaPengajuan = $this->faker->numberBetween($minimalHarga, $minimalHarga + 500000);
        $hargaPengajuan = ceil($hargaPengajuan / 10000) * 10000;

        // Cek apakah user sudah membuat pasang_lelang untuk lelang ini
        $existingPasangLelang = \App\Models\M_PasangLelang::where('lelang_id', $lelang->id)
            ->where('user_id', $userId)
            ->exists();

        // Jika user sudah memiliki pasang_lelang untuk lelang ini, skip
        if ($existingPasangLelang) {
            return $this->definition();
        }

        return [
            'lelang_id' => $lelang->id,
            'user_id' => $userId,
            'harga_pengajuan' => $hargaPengajuan,
            'waktu_dimenangkan' => null,
        ];
    }
}
