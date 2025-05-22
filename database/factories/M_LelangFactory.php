<?php

namespace Database\Factories;

use App\Models\M_Katalog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class M_LelangFactory extends Factory
{
    protected $selectedDate;

    /**
     * Method untuk pass tanggal dari luar
     */
    public function withDate(array $date)
    {
        $this->selectedDate = $date;
        return $this;
    }

    public function definition(): array
    {
        $produk = [
            'Ubi Jalar' => [
                'keterangan' => 'Sortiran sedang, hasil panen segar dari ladang.',
                'harga_dibuka' => 120000,
                'jumlah_kg' => 100,
                'gambar' => 'ubi-jalar.jpg',
            ],
            'Ubi Ungu' => [
                'keterangan' => 'Ubi ungu segar, cocok untuk berbagai olahan.',
                'harga_dibuka' => 90000,
                'jumlah_kg' => 90,
                'gambar' => 'ubi-ungu.jpg',
            ],
            'Padi' => [
                'keterangan' => 'Padi berkualitas tinggi, siap untuk diproses.',
                'harga_dibuka' => 65000,
                'jumlah_kg' => 75,
                'gambar' => 'padi.jpg',
            ],
            'Buah Naga' => [
                'keterangan' => 'Buah naga segar dengan rasa manis alami.',
                'harga_dibuka' => 150000,
                'jumlah_kg' => 50,
                'gambar' => 'buah-naga.jpg',
            ],
        ];

        $date = $this->selectedDate ?? [
            'start' => now()->format('Y-m-d H:i:s'),
            'end' => now()->addDays(3)->format('Y-m-d H:i:s'),
        ];

        $selectedProduct = $this->faker->randomElement(array_keys($produk));
        $data = $produk[$selectedProduct];

        // Cari katalog sesuai produk berdasarkan nama produk
        $katalog = \App\Models\M_Katalog::where('nama_produk', $selectedProduct)->first();

        if (!$katalog) {
            throw new \Exception("Katalog untuk produk {$selectedProduct} tidak ditemukan.");
        }

        $originalPath = public_path("seeder/{$data['gambar']}");
        if (!file_exists($originalPath)) {
            throw new \Exception("Gambar $originalPath tidak ditemukan. Pastikan Anda sudah menambahkan gambar tersebut.");
        }

        $randomFileName = Str::random(40) . '.' . pathinfo($data['gambar'], PATHINFO_EXTENSION);
        $randomFilePath = "lelangs/$randomFileName";

        if (!Storage::disk('public')->exists('lelangs')) {
            Storage::disk('public')->makeDirectory('lelangs');
        }
        Storage::disk('public')->put($randomFilePath, file_get_contents($originalPath));

        $currentDate = \Carbon\Carbon::parse($date['start'])->format('Y-m-d');
        $lelangCountToday = \App\Models\M_Lelang::whereDate('tanggal_dibuka', $currentDate)
            ->where('katalog_id', $katalog->id)
            ->count();

        $kodeLelang = sprintf(
            "LEL-%s-%d-%d",
            $currentDate,
            $katalog->id,
            $lelangCountToday + 1
        );

        return [
            'kode_lelang' => $kodeLelang,
            'nama_produk_lelang' => "{$selectedProduct} {$data['jumlah_kg']}kg",
            'keterangan' => $data['keterangan'],
            'jumlah_kg' => $data['jumlah_kg'],
            'harga_dibuka' => $data['harga_dibuka'],
            'tanggal_dibuka' => $date['start'],
            'tanggal_ditutup' => $date['end'],
            'pemenang_id' => null,
            'foto_produk' => $randomFilePath,
            'katalog_id' => $katalog->id,
        ];
    }
}
