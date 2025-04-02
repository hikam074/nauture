<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\M_Katalog;

class C_Katalog extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $katalogs = M_Katalog::all();
        return view('katalog.index', ['katalogs' => $katalogs]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        dd(Auth::check());
        if (!Auth::check()) {
              dd('Login berhasil, redirecting to katalog...');  // Debugging point
            return redirect()->route('login');
            return view('katalog.add');
        }
        return view('katalog.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi_produk' => 'nullable|string',
            'harga_perkilo' => 'required|integer',
            'foto_produk' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Simpan foto ke folder 'katalogs' di dalam storage
            $fotoPath = $request->file('foto_produk')->store('katalogs', 'public');

            // Simpan data produk ke database
            \App\Models\M_Katalog::create([
                'nama_produk' => $request->nama_produk,
                'deskripsi_produk' => $request->deskripsi_produk,
                'harga_perkilo' => $request->harga_perkilo,
                'foto_produk' => $fotoPath,
            ]);
            // berhasil disimpan
            session()->flash('success', 'Produk berhasil ditambahkan!');
            return redirect('/katalog');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan produk!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Cari data katalog berdasarkan ID
        $katalog = \App\Models\M_Katalog::findOrFail($id); // Akan melempar 404 jika ID tidak ditemukan

        // Kirim data ke view 'katalog.show'
        return view('katalog.show', ['katalog' => $katalog]);
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
        //
    }
}
