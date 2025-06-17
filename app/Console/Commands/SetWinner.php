<?php

namespace App\Console\Commands;

use App\Http\Controllers\C_Whatsapp;
use Illuminate\Console\Command;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SetWinner extends Command
{
    protected $signature = 'lelang-set-winner {--initial-only : Hanya menjalankan proses penentuan pemenang awal} {--no-notify : Tidak mengirim notifikasi WhatsApp}';
    protected $description = 'Menentukan pemenang lelang dan mengganti pemenang jika pemenang awal tidak menyelesaikan transaksi dalam waktu tertentu';

    public function handle()
    {
        $toggleSchedulerOn = env('TURN_ON_SCHEDULER', false);

        if ($toggleSchedulerOn || $this->option('initial-only')) {
            if (!$toggleSchedulerOn && $this->option('initial-only')) {
                $this->info('INFO: Menjalankan set-winner karena flag --initial-only (dipanggil dari seeder/manual).');
                Log::info('INFO: Menjalankan set-winner karena flag --initial-only (dipanggil dari seeder/manual).');
            }
            $this->startHandle();
        } else {
            $this->info('INFO: Scheduler dinonaktifkan via .env dan tidak ada flag --initial-only.');
            Log::info('INFO: Scheduler dinonaktifkan via .env dan tidak ada flag --initial-only.');
        }
    }

    private function startHandle()
    {
        Log::info('Memulai proses penentuan pemenang...');
        $this->info('Memproses penentuan pemenang...');

        $this->setInitialWinners();

        if (!$this->option('initial-only')) {
            $this->shiftOverdueWinners();
        }

        $this->info('Proses penentuan pemenang selesai.');
        Log::info('Proses penentuan pemenang selesai.');
    }

    private function setInitialWinners()
    {
        $now = now();
        Log::info('Langkah 1: Menentukan pemenang awal untuk lelang yang baru berakhir.');

        $auctionsWithoutWinner = DB::table('lelangs')
            ->where('tanggal_ditutup', '<=', $now)
            ->whereNull('deleted_at')
            ->whereNull('pemenang_id')
            ->get();

        foreach ($auctionsWithoutWinner as $auction) {
            DB::transaction(function () use ($auction, $now) {
                $highestBid = DB::table('pasang_lelangs')
                    ->where('lelang_id', $auction->id)
                    ->whereNull('deleted_at')
                    ->orderBy('harga_pengajuan', 'desc')
                    ->first();

                if ($highestBid) {
                    DB::table('lelangs')->where('id', $auction->id)->update(['pemenang_id' => $highestBid->id]);
                    DB::table('pasang_lelangs')->where('id', $highestBid->id)->update(['waktu_dimenangkan' => $now]);

                    Log::info("Pemenang awal ditetapkan untuk Lelang ID: {$auction->id}, Pemenang (pasang_lelang_id): {$highestBid->id}");

                    // Kirim notifikasi setelah transaksi DB berhasil
                    if (!$this->option('no-notify')) {
                        $this->sendWinnerNotification($highestBid->user_id, [
                            'nama_produk_lelang' => $auction->nama_produk_lelang,
                            'name' => DB::table('users')->where('id', $highestBid->user_id)->value('name'),
                            'kode_lelang' => $auction->kode_lelang,
                            'bid' => $highestBid->harga_pengajuan,
                            'deadline' => $now->copy()->addHours(3)->toDateTimeString(),
                            'url' => route('lelang.show', ['id' => $auction->id]),
                        ]);
                    }
                } else {
                    DB::table('lelangs')->where('id', $auction->id)->update(['deleted_at' => $now]);
                    Log::info("Lelang ID: {$auction->id} dihapus (soft delete) karena tidak ada penawar.");
                }
            });
        }
    }

    /**
     * =================================================================================
     * FUNGSI INI TELAH DIREFAKTOR SECARA PENUH UNTUK KEAMANAN DAN KONSISTENSI DATA
     * - Menggunakan DB::transaction untuk memastikan semua operasi atomik.
     * - Memperbaiki logika hukuman yang hilang.
     * - Memperjelas logika pengecekan waktu.
     * =================================================================================
     */
    private function shiftOverdueWinners()
    {
        Log::info('Langkah 2: Memeriksa dan mengganti pemenang yang overdue.');

        $idSafeStatuses = DB::table('status_transaksis')->whereIn('kode_status_transaksi', ['capture', 'settlement', 'delivering', 'delivered'])->pluck('id');
        $id_expire = DB::table('status_transaksis')->where('kode_status_transaksi', 'expire')->value('id');

        $overdueAuctions = DB::table('lelangs')
            ->join('pasang_lelangs', 'lelangs.pemenang_id', '=', 'pasang_lelangs.id')
            ->whereNotNull('lelangs.pemenang_id')
            ->whereNull('lelangs.deleted_at')
            ->where('pasang_lelangs.waktu_dimenangkan', '<', now()->subHours(3)) // Pengecekan waktu lebih efisien
            ->select('lelangs.*', 'pasang_lelangs.user_id as pemenang_user_id', 'pasang_lelangs.id as pasang_lelang_id')
            ->get();

        foreach ($overdueAuctions as $auction) {
            // Periksa apakah ada transaksi yang sudah aman (lunas/selesai)
            $safeTransactionExists = DB::table('transaksis')
                ->where('lelang_id', $auction->id)
                ->where('pasang_lelang_id', $auction->pasang_lelang_id)
                ->whereIn('status_transaksi_id', $idSafeStatuses)
                ->exists();

            if ($safeTransactionExists) {
                Log::info("Lelang ID: {$auction->id} memiliki transaksi yang sudah aman. Pemenang tidak diganti.");
                continue;
            }

            Log::info("OVERDUE DITEMUKAN: Lelang ID: {$auction->id}, Pemenang saat ini (User ID): {$auction->pemenang_user_id}. Memulai proses penggantian.");

            try {
                $this->processWinnerShift($auction, $id_expire);
            } catch (Throwable $e) {
                Log::error("GAGAL TOTAL memproses pergeseran pemenang untuk Lelang ID: {$auction->id}. Error: " . $e->getMessage());
            }
        }
    }

    private function processWinnerShift($auction, $id_expire)
    {
        $now = now();
        $currentWinnerUserId = $auction->pemenang_user_id;
        $currentPasangLelangId = $auction->pasang_lelang_id;

        // Cari bidder berikutnya di luar transaksi
        $nextHighestBid = DB::table('pasang_lelangs')
            ->where('lelang_id', $auction->id)
            ->where('id', '<>', $currentPasangLelangId)
            ->whereNull('deleted_at')
            ->orderBy('harga_pengajuan', 'desc')
            ->first();

        DB::transaction(function () use ($auction, $currentWinnerUserId, $currentPasangLelangId, $nextHighestBid, $id_expire, $now) {
            // 1. HUKUM PEMENANG LAMA (selalu dijalankan)
            DB::table('users')->where('id', $currentWinnerUserId)->increment('suspend_point');
            DB::table('pasang_lelangs')->where('id', $currentPasangLelangId)->update(['deleted_at' => $now]);
            Log::info("[TX] Lelang ID: {$auction->id} - User ID: {$currentWinnerUserId} diberi suspend point dan bid-nya dihapus.");

            // 2. EXPIRE TRANSAKSI LAMA (jika ada)
            DB::table('transaksis')
                ->where('lelang_id', $auction->id)
                ->where('pasang_lelang_id', $currentPasangLelangId)
                ->update(['status_transaksi_id' => $id_expire]);
            Log::info("[TX] Lelang ID: {$auction->id} - Transaksi lama (jika ada) di-expire.");

            // 3. TENTUKAN NASIB LELANG
            if ($nextHighestBid) {
                // KASUS A: Ada pemenang berikutnya
                DB::table('lelangs')->where('id', $auction->id)->update(['pemenang_id' => $nextHighestBid->id]);
                DB::table('pasang_lelangs')->where('id', $nextHighestBid->id)->update(['waktu_dimenangkan' => $now]);
                Log::info("[TX] Lelang ID: {$auction->id} - Pemenang baru ditetapkan: (pasang_lelang_id) {$nextHighestBid->id}.");

            } else {
                // KASUS B: Tidak ada pemenang berikutnya
                DB::table('lelangs')->where('id', $auction->id)->update(['deleted_at' => $now]);
                Log::info("[TX] Lelang ID: {$auction->id} - Tidak ada pemenang pengganti, lelang di-soft-delete.");
            }
        });

        // Kirim notifikasi HANYA JIKA transaksi berhasil dan ada pemenang baru
        if ($nextHighestBid && !$this->option('no-notify')) {
            Log::info("Mengirim notifikasi ke pemenang baru Lelang ID: {$auction->id}.");
            $this->sendWinnerNotification($nextHighestBid->user_id, [
                'nama_produk_lelang' => $auction->nama_produk_lelang,
                'name' => DB::table('users')->where('id', $nextHighestBid->user_id)->value('name'),
                'kode_lelang' => $auction->kode_lelang,
                'bid' => $nextHighestBid->harga_pengajuan,
                'deadline' => $now->copy()->addHours(3)->toDateTimeString(),
                'url' => route('lelang.show', ['id' => $auction->id]),
            ]);
        }

        Log::info("PROSES SELESAI untuk Lelang ID: {$auction->id}.");
    }

    private function sendWinnerNotification($userId, $params)
    {
        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user || !$user->no_telp) {
            Log::warning("Gagal mengirim notifikasi, nomor telepon tidak ditemukan untuk User ID: {$userId}.");
            return;
        }

        try {
            $controller = new C_Whatsapp;
            $response = $controller->sendMessage(new HttpRequest([
                'target' => $user->no_telp,
                'template' => 'pemenang_lelang',
                'params' => $params,
            ]));
            Log::info("Respon notifikasi WhatsApp ke User ID {$userId}: " . $response);
        } catch (Throwable $e) {
            Log::error("Gagal mengirim notifikasi WhatsApp ke User ID {$userId}. Error: " . $e->getMessage());
        }
    }
}
