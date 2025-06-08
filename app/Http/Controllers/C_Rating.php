<?php

namespace App\Http\Controllers;

use App\Models\M_Rating;
use App\Models\M_Transaksi;
use Illuminate\Http\Request;

class C_Rating extends Controller
{
    public function getDetailTransaksi(string $id) {
        // Cari data lelang berdasarkan ID
        $transaksi = M_Transaksi::with('lelang.katalog')->findOrFail($id);
        // Periksa apakah rating sudah ada
        $rating = $transaksi->rating;
        return $this->showFormTambahRating($transaksi, $rating);
    }
    public function showFormTambahRating($transaksi, $rating) {
        return view('rating.V_FormTambahRating', compact('transaksi', 'rating'));
    }


    public function checkSkorRating(Request $request) {
        // Validasi request
        $validatedData = $request->validate([
            'rating' => 'required|integer|between:1,5', // harus angka antara 1 sampai 5
            'review' => 'nullable|string|max:255', // validasi untuk ulasan opsional
            'transaksi_id' => 'required|exists:transaksis,id', // validasi id transaksi
        ]);
        // Jika validasi berhasil, lanjut ke proses insert data
        return $this->insertDataRating($validatedData);
    }

    public function insertDataRating($request) {
        try {
            $transaksi = M_Transaksi::with('lelang')->find($request['transaksi_id']);
            M_Rating::updateOrCreate(
                ['transaksi_id' => $request['transaksi_id']],
                [
                    'rating' => $request['rating'],
                    'ulasan' => $request['review'],
                ]
            );

            // Kirim pesan sukses ke session
            return redirect()->route('katalog.show', ['id' => $transaksi->lelang->katalog_id] )->with('success', [
                    'title' => 'Sukses',
                    'message'  => 'Penilaian berhasil ditambahkan!'
                ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', [
                    'title' => 'Kesalahan Sistem',
                    'message'  => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
                ])->withInput();
        }
    }

    public function deleteDataRating($transaksi_id) {
        try {
            $rating = M_Rating::where('transaksi_id', $transaksi_id)->first();
            $rating->forceDelete();

            // Kirim pesan sukses ke session
            return redirect()->back()->with('success', [
                    'title' => 'Penilaian berhasil dibatalkan!',
                    'message'  => 'Silahkan beri nilai kembali kapan saja'
                ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', [
                    'title' => 'Kesalahan Sistem',
                    'message'  => 'Terjadi kesalahan saat manipulasi data: ' . $e->getMessage(),
                ])->withInput();
        }
    }
}

