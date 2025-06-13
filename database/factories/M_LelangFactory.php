<?php

namespace Database\Factories;

use App\Models\M_Katalog;
use App\Models\M_Lelang;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class M_LelangFactory extends Factory
{
    protected $model = M_Lelang::class;
    protected $selectedDate;

    public function withDate(array $date)
    {
        $this->selectedDate = $date;
        return $this;
    }

    public function definition(): array
    {
        // 1. AMBIL PRODUK ACAK DARI KATALOG
        $katalog = M_Katalog::inRandomOrder()->first();
        if (!$katalog) {
            throw new \Exception("Tabel katalog kosong. Harap jalankan seeder katalog terlebih dahulu.");
        }

        // 2. DATA DINAMIS UNTUK MEMPERKAYA LELANG

        // Peta Varietas: Diperluas untuk mencakup lebih banyak produk.
        // Produk yang tidak ada di sini tidak akan memiliki varietas.
        $varietasMap = [
            'Durian' => ['Musang King', 'Bawor', 'Montong', 'Petruk'],
            'Cabe Rawit' => ['Ori', 'Domba', 'Japlak', 'Pelangi'],
            'Ubi Cilembu' => ['Nirkum', 'Rancing', 'Madu'],
            'Ubi Jalar' => ['Oranye', 'Jepang', 'Madu'],
            'Buah Naga' => ['Merah Super', 'Putih Jumbo', 'Kuning'],
            'Belimbing' => ['Madu', 'Dewi', 'Demak Jumbo'],
            'Singkong' => ['Manggu', 'Gajah', 'Mentega'],
            'Jagung Gelondong' => ['Hibrida', 'Manis', 'Ungu'],
        ];

        // Definisi Sortiran dengan deskripsi yang lebih menarik
        $sortiran = [
            ['nama' => 'Super', 'keterangan' => 'Sortiran kualitas super (Grade A), ukuran di atas rata-rata pasar.'],
            ['nama' => 'Standar', 'keterangan' => 'Sortiran standar (Grade B), ukuran seragam sesuai kebutuhan umum.'],
            ['nama' => 'Ekonomis', 'keterangan' => 'Sortiran ekonomis (Grade C), cocok untuk kebutuhan industri atau olahan.'],
        ];

        // Peta Gambar: Dilengkapi untuk semua produk di katalog Anda
        $gambarMap = [
            'Ubi Jalar' => 'ubi-jalar.jpg',
            'Ubi Ungu' => 'ubi-ungu.jpg',
            'Ubi Cilembu' => 'ubi-cilembu.jpg',
            'Padi' => 'padi.jpg',
            'Buah Naga' => 'buah-naga.jpg',
            'Cabe Rawit' => 'cabai-rawit.jpg',
            'Durian' => 'durian.jpg',
            'Belimbing' => 'belimbing.jpg',
            'Singkong' => 'singkong.jpg',
            'Jagung Gelondong' => 'jagung.jpg',
        ];

        // 3. LOGIKA PEMILIHAN & GENERASI DATA ACAK

        $selectedSortiran = $this->faker->randomElement($sortiran);
        $selectedVarietas = '';

        if (array_key_exists($katalog->nama_produk, $varietasMap)) {
            $selectedVarietas = $this->faker->randomElement($varietasMap[$katalog->nama_produk]);
        }

        // --- ATURAN BARU UNTUK BERAT DAN HARGA ---
        // Membuat berat acak dengan kelipatan 5 (misal: 20, 25, 30, ... 250)
        $jumlahKg = $this->faker->numberBetween(4, 50) * 5;

        // Membuat harga buka acak dengan kelipatan 10.000 (misal: 50.000, 60.000, ... 2.000.000)
        $hargaDibuka = $this->faker->numberBetween(5, 200) * 10000;

        // 4. SUSUN NAMA & KETERANGAN FINAL

        $namaProdukLelang = $katalog->nama_produk;
        if (!empty($selectedVarietas)) {
            $namaProdukLelang .= " {$selectedVarietas}";
        }
        $namaProdukLelang .= " {$selectedSortiran['nama']} {$jumlahKg}kg";

        $keteranganLelang = "{$selectedSortiran['keterangan']} {$katalog->deskripsi_produk}";

        // 5. PENANGANAN GAMBAR, KODE, DAN TANGGAL (SAMA SEPERTI SEBELUMNYA)

        $namaGambarAsli = $gambarMap[$katalog->nama_produk] ?? 'default.jpg';
        $originalPath = public_path("seeder/{$namaGambarAsli}");
        if (!file_exists($originalPath)) {
            throw new \Exception("Gambar $originalPath tidak ditemukan.");
        }

        $randomFileName = Str::random(40) . '.' . pathinfo($namaGambarAsli, PATHINFO_EXTENSION);
        $randomFilePath = "lelangs/$randomFileName";
        if (!Storage::disk('public')->exists('lelangs')) {
            Storage::disk('public')->makeDirectory('lelangs');
        }
        Storage::disk('public')->put($randomFilePath, file_get_contents($originalPath));

        $date = $this->selectedDate ?? [
            'start' => now()->format('Y-m-d H:i:s'),
            'end' => now()->addDays(3)->format('Y-m-d H:i:s'),
        ];

        $currentDate = \Carbon\Carbon::parse($date['start'])->format('Y-m-d');
        $lelangCountToday = M_Lelang::whereDate('tanggal_dibuka', $currentDate)->where('katalog_id', $katalog->id)->count();
        $kodeLelang = sprintf("LEL-%s-%d-%d", $currentDate, $katalog->id, $lelangCountToday + 1);

        // 6. KEMBALIKAN DATA LELANG YANG SUDAH JADI
        return [
            'kode_lelang' => $kodeLelang,
            'nama_produk_lelang' => $namaProdukLelang,
            'keterangan' => $keteranganLelang,
            'jumlah_kg' => $jumlahKg,
            'harga_dibuka' => $hargaDibuka,
            'tanggal_dibuka' => $date['start'],
            'tanggal_ditutup' => $date['end'],
            'pemenang_id' => null,
            'foto_produk' => $randomFilePath,
            'katalog_id' => $katalog->id,
        ];
    }
}
