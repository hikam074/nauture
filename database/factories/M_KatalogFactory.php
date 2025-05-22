<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class M_KatalogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $produk = [
            'Ubi Jalar' => [
                'harga' => 12000,
                'gambar' => 'ubi-jalar.jpg',
                'deskripsi' => 'Ubi jalar manis, bergizi tinggi, cocok untuk makanan sehat sehari-hari.'
            ],
            'Ubi Ungu' => [
                'harga' => 9000,
                'gambar' => 'ubi-ungu.jpg',
                'deskripsi' => 'Ubi ungu kaya antioksidan dengan rasa lezat dan tekstur lembut.'
            ],
            'Ubi Cilembu' => [
                'harga' => 15000,
                'gambar' => 'ubi-cilembu.jpg',
                'deskripsi' => 'Bibitan lokal Cilembu Jawa Barat, Ubi Cilembu terkenal dengan rasa manis alami dan warna menarik.'
            ],
            'Padi' => [
                'harga' => 6500,
                'gambar' => 'padi.jpg',
                'deskripsi' => 'Padi sudah dalam bentuk gabah kering dengan kadar air 10%.'
            ],
            'Buah Naga' => [
                'harga' => 10000,
                'gambar' => 'buah-naga.jpg',
                'deskripsi' => 'Buah naga bibitan asli Banyuwangi Jawa Timur. Untuk varian yang tersedia silahkan cek tiap lelang.'
            ],
            'Cabe Rawit' => [
                'harga' => 40000,
                'gambar' => 'cabai-rawit.jpg',
                'deskripsi' => 'Cabe rawit pedas tajam, sering digunakan untuk menambah cita rasa masakan.'
            ],
            'Durian' => [
                'harga' => 100000,
                'gambar' => 'durian.jpg',
                'deskripsi' => 'Durian harum dan lezat, buah eksotis favorit banyak orang Indonesia.'
            ],
            'Belimbing' => [
                'harga' => 25000,
                'gambar' => 'belimbing.jpg',
                'deskripsi' => 'Belimbing segar dengan rasa asam manis, cocok untuk jus dan salad.'
            ],
            'Singkong' => [
                'harga' => 7000,
                'gambar' => 'singkong.jpg',
                'deskripsi' => 'Singkong bergizi, sumber karbohidrat alternatif dengan tekstur kenyal alami.'
            ],
            'Jagung Gelondong' => [
                'harga' => 3000,
                'gambar' => 'jagung.jpg',
                'deskripsi' => 'Jagung gelondong masih berkulit luar (gelondong bukan pipil ataupun kupas).'
            ],
        ];


        // Pilih produk secara acak
        $selectedProduct = fake()->unique()->randomElement(array_keys($produk));
        $data = $produk[$selectedProduct];

        // Path asli file gambar
        $originalPath = public_path("seeder/{$data['gambar']}");

        // Validasi gambar ada di storage
        if (!file_exists($originalPath)) {
            throw new \Exception("Gambar $originalPath tidak ditemukan. Pastikan Anda sudah menambahkan gambar tersebut.");
        }

        // Generate nama random untuk file gambar
        $randomFileName = Str::random(40) . '.' . pathinfo($data['gambar'], PATHINFO_EXTENSION);
        $randomFilePath = "katalogs/$randomFileName";

        // Pastikan folder tujuan ada
        if (!Storage::disk('public')->exists('katalogs')) {
            Storage::disk('public')->makeDirectory('katalogs');
        }

        // Salin file dengan nama baru ke storage/app/public/katalogs
        Storage::disk('public')->put($randomFilePath, file_get_contents($originalPath));

        return [
            'nama_produk' => $selectedProduct,
            'deskripsi_produk' => $data['deskripsi'],
            'harga_perkilo' => $data['harga'],
            'foto_produk' => $randomFilePath, 
        ];
    }
}
