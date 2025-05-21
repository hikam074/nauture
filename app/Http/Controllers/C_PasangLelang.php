<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\M_Lelang;
use App\Models\M_PasangLelang;
use App\Models\M_StatusTransaksi;
use App\Models\M_Transaksi;
use Exception;

class C_PasangLelang
{
    public function showFormPasangLelang() {
        if (Auth::check()) {
            return response()->json(['loggedIn' => true], 200);
        } else {
            return response()->json(['loggedIn' => false], 200);
            // return redirect()->back()->with('error', [
            //     'title' => 'Peringatan',
            //     'message'  => 'Data harus diisi lengkap!'
            // ]);
        }
    }

    // !!!WARNING
    // public function checkDataLengkap(int $id_lelang, int $harga) {
    // WARNING!!!
    public function checkDataLengkap(Request $request) {
        $validated = $request->validate([
            'lelang_id' => 'required|exists:lelangs,id',
            'harga_pengajuan' => 'required|numeric|min:0',
        ]);

        // Ambil harga minimal lelang dari database
        $lelang = M_Lelang::findOrFail($request->input('lelang_id'));
        $minimal = $lelang->harga_dibuka;
        // Ambil bid saat ini dari database
        $topBid = M_PasangLelang::where('lelang_id', $request->input('lelang_id'))->max('harga_pengajuan') ?? 0;

        // Validasi apakah harga lebih besar dari harga minimal
        if ($request->input('harga_pengajuan') < $minimal) {
            return redirect()->back()->with('error', [
                    'title' => 'Gagal',
                    'message'  => 'Harga yang diajukan harus lebih besar dari harga mulai!'
                ])->withInput();
        }
        // Validasi apakah harga lebih besar dari bid saat ini
        if ($request->input('harga_pengajuan') <= $topBid) {
            return redirect()->back()->with('error', [
                    'title' => 'Gagal',
                    'message'  => 'Harga yang diajukan harus lebih besar dari penawaran tertinggi saat ini!'
                ])->withInput();
        }
        // Validasi apakah harga kelipatan 10.000
        if ($request->input('harga_pengajuan') % 10000 !== 0) {
            return redirect()->back()->with('error', [
                    'title' => 'Gagal',
                    'message'  => 'Harga yang diajukan harus kelipatan Rp10.000!'
                ])->withInput();
        }

        if ($validated) {
            // data valid, lanjut menyimpan
            return $this->insertPasangLelang($validated);
        }

        return redirect()->back()->with('error', [
            'title' => 'Peringatan',
            'message'  => 'Data harus diisi lengkap!'
        ]);

    }

    public function insertPasangLelang($data) {
        // Gunakan updateOrCreate untuk cek data dan update/insert : komplit
        M_PasangLelang::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'lelang_id' => $data['lelang_id'],
            ],
            [
                'harga_pengajuan' => $data['harga_pengajuan'],
            ]
        );
        // session()->flash('success', 'Tawaran berhasil dipasang atau diperbarui! Pantau terus perkembangan lelang ini!');
        return redirect()->route('lelang.show', ['id' => $data['lelang_id']])->with('success', [
            'title' => 'Berhasil',
            'message'  => 'Tawaran berhasil dipasang atau diperbarui! Pantau terus perkembangan lelang ini!'
        ]);
    }




    public function showDataLelangUserIni()
    {
        $id = Auth::user()->id;
        $allBids = M_PasangLelang::with(['lelang', 'transaksi'])->get()->where('user_id', $id);
        // $thisTransaksi = M_Transaksi::findOrFail();

        $allBids->each(function ($bid) {
            if ($bid->lelang) {
                // Cari bider tertinggi berdasarkan harga pengajuan
                $topBid = $bid->lelang->pasangLelang->sortByDesc('harga_pengajuan')->first();
                $biderTertinggiId = $topBid ? $topBid->user_id : null;

                if (now()->lessThan($bid->lelang->tanggal_ditutup)) {
                    // Lelang masih berlangsung
                    if ($biderTertinggiId == $bid->user_id) {
                        $bid->status = 'Berlangsung, Penawar Tertinggi';
                    } else {
                        $bid->status = 'Berlangsung, BUKAN Penawar Tertinggi';
                    }
                } else {
                    // Lelang telah selesai
                    $selisihWaktu = now()->diffInHours($bid->lelang->tanggal_ditutup);
                    $settle = M_StatusTransaksi::where('kode_status_transaksi', 'settlement')->first()->id;
                    $capture = M_StatusTransaksi::where('kode_status_transaksi', 'capture')->first()->id;
                    $pending = M_StatusTransaksi::where('kode_status_transaksi', 'pending')->first()->id;

                    $transaksiIsSettlement = $bid->transaksi
                        ->where('pasang_lelang_id', $bid->id)
                        ->whereIn('status_transaksi_id', [$settle, $capture])
                        ->first();
                    $transaksiIsOngoing = $bid->transaksi
                        ->where('pasang_lelang_id', $bid->id)
                        ->where('status_transaksi_id', $pending)
                        ->first();

                    if ($bid->id == $bid->lelang->pemenang_id)
                    {
                        if ($transaksiIsSettlement) {
                            $bid->status = 'Menang, selesai dibayar';
                        } elseif ($selisihWaktu <= 3 && $transaksiIsOngoing) {
                            $bid->status = 'Menang, segera selesaikan pembayaran';
                        } elseif ($selisihWaktu <= 3 ) {
                            $bid->status = 'Menang, belum dibayar';
                        } else {
                            $bid->status = 'Dialihkan ke pemenang lain';
                        }
                    } else {
                        $bid->status = 'Kalah';
                    }
                }
            } else {
                // Tidak ada data lelang yang terkait
                $bid->status = 'TIDAK VALID';
            }
        });
        // reset index
        $allBids = $allBids->values();
        return view('dashboard.lelangAnda', compact('allBids'));
    }












    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     // Pastikan user login
    //     if (!Auth::check()) {
    //         return redirect()->route('login');
    //     }

    //     $lelang = M_Lelang::findOrFail($id);

    //     // Periksa apakah user sudah memiliki tawaran
    //     $existingBid = M_PasangLelang::where('user_id', Auth::id())
    //         ->where('lelang_id', $id)
    //         ->first();

    //     return view('lelang.show', compact('lelang', 'existingBid'));
    // }




    public function forceDelete(string $id)
    {
        // Cari lelang terkait
        $lelang = M_Lelang::where('id', $id)
            ->where('tanggal_ditutup', '>', now())
            ->first();

        // Jika lelang tidak ada atau sudah berakhir, beri respon error
        if (!$lelang) {
            return redirect()->back()->with('error', [
                    'title' => 'Gagal',
                    'message'  => 'Tawaran tidak dapat dihapus. Lelang sudah berakhir atau tidak valid!'
            ]);
        }

        // Validasi apakah lelang_id ada di database
        $pasangLelang = M_PasangLelang::where('user_id', Auth::id())
            ->where('lelang_id', $id)
            ->first();
        try {
            $pasangLelang->forceDelete();
            // Berikan notifikasi berhasil
            return redirect()->route('lelang.show', ['id' => $id])->with('success', [
                    'title' => 'Sukses',
                    'message'  => 'Tawaran berhasil dibatalkan!'
            ]);

        } catch (Exception $e) {
            // Jika data tidak ditemukan, beri respon error
            return redirect()->back()->with('error', [
                    'title' => 'Gagal',
                    'message'  => 'Tawaran tidak ditemukan atau tidak dapat dihapus!'
            ]);
        }
    }

}
