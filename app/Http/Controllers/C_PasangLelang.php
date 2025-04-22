<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\M_Lelang;
use App\Models\M_PasangLelang;
use Exception;

class C_PasangLelang
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
        // pastikan sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // // Ambil semua data katalog
        // $lelang = M_Lelang::FindOrFail($id);

        // return view('lelang.form', compact('lelang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lelang_id' => 'required|exists:lelangs,id',
            'harga_pengajuan' => 'required|numeric|min:0',
        ]);

        // Gunakan updateOrCreate untuk cek data dan update/insert
        M_PasangLelang::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'lelang_id' => $validated['lelang_id'],
            ],
            [
                'harga_pengajuan' => $validated['harga_pengajuan'],
            ]
        );

        $request->session()->flash('success', 'Tawaran berhasil dipasang atau diperbarui! Pantau terus perkembangan lelang ini!');
        return redirect()->route('lelang.show', ['id' => $validated['lelang_id']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Pastikan user login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $lelang = M_Lelang::findOrFail($id);

        // Periksa apakah user sudah memiliki tawaran
        $existingBid = M_PasangLelang::where('user_id', Auth::id())
            ->where('lelang_id', $id)
            ->first();

        return view('lelang.show', compact('lelang', 'existingBid'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }

    public function forceDelete(string $id)
    {
        // Cari lelang terkait
        $lelang = M_Lelang::where('id', $id)
            ->where('tanggal_ditutup', '>', now())
            ->first();

        if (!$lelang) {
            // Jika lelang tidak ada atau sudah berakhir, beri respon error
            return redirect()->back()
                ->withErrors(['error' => 'Tawaran tidak dapat dihapus. Lelang sudah berakhir atau tidak valid.']);
        }

        // Validasi apakah lelang_id ada di database
        $pasangLelang = M_PasangLelang::where('user_id', Auth::id())
            ->where('lelang_id', $id)
            ->first();

        try {
            $pasangLelang->forceDelete();
            // Berikan notifikasi berhasil
            session()->flash('success', 'Tawaran berhasil dibatalkan!');
            return redirect()->route('lelang.show', ['id' => $id]);

        } catch (Exception $e) {
            // Jika data tidak ditemukan, beri respon error
            return redirect()->back()
                ->withErrors(['error' => 'Tawaran tidak ditemukan atau tidak dapat dihapus.']);
        }
    }

}
