<?php

namespace App\Console\Commands;

use App\Http\Controllers\C_Whatsapp;
use App\Models\M_Lelang;
use Illuminate\Console\Command;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class SetWinner extends Command
{
    protected $signature = 'lelang-set-winner {--initial-only : Hanya menjalankan proses penentuan pemenang awal} {--no-notify : Tidak mengirim notifikasi WhatsApp}';
    protected $description = 'Menentukan pemenang lelang dan mengganti pemenang jika pemenang awal tidak menyelesaikan transaksi dalam waktu tertentu';

    public function handle()
    {
        $toggleSchedulerOn = env('TURN_ON_SCHEDULER', false);

        // --- INI ADALAH LOGIKA UTAMA YANG DIPERBAIKI ---
        // Proses akan berjalan JIKA:
        // 1. Toggle utama di .env AKTIF.
        // ATAU
        // 2. Ada flag --initial-only yang dikirim (kasus untuk seeder).
        if ($toggleSchedulerOn || $this->option('initial-only')) {
            // Jika dipanggil dari seeder dengan toggle mati, beri info khusus.
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

        // 1. Selalu jalankan proses penentuan pemenang awal untuk lelang yang baru berakhir.
        $this->setInitialWinners();

        // 2. Jika tidak ada opsi --initial-only, jalankan juga proses pergeseran pemenang.
        if (!$this->option('initial-only')) {
            $this->shiftOverdueWinners();
        }

        $this->info('Proses penentuan pemenang selesai.');
        Log::info('Proses penentuan pemenang selesai.');
    }

    private function setInitialWinners()
    {
        $now = now();
        Log::info('Command lelang-set-winner started. step 1');
        // Langkah 1: Menentukan pemenang pertama
        $auctionsWithoutWinner = DB::table('lelangs')
            ->where('tanggal_ditutup', '<=', $now)
            ->whereNull('deleted_at') // Lelang yang belum dihapus
            ->whereNull('pemenang_id') // Lelang yang belum memiliki pemenang
            ->get();

        foreach ($auctionsWithoutWinner as $auction) {
            $highestBid = DB::table('pasang_lelangs')
                ->where('lelang_id', $auction->id)
                ->whereNull('deleted_at')
                ->orderBy('harga_pengajuan', 'desc')
                ->first();

            if ($highestBid) {
                // Update pemenang pertama
                DB::table('lelangs')->where('id', $auction->id)
                    ->update(['pemenang_id' => $highestBid->id]);

                DB::table('pasang_lelangs')->where('id', $highestBid->id)
                    ->update(['waktu_dimenangkan' => $now]);

                // Kirim pesan WhatsApp
                if (!$this->option('no-notify')) {
                    $this->sendWinnerNotification($highestBid->user_id, [
                        'nama_produk_lelang' => $auction->nama_produk_lelang,
                        'name' => DB::table('users')->where('id', $highestBid->user_id)->value('name'),
                        'kode_lelang' => $auction->kode_lelang,
                        'bid' => $highestBid->harga_pengajuan,
                        'deadline' => $now->addHours(3)->toDateTimeString(),
                        'url' => route('lelang.show', ['id' => $auction->id]),
                    ]);
                }

                Log::info('Pemenang pertama ditetapkan untuk lelang ID: ' . $auction->id);
            } else {
                // Soft delete jika tidak ada bidder sama sekali
                DB::table('lelangs')->where('id', $auction->id)
                    ->update(['deleted_at' => $now]);

                Log::info('Lelang dihapus karena tidak ada bidder, ID: ' . $auction->id);
            }
        }
    }

    private function shiftOverdueWinners()
    {
        $now = now();
        // Langkah 2: Mengganti pemenang jika pemenang tidak menyelesaikan transaksi
        $auctionsWithWinner = DB::table('lelangs')
            ->whereNotNull('pemenang_id')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'asc')
            ->get();

        $idSafeStatuses = DB::table('status_transaksis')
            ->whereIn('kode_status_transaksi', ['capture', 'settlement', 'delivering', 'delivered', 'expire'])
            ->pluck('id')
            ->toArray();

        $idPendingStatuses = DB::table('status_transaksis')
            ->whereIn('kode_status_transaksi', ['applying', 'pending', 'cancel', 'deny'])
            ->pluck('id')
            ->toArray();

        // id expired
        $id_expire = DB::table('status_transaksis')
            ->where('kode_status_transaksi', 'expire')
            ->value('id');

        // MODE DEV ---------------------------------------------------------------------------------------------
        // $limit = 1; // Batasi hingga x iterasi
        // $count = 0;
        // MODE DEV

        Log::info('Command lelang-set-winner started. step 2');

        foreach ($auctionsWithWinner as $auction) {

            $currentWinner = DB::table('pasang_lelangs')
                ->where('id', $auction->pemenang_id)
                ->first();

            if ($currentWinner && $currentWinner->waktu_dimenangkan && $now->diffInHours($currentWinner->waktu_dimenangkan) < -3) {

                // MODE DEV ---------------------------------------------------------------------------------------------
                // if ($count >= $limit) {
                //     break;
                // }
                // MODE DEV

                // Periksa status transaksi
                $transaction = DB::table('transaksis')
                    ->where('lelang_id', $auction->id)
                    ->first();

                if ($transaction && in_array($transaction->status_transaksi_id, $idSafeStatuses)) {
                    Log::info('Transaksi aman untuk ID transaksi: ' . $transaction->id . ' - Tidak ada penggantian pemenang.');
                    continue;
                }

                if (!$transaction || in_array($transaction->status_transaksi_id, $idPendingStatuses)) {
                    Log::info('________________ OVERDUE FOUND ________________');
                    Log::info('[1] Transaksi ini status pending atau tidak transaksi melebihi 3 jam , ID pasang_lelangs :' . $currentWinner->id . ' ID user :' . $currentWinner->user_id);
                    // break;

                    // Cari bidder berikutnya
                    $nextHighestBid = DB::table('pasang_lelangs')
                        ->where('lelang_id', $auction->id)
                        ->where('id', '<>', $auction->pemenang_id)
                        ->whereNull('deleted_at')
                        ->orderBy('harga_pengajuan', 'desc')
                        ->first();

                    if ($nextHighestBid) {
                        if ($transaction) {
                            Log::info('[2] karena tidak ada transaksi maka ID pasang_lelangs :' . $currentWinner->id . ' akan diganti ke id : ' . $nextHighestBid->id);
                        } else {
                            Log::info('[2] karena transaksi tidak kunjung bayar maka ID pasang_lelangs :' . $currentWinner->id . ' akan diganti ke id : ' . $nextHighestBid->id);
                        }

                        // Update pemenang baru
                        DB::table('lelangs')->where('id', $auction->id)
                            ->update(['pemenang_id' => $nextHighestBid->id]);
                        Log::info('[2 - 1] pemenang baru ID pasang_lelangs :' . $nextHighestBid->id);

                        DB::table('pasang_lelangs')->where('id', $nextHighestBid->id)
                            ->update(['waktu_dimenangkan' => $now]);
                        Log::info('[2 - 2] waktu dimenangkan baru :' . $now);

                        if ($transaction) {
                            DB::table('transaksis')->where('id', $transaction->id)
                                ->update(['status_transaksi_id' => $id_expire]); // expire
                            Log::info('[2 - 2 - 1] karena ada transaksi maka di jadikan expire untuk ID transaksi :' . $transaction->id);
                        }

                        DB::table('users')->where('id', $currentWinner->user_id)
                            ->increment('suspend_point');
                        Log::info('[2 - 3] menghukum suspend point untuk ID user :' . $currentWinner->user_id);

                        DB::table('pasang_lelangs')->where('id', $currentWinner->id)
                            ->update(['deleted_at' => $now]);
                        Log::info('[2 - 4] menghapus ID pasang_lelangs :' . $currentWinner->id);

                        // Kirim pesan WhatsApp
                        $this->sendWinnerNotification($nextHighestBid->user_id, [
                            'nama_produk_lelang' => $auction->nama_produk_lelang,
                            'name' => DB::table('users')->where('id', $nextHighestBid->user_id)->value('name'),
                            'kode_lelang' => $auction->kode_lelang,
                            'bid' => $nextHighestBid->harga_pengajuan,
                            'deadline' => $now->addHours(3)->toDateTimeString(),
                            'url' => route('lelang.show', ['id' => $auction->id]),
                        ]);

                        Log::info('_____ Pemenang BERHASIL diganti untuk lelang ID: ' . $auction->id . ' _____');
                    } else {
                        // Soft delete jika tidak ada bidder berikutnya
                        DB::table('lelangs')->where('id', $auction->id)
                            ->update(['deleted_at' => $now]);

                        Log::info('Lelang dihapus karena tidak ada bidder berikutnya, ID LELANG: ' . $auction->id);
                    }
                }
                // $count++;
            }
        }

        Log::info('Command lelang-set-winner completed.');
    }

    private function sendWinnerNotification($userId, $params)
    {
        $user = DB::table('users')->where('id', $userId)->first();
        $controller = new C_Whatsapp;

        if ($user && $user->no_telp) {
            $respons = $controller->sendMessage(new HttpRequest([
                'target' => $user->no_telp,
                'template' => 'pemenang_lelang',
                'params' => $params,
            ]));
            Log::info($respons);
        } else {
            Log::warning('Gagal mengirim pesan, nomor telepon tidak ditemukan untuk user ID: ' . $userId);
        }
    }
}
