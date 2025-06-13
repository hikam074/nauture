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
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Midtrans;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Konfigurasi Midtrans di awal
        Midtrans\Config::$serverKey = config('midtrans.serverKey');
        Midtrans\Config::$isProduction = config('midtrans.isProduction', false);
        Midtrans\Config::$isSanitized = config('midtrans.isSanitized', true);
        Midtrans\Config::$is3ds = config('midtrans.is3ds', true);

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
            // Muat ulang relasi alamat untuk mendapatkan data lengkap
            $userPemenang->load('alamat.city.provinsi');

            $ongkir = rand(10, 50) * 1000;
            $gross_amount = $pemenang->harga_pengajuan + $ongkir;

            $kodeTransaksi = sprintf(
                "NAU-%s-%d-%d-1",
                Carbon::now()->format('Ymd-His'),
                $lelang->id,
                $pemenang->id
            );

            // --- Logika Pembuatan Snap Token (Duplikasi dari Controller) ---
            $tenggat = Carbon::parse($pemenang->waktu_dimenangkan)->addHours(3);
            $deadline = abs(intval($tenggat->diffInMinutes(Carbon::now())));

            $params = [
                'transaction_details' => [
                    'order_id' => $kodeTransaksi,
                    'gross_amount' => $gross_amount,
                ],
                'expiry' => [
                    'start_time' => Carbon::now()->format('Y-m-d H:i:s O'),
                    'duration' => $deadline > 0 ? $deadline : 1, // Pastikan durasi minimal 1 menit
                    'unit' => 'minute',
                ],
                'item_details' => [[
                    "id" => $lelang->kode_lelang,
                    "price" => $gross_amount,
                    "quantity" => 1,
                    "name" => $lelang->nama_produk_lelang,
                    "brand" => "NauTure",
                    "category" => $lelang->katalog->nama_produk,
                    "merchant_name" => "NauTure",
                    "url" => route('lelang.show', ['id' => $lelang->id])
                ]],
                'customer_details' => [
                    'first_name' => $userPemenang->name,
                    'email' => $userPemenang->email,
                    'phone' => $userPemenang->no_telp,
                    'shipping_address' => [
                        'first_name' => $userPemenang->name,
                        'email' => $userPemenang->email,
                        'phone' => $userPemenang->no_telp,
                        'address' => $userPemenang->alamat->detail_alamat,
                        'city' => $userPemenang->alamat->city->nama_city,
                        'postal_code' => $userPemenang->alamat->kode_pos,
                        'country_code' => "IDN"
                    ]
                ],
            ];

            // Dapatkan snap_token
            $snapToken = Midtrans\Snap::getSnapToken($params);
            // --- Akhir Logika Snap Token ---

            $transaksi = M_Transaksi::updateOrCreate(
                ['pasang_lelang_id' => $pemenang->id],
                [
                    'order_id'            => $kodeTransaksi,
                    'lelang_id'           => $lelang->id,
                    'gross_amount'        => $gross_amount,
                    'alamat_id'           => $userPemenang->alamat_id,
                    'snap_token'          => $snapToken, // <-- TAMBAHKAN SNAP TOKEN DI SINI
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
