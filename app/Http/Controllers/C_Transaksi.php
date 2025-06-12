<?php

namespace App\Http\Controllers;

use App\Models\M_Alamat;
use App\Models\M_City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\M_Lelang;
use App\Models\M_PasangLelang;
use App\Models\M_Transaksi;
use App\Models\M_StatusTransaksi;
use App\Models\User;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class C_Transaksi extends Controller
{
    public function checkBatasWaktuPembayaran(Request $request) {
        // dapatkan pasangLelangID
        $pasang_lelang_id = $request->input('pasang_lelang_id');
        // cari data pasangLelang berdasarkan ID diatas
        $pasang_lelang = M_PasangLelang::findOrFail($pasang_lelang_id);

        // hanya bisa buat transaksi 3 jam setelah dinyatakan menang
        // if ($pasang_lelang->waktu_dimenangkan && (now()->diffInHours($pasang_lelang->waktu_dimenangkan) < -3)) {
        if ($pasang_lelang->waktu_dimenangkan && (now()->diffInHours($pasang_lelang->waktu_dimenangkan) < -3)) {
            return redirect()->back()->with('error', [
                'title' => 'Gagal',
                'message' => 'Waktu pembayaran telah habis. Anda tidak dapat melakukan pembayaran lagi.',
            ]);
        }
        dd($request->all());
        // dd(now()->diffInHours($pasang_lelang->waktu_dimenangkan));
        return $this->insertDataTransaksi($request);
    }

    public function insertDataTransaksi(Request $request)
    {
        // dd($request->pasang_lelang_id);
        // dd($request->pasang_lelang_id);
        // dapatkan pasangLelangID
        $pasang_lelang_id = $request->input('pasang_lelang_id');
        // $pasang_lelang_id = $request->;
        // dd($request->pasang_lelang_id);

        // cari data pasangLelang berdasarkan ID diatas
        $pasang_lelang = M_PasangLelang::findOrFail($pasang_lelang_id);

        // cari data lelang berdasarkan pasangLelang
        $lelang = M_Lelang::findOrFail($pasang_lelang->lelang_id);
        // dd('cp');
        // ambil tenggat berdasarkan waktu dimenangkan
        // $tenggat = Carbon::parse(now())->addHours(3);
        $tenggat = Carbon::parse($pasang_lelang->waktu_dimenangkan)->addHours(3);
        $deadline = abs(intval($tenggat->diffInMinutes(Carbon::now())));
        // dd( $tenggat. '==='.intval($tenggat->diffInMinutes(Carbon::now())) .'======='.$deadline);
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

        // PEMBUATAN KODE TRANSAKSI

        // Ambil tanggal dan waktu saat ini
        $currentDateTime = Carbon::now()->format('Ymd-His');
        // Hitung jumlah kode transaksi yang telah dibuat untuk pasang_lelang_id tertentu
        $countKodeTransaksi = M_Transaksi::where('pasang_lelang_id', $pasang_lelang_id)
            ->whereNotNull('order_id') // Memastikan kode transaksi sudah digenerate
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
        $alamatDiProfil = Auth::user()->alamat_id;
        if (!$alamatDiProfil) {
            return redirect()->back()->with('error', [
                'title' => 'Anda Belum Mengisi Alamat',
                'message' =>  'Silahkan tambahkan alamat anda di menu Profil!'
            ]);
        }
        // buat instance alamat
        $cityNameUpper = strtoupper($cityName);

        $address = M_Alamat::create([
            'city_id' => M_City::where('nama_city', $cityNameUpper)->first()->id,
            'detail_alamat' => $detail_alamat,
            'kode_pos' => $postalCode,
        ]);

        // SIMPAN DATA TRANSAKSI
        $transaksi = new M_Transaksi([
            'order_id' => $kodeTransaksi,
            'lelang_id' => $lelang->id,
            'pasang_lelang_id' => $pasang_lelang_id,
            'gross_amount' => $harga_total,
            'alamat_id' => $address->id,
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
            'expiry' => [
                'start_time' => Carbon::now()->format('Y-m-d H:i:s O'),
                'duration' => $deadline,
                'unit' => 'minute',
            ],
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
            ),

        );

        Log::info('Midtrans Params:', $params);

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $transaksi->snap_token = $snapToken;
        $transaksi->save();

        return redirect()->back()->with('success', [
            'title' => 'Berhasil menginisiasi transaksi!',
            'message' =>  'Silahkan masuk ke tab transaksi untuk melanjutkan pembayaran'
        ]);
    }




    public function getDataTransaksiChekout($id) {
        $transaksi = M_Transaksi::with(['pasangLelang', 'lelang'])->findOrFail($id);
        if ($transaksi->status_transaksi_id != M_StatusTransaksi::where('kode_status_transaksi', 'pending')->first()->id) {
            return redirect()->back()->with('error', 'Transaksi ini sudah selesai atau dibatalkan.');
        }
        return $this->showHalamanChekout($id, $transaksi);
    }

    public function showHalamanChekout($id, $transaksi) {
        return view('transaksi.V_HalamanCheckout', compact('transaksi'));
    }




    public function getDataTransaksiUserIni() {
        $transaksis = M_Transaksi::with(['lelang', 'statusTransaksi', 'paymentMethod'])
            ->whereHas('pasangLelang', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->paginate(10);
        return $this->showDataTransaksiUserIni($transaksis);
    }
    public function showDataTransaksiUserIni($transaksis) {
        return view('dashboard.d-transaksi.V_HalamanTransaksiUser', compact('transaksis'));
    }




    public function getDataTransaksi() {
        $transaksis = M_Transaksi::with('pasangLelang', 'paymentMethod')->paginate(10);
        return $this->showDataTransaksi($transaksis);
    }
    public function showDataTransaksi($transaksis) {
        return view('dashboard.d-transaksi.V_HalamanSemuaTransaksi', compact('transaksis'));
    }




    public function updateStatusPengiriman(Request $request) {
        $transaksi = M_Transaksi::findOrFail($request->transaksi_id);
        // dd($transaksi->id.' '.$request->no_resi);
        $transaksi->no_resi = $request->no_resi;
        try {
            $transaksi->status_transaksi_id = M_StatusTransaksi::where('kode_status_transaksi', 'delivering')->first()->id;
            $transaksi->save();
            return redirect()->back()->with('success', 'Berhasil memperbarui status : sedang dikirim! ( Nomor Resi : '.$request->no_resi.' )');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateDeliverySelesai(Request $request) {
        $transaksi = M_Transaksi::where('order_id', $request->order_id)->first();
        try {
            $transaksi->status_transaksi_id = M_StatusTransaksi::where('kode_status_transaksi', 'delivered')->first()->id;
            $transaksi->save();
            return redirect()->back()->with('success', 'Barang telah anda terima! Jangan lupa memberi penilaian untuk barang kami!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
