<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\M_Lelang;
use App\Models\M_PasangLelang;
use App\Models\M_Transaksi;
use App\Models\M_StatusTransaksi;
use App\Models\User;

use Illuminate\Support\Carbon;

class C_Transaksi extends Controller
{
    public function createTransaksi(Request $request)
    {
        // dapatkan pasangLelangID
        $pasang_lelang_id = $request->input('pasang_lelang_id');
        // cari data pasangLelang berdasarkan ID diatas
        $pasang_lelang = M_PasangLelang::findOrFail($pasang_lelang_id);
        // cari data lelang berdasarkan pasangLelang
        $lelang = M_Lelang::findOrFail($pasang_lelang->lelang_id);
        // ambil input ongkir
        $ongkir = $request->input('ongkir');
        // calc harga total
        $harga_total = $ongkir + $pasang_lelang->harga_pengajuan;
        // ambil user
        $user = User::find(Auth::id());

        // decode alamat
        $destinationJson = $request->input('destinationJson');
        $destinationData = json_decode($destinationJson, true);
        // slicing
        $province = $destinationData['province_name'];
        $cityName = $destinationData['city_name'];
        $districtName = $destinationData['district_name'];
        $subdistrictName = $destinationData['subdistrict_name'];
        $postalCode = $destinationData['zip_code'];
        // ambil input detail
        $detail_alamat = $request->input('detail_alamat');

        // hanya bisa buat transaksi 3 jam setelah dinyatakan menang
        if ($pasang_lelang->waktu_dimenangkan && now()->diffInHours($pasang_lelang->waktu_dimenangkan) > 3) {
            return redirect()->back()->with('error', [
                'title' => 'Gagal',
                'message' => 'Waktu pembayaran telah habis. Anda tidak dapat melakukan pembayaran lagi.',
            ]);
        }

        // PEMBUATAN KODE TRANSAKSI

        // Ambil tanggal dan waktu saat ini
        $currentDateTime = Carbon::now()->format('Ymd-His');
        // Hitung jumlah kode transaksi yang telah dibuat untuk pasang_lelang_id tertentu
        $countKodeTransaksi = M_Transaksi::where('pasang_lelang_id', $pasang_lelang_id)
            ->whereNotNull('kode_transaksi') // Memastikan kode transaksi sudah digenerate
            ->count();
        // Generate kode transaksi
        $kodeTransaksi = sprintf(
            "NAU-%s-%d-%d-%d",
            $currentDateTime,
            $lelang->id,
            $pasang_lelang_id,
            $countKodeTransaksi + 1
        );

        // VALIDASI ALAMAT
        $alamatDiProfil = Auth::user()->alamat;
        if (!$alamatDiProfil) {
            return redirect()->back()->with('error', [
                'title' => 'Anda Belum Mengisi Alamat',
                'message' =>  'Silahkan tambahkan alamat anda di menu Profil!'
            ]);
        }

        // SIMPAN DATA TRANSAKSI
        $transaksi = new M_Transaksi([
            'order_id' => $kodeTransaksi,
            'lelang_id' => $lelang->id,
            'pasang_lelang_id' => $pasang_lelang_id,
            'gross_amount' => $harga_total,
            'alamat' => $detail_alamat,
            'status_transaksi_id' => M_StatusTransaksi::where('kode_status_transaksi', 'pending')->first()->id,
        ]);

        // DAPATKAN SNAP TOKEN UNTUK MIDTRANS

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');

        $params = array(
            'transaction_details' => array(
                'order_id' => $transaksi->order_id,
                'gross_amount' => $transaksi->gross_amount,
            ),
            'items_details' => array(
                "id" => $lelang->kode_lelang,
                "price" => $harga_total,
                "quantity" => 1,
                "name" => $lelang->nama_lelang,
                "brand" => "NauTure",
                "category" => $lelang->katalog->nama_produk,
                "merchant_name" => "NauTure",
                "url" => "https://subtle-mantis-actually.ngrok-free.app/lelang/{$lelang->id}"
            ),
            'customer_details' => array(
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->no_telp,
                'billing_address' => array(
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->no_telp,
                    'address' => $user->alamat,
                ),
                'shipping_address' => array(
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->no_telp,
                    'address' => $detail_alamat,
                    'city' => $cityName,
                    'postal_code' => $postalCode,
                    'country_code' => "IDN"
                )
            )
        );

        // dd($request->all());

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $transaksi->snap_token = $snapToken;
        $transaksi->save();

        // return redirect()->route('transaksi.checkout', ['id' => $transaksi->id]);
        return redirect()->back()->with('success', [
            'title' => 'Berhasil menginisiasi transaksi!',
            'message' =>  'Silahkan masuk ke tab transaksi untuk melanjutkan pembayaran'
        ]);
    }

    public function showHalamanChekout($id) {
        $transaksi = M_Transaksi::findOrFail($id);
        if ($transaksi->status_transaksi_id != M_StatusTransaksi::where('kode_status_transaksi', 'pending')->first()->id) {
            return redirect()->back()->with('error', 'Transaksi ini sudah selesai atau dibatalkan.');
        }
        return view('transaksi.bayar', compact('transaksi'));
    }

    public function showHalamanSukses($id) {
        $transaksi = M_Transaksi::findOrFail($id);
        // $transaksi->status_transaksi_id = M_StatusTransaksi::where('kode_status_transaksi', 'settlement')->first()->id;
        // $transaksi->save();
        return view('transaksi.success', compact('transaksi'));
    }

    public function showDataTransaksiUserIni() {
        $user = User::find(Auth::id());
        $transaksis = M_Transaksi::with(['lelang', 'statusTransaksi'])
            ->whereHas('pasangLelang', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();
        return view('dashboard.transaksi', compact('transaksis'));
    }




}
