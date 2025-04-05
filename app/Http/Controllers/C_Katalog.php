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
    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $query = M_katalog::query();

        if ($filter === 'deleted') {
            $query->onlyTrashed(); // produk yang dihapus
        } elseif ($filter === 'all') {
            $query->withTrashed(); // produk aktif dan dihapus
        } else {
            // Default: produk belum dihapus
            $query->whereNull('deleted_at');
        }

        $katalogs = $query->paginate(10);

        return view('katalog.index', compact('katalogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
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
            M_Katalog::create([
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
        $katalog = M_Katalog::withTrashed()->findOrFail($id); // Akan melempar 404 jika ID tidak ditemukan

        // Kirim data ke view 'katalog.show'
        return view('katalog.show', ['katalog' => $katalog]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $katalog = M_Katalog::findOrFail($id);
        return view('katalog.add', compact('katalog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi_produk' => 'nullable|string',
            'harga_perkilo' => 'required|integer',
            'foto_produk' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $katalog = M_Katalog::findOrFail($id);
        $katalog->update($request->all());

        return redirect()->route('katalog.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $katalog = M_Katalog::findOrFail($id);
        $katalog->delete(); // soft delete

        return redirect()->route('katalog.index')->with('success', 'Produk berhasil dihapus.');
    }

    // Menampilkan produk yang terhapus
    public function trashed()
    {
        $katalog = M_Katalog::onlyTrashed()->get(); // Ambil produk yang dihapus
        return view('products.trashed', compact('products'));
    }

    // Mengembalikan produk yang dihapus
    public function restore($id)
    {
        if (!$id) {
            return redirect()->route('katalog.index')->with('error', 'ID tidak ditemukan.');
        }

        $katalog = M_Katalog::onlyTrashed()->findOrFail($id);
        $katalog->restore(); // Mengembalikan produk

        return redirect()->route('katalog.index')->with('success', 'Produk berhasil dikembalikan.');
    }

    // Menghapus permanen produk
    public function forceDelete($id)
    {
        $katalog = M_Katalog::onlyTrashed()->findOrFail($id);
        $katalog->forceDelete(); // Hapus permanen

        return redirect()->route('katalog.index')->with('success', 'Produk berhasil dihapus permanen.');
    }
}
