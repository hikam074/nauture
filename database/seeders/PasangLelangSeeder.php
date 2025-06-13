<?php

namespace Database\Seeders;

use App\Models\M_Lelang;
use App\Models\M_PasangLelang;
use App\Models\User;
use Carbon\Carbon;
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
        $userKhususId = 21;

        if (!User::where('id', $userKhususId)->exists()) {
            $this->command->warn("User dengan ID {$userKhususId} tidak ditemukan, proses bid khusus dilewati.");
        }

        foreach ($lelangs as $lelang) {
            $userIds = User::where('id', '!=', $userKhususId)->pluck('id')->shuffle();
            $jumlahPenawarAcak = rand(1, min(4, $userIds->count()));

            // Buat bid dari user acak
            for ($i = 0; $i < $jumlahPenawarAcak; $i++) {
                $userId = $userIds->pop();
                if ($userId) {
                    $bid = M_PasangLelang::factory()->create([
                        'lelang_id' => $lelang->id,
                        'user_id' => $userId,
                    ]);
                    // Atur created_at dan updated_at secara acak dalam rentang waktu lelang
                    $waktuBid = $this->getRandomTimeBetween($lelang->tanggal_dibuka, $lelang->tanggal_ditutup);
                    $bid->created_at = $waktuBid;
                    $bid->updated_at = $waktuBid;
                    $bid->save();
                }
            }

            // Selalu tambahkan bid untuk user_id 21
            if (User::where('id', $userKhususId)->exists()) {
                $bidKhusus = M_PasangLelang::factory()->create([
                    'lelang_id' => $lelang->id,
                    'user_id' => $userKhususId,
                ]);
                // Atur created_at dan updated_at secara acak dalam rentang waktu lelang
                $waktuBidKhusus = $this->getRandomTimeBetween($lelang->tanggal_dibuka, $lelang->tanggal_ditutup);
                $bidKhusus->created_at = $waktuBidKhusus;
                $bidKhusus->updated_at = $waktuBidKhusus;
                $bidKhusus->save();
            }
        }
    }

    /**
     * Helper function untuk mendapatkan waktu acak di antara dua tanggal.
     */
    private function getRandomTimeBetween($start, $end)
    {
        $startTimestamp = Carbon::parse($start)->timestamp;
        $endTimestamp = Carbon::parse($end)->timestamp;
        $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);
        return Carbon::createFromTimestamp($randomTimestamp);
    }
}
