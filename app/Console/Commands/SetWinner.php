<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SetWinner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lelang-set-winner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Command lelang-set-winner started.');
        $now = now();

        // Ambil lelang yang sudah mencapai batas waktu
        $auctions = DB::table('lelangs')
            ->where('tanggal_ditutup', '<=', $now)
            ->whereNull('deleted_at') // Hanya yang belum dihapus
            ->whereNull('pemenang_id')
            ->get();
        // Ambil lelang yang sudah mencapai batas waktu
        $pasang_auctions = DB::table('pasang_lelangs')
            ->get();

        foreach ($auctions as $auction) {
            $highestBid = DB::table('pasang_lelangs')
                ->where('lelang_id', $auction->id)
                ->orderBy('harga_pengajuan', 'desc')
                ->first();

            if ($highestBid) {
                // Update lelang dengan pemenang
                DB::table('lelangs')->where('id', $auction->id)
                    ->update([
                        'pemenang_id' => $highestBid->id,
                ]);
                DB::table('pasang_lelangs')
                    ->where('id', $highestBid->id)
                    ->update([
                        'waktu_dimenangkan' => now(),
                ]);
                Log::info('Auctions updated');
            } else {
                // Soft delete lelang
                DB::table('lelangs')->where('id', $auction->id)->update([
                    'deleted_at' => $now,
                ]);
                Log::info('Auctions deleted');
            }
        }
        Log::info('Auctions found: ' . $auctions->count());

        $this->info('Auction statuses updated successfully.');
    }
}
