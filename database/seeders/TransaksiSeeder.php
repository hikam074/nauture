<?php

namespace Database\Seeders;

use App\Models\M_Alamat;
use App\Models\M_City;
use App\Models\M_Lelang;
use App\Models\M_PaymentMethod;
use App\Models\M_Saldo;
use App\Models\M_StatusTransaksi;
use App\Models\M_Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Midtrans;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusDeliveredId = M_StatusTransaksi::where('kode_status_transaksi', 'delivered')->first()->id;
        $lelangsSelesai = M_Lelang::with('katalog')->whereNotNull('pemenang_id')->get();
        $saldo = M_Saldo::find(1);

        foreach ($lelangsSelesai as $lelang) {
            $pemenang = $lelang->pemenang;

            if (!$pemenang) {
                continue;
            }

            $userPemenang = User::find($pemenang->user_id);
            if (!$userPemenang) {
                continue;
            }

            if (!$userPemenang->alamat_id) {
                $alamatBaru = M_Alamat::create([
                    'city_id'       => M_City::inRandomOrder()->first()->id,
                    'detail_alamat' => 'Jl. Seeder No. ' . rand(1, 100) . ', Kecamatan Seeder, Kota Seeder',
                    'kode_pos'      => rand(10000, 99999)
                ]);
                $userPemenang->alamat_id = $alamatBaru->id;
                $userPemenang->save();
            }

            $ongkir = rand(10, 50) * 1000;
            $gross_amount = $pemenang->harga_pengajuan + $ongkir;

            $kodeTransaksi = sprintf(
                "NAU-%s-%d-%d-1",
                Carbon::now()->format('Ymd-His'),
                $lelang->id,
                $pemenang->id
            );

            // --- Simulasi Snap Token ---
            $snapToken = md5($kodeTransaksi . time());
            // --- Akhir Simulasi ---

            $transaksi = M_Transaksi::updateOrCreate(
                ['pasang_lelang_id' => $pemenang->id],
                [
                    'order_id'            => $kodeTransaksi,
                    'lelang_id'           => $lelang->id,
                    'gross_amount'        => $gross_amount,
                    'alamat_id'           => $userPemenang->alamat_id,
                    'snap_token'          => $snapToken,
                    'status_transaksi_id' => $statusDeliveredId,
                    'payment_method_id'   => M_PaymentMethod::inRandomOrder()->first()->id,
                    'payment_time'        => Carbon::parse($lelang->tanggal_ditutup)->addHours(1),
                    'no_resi'             => 'NAU' . rand(100000000, 999999999),
                ]
            );

            $waktuSelesaiLelang = Carbon::parse($lelang->tanggal_ditutup);
            $transaksi->created_at = $waktuSelesaiLelang;
            $transaksi->updated_at = $waktuSelesaiLelang;
            $transaksi->save();

            if ($saldo) {
                $saldo->increment('saldo', $gross_amount);
            }
        }
    }
}
