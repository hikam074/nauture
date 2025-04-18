<?php

namespace App\Http\Controllers;

use App\Models\M_Lelang;
use App\Models\M_Katalog;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class C_Lelang
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'active'); // Default 'active' jika tidak ada filter.
        $sortBy = $request->query('sort_by', 'date_added'); // Default 'date_added' jika tidak ada sort.
        $katalogIds = $request->get('katalog_id', []); // Filter berdasarkan banyak katalog_id yang dipilih.

        // Jika 'Semua Katalog' dipilih (katalog_id kosong), ambil semua katalog_id
        if (in_array('', $katalogIds)) {
            $katalogIds = M_Katalog::pluck('id')->toArray(); // Ambil semua katalog ID
        }

        $query = M_Lelang::with(['katalog', 'pasangLelang' => function ($q) {
            $q->select('lelang_id', DB::raw('MAX(harga_pengajuan) as highest_bid'));
        }]);


        // Filter berdasarkan status
        if ($filter === 'deleted') {
            $query->onlyTrashed()->whereNull('pemenang_id');
        } elseif ($filter === 'completed') {
            $query->onlyTrashed()->whereNotNull('pemenang_id');
        } elseif ($filter === 'active') {
            $query->whereNull('deleted_at');
        } elseif ($filter === 'all') {
            $query->withTrashed();
        } else {
            $query->whereNull('deleted_at'); // Default: Lelang yang masih berlangsung.
        }

        // Filter berdasarkan katalog (jika ada katalog yang dipilih)
        if (!empty($katalogIds)) {
            $query->whereIn('katalog_id', $katalogIds); // Menambahkan filter berdasarkan beberapa katalog_id
        }

        // Logika sort berdasarkan pilihan
        if ($sortBy === 'date_added') {
            $query->orderBy('created_at', 'asc'); // Mengurutkan berdasarkan tanggal ditambahkan
        } elseif ($sortBy === 'alphabetical') {
            $query->orderBy('nama_produk_lelang', 'asc'); // Mengurutkan berdasarkan nama produk secara abjad
        } elseif ($sortBy === 'highest_bid') {
            $query->orderBy('harga_dibuka', 'desc'); // Mengurutkan berdasarkan harga tertinggi
        }

        // Pagination dengan query string diteruskan
        $lelangs = $query->paginate(12)->appends($request->query());

        // Ambil semua katalog untuk filter dropdown
        $allKatalogs = M_Katalog::all(); // Ambil semua katalog

        return view('lelang.index', compact('lelangs', 'filter', 'sortBy', 'allKatalogs', 'katalogIds'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // pastikan sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Ambil semua data katalog
        $katalogs = M_Katalog::all();

        return view('lelang.add', compact('katalogs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk_lelang' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'jumlah_kg' => 'required|numeric|min:0',
            'harga_dibuka' => 'required|numeric|min:0',
            'tanggal_dibuka' => 'required|date|before:tanggal_ditutup',
            'tanggal_ditutup' => 'required|date|after:tanggal_dibuka',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'katalog_id' => 'required|exists:katalogs,id',
            'current_img' => 'nullable|string', // URL gambar jika dari katalog
        ]);

        // Hitung jumlah lelang hari ini untuk katalog tertentu
        $currentDate = Carbon::now()->format('Y-m-d');
        $lelangCountToday = M_Lelang::whereDate('created_at', $currentDate)
            ->where('katalog_id', $request->katalog_id)
            ->count();

        // Generate kode_lelang
        $kodeLelang = sprintf(
            "LEL-%s-%s-%d",
            $currentDate,
            $request->katalog_id,
            $lelangCountToday + 1
        );

        // Simpan file foto ke folder `lelangs`
        $fotoProdukPath = null;
        if ($request->hasFile('foto_produk')) {
            // Jika ada file upload, simpan langsung ke folder `lelangs`
            $fotoProdukPath = $request->file('foto_produk')->store('lelangs', 'public');
        } elseif ($request->current_img) { //dd($request->current_img);
            // Jika menggunakan gambar dari katalog, salin ke folder `lelangs`
            $imageUrl = str_replace(url('/storage'), '', $request->current_img); // Menghapus URL base jika ada
            $sourcePath = public_path('storage' . $imageUrl);
            if (file_exists($sourcePath)) {
                $newFileName = 'lelangs/' . uniqid() . '-' . basename($imageUrl);
                $destinationPath = public_path('storage/' . $newFileName);
                copy($sourcePath, $destinationPath);
                $fotoProdukPath = $newFileName; // Simpan path baru
            }
        }

        // Simpan data lelang
        $lelang = M_Lelang::create([
            'kode_lelang' => $kodeLelang,
            'nama_produk_lelang' => $request->nama_produk_lelang,
            'keterangan' => $request->keterangan,
            'jumlah_kg' =>$request->jumlah_kg,
            'harga_dibuka' => $request->harga_dibuka,
            'tanggal_dibuka' => $request->tanggal_dibuka,
            'tanggal_ditutup' => $request->tanggal_ditutup,
            'foto_produk' => $fotoProdukPath,
            'katalog_id' => $request->katalog_id,
        ]);

        return redirect()->route('lelang.index')
            ->with('success', 'Lelang berhasil ditambahkan dengan kode: ' . $kodeLelang);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Cari data lelang berdasarkan ID
        $lelang = M_Lelang::withTrashed()->findOrFail($id); // Akan melempar 404 jika ID tidak ditemukan

        // Kirim data ke view 'katalog.show'
        return view('lelang.show', ['lelang' => $lelang]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Ambil semua data katalog
        $katalogs = M_Katalog::all();

        $lelang = M_lelang::findOrFail($id);

        return view('lelang.add', compact('lelang', 'katalogs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Cari data lelang berdasarkan ID
        $lelang = M_Lelang::findOrFail($id);

        // Validasi data
        $request->validate([
            'nama_produk_lelang' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'harga_dibuka' => 'required|integer|min:1',
            'tanggal_dibuka' => [
                'required',
                'date',
                'before_or_equal:tanggal_ditutup',
                function ($attribute, $value, $fail) use ($lelang) {
                    if ($lelang->tanggal_dibuka < now()) {
                        $fail('Tanggal dibuka sudah jatuh tempo dan tidak dapat diubah.');
                    }
                }
            ],
            'tanggal_ditutup' => [
                'required',
                'date',
                'after_or_equal:tanggal_dibuka',
                function ($attribute, $value, $fail) use ($lelang) {
                    if ($lelang->tanggal_ditutup < now()) {
                        $fail('Tanggal ditutup sudah jatuh tempo dan tidak dapat diubah.');
                    }
                }
            ],
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'katalog_id' => 'nullable|exists:katalogs,id', // Validasi foreign key ke tabel katalog
        ]);

        // Update data lelang
        $lelang->nama_produk_lelang = $request->nama_produk_lelang;
        $lelang->keterangan = $request->keterangan;
        $lelang->harga_dibuka = $request->harga_dibuka;

        // Update tanggal hanya jika belum jatuh tempo
        if ($lelang->tanggal_dibuka >= now()) {
            $lelang->tanggal_dibuka = $request->tanggal_dibuka;
        }
        if ($lelang->tanggal_ditutup >= now()) {
            $lelang->tanggal_ditutup = $request->tanggal_ditutup;
        }

        $lelang->katalog_id = $request->katalog_id;

        // Proses file foto jika ada
        if ($request->hasFile('foto_produk')) {
            // Hapus file lama jika ada
            if ($lelang->foto_produk && Storage::exists('public/' . $lelang->foto_produk)) {
                Storage::delete('public/' . $lelang->foto_produk);
            }

            // Simpan file baru
            $filePath = $request->file('foto_produk')->store('lelangs', 'public');
            $lelang->foto_produk = $filePath;
        }

        // Simpan perubahan
        $lelang->save();

        // Redirect dengan pesan sukses
        return redirect()->route('lelang.index')->with('success', 'Lelang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lelang = M_Lelang::findOrFail($id);
        $lelang->delete(); // soft delete

        return redirect()->route('lelang.index')->with('success', 'Produk berhasil dihapus.');
    }

    // Menampilkan produk yang terhapus
    public function trashed()
    {
        $lelang = M_Lelang::onlyTrashed()->get(); // Ambil produk yang dihapus
        return view('products.trashed', compact('products'));
    }

    // Mengembalikan produk yang dihapus
    public function restore($id)
    {
        if (!$id) {
            return redirect()->route('lelang.index')->with('error', 'ID tidak ditemukan.');
        }

        $lelang = M_Lelang::onlyTrashed()->findOrFail($id);
        $lelang->restore(); // Mengembalikan produk

        return redirect()->route('lelang.index')->with('success', 'Produk berhasil dikembalikan.');
    }

}
