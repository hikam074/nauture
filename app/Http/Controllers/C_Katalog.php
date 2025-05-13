<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use App\Models\M_Katalog;
use App\Models\M_Lelang;

class C_Katalog extends Controller
{
    public function getDataKatalog(Request $request)
    {
        $kategori = $request->get('kategori', 'active'); // Default 'all' jika tidak ada kategori.
        $sortBy = $request->get('sort_by', 'date_added'); // Default 'date_added' jika tidak ada sort.

        $query = M_Katalog::query();

        // Filter berdasarkan kategori
        if ($kategori === 'active') {
            $query->whereNull('deleted_at'); // Produk tersedia.
        } elseif ($kategori === 'deleted') {
            $query->onlyTrashed(); // Produk dihapus.
        } elseif ($kategori === 'all') {
            $query->withTrashed(); // semua.
        }

        // Logika sort berdasarkan pilihan
        if ($sortBy === 'date_added') {
            $query->orderBy('created_at', 'asc'); // Mengurutkan berdasarkan tanggal ditambahkan
        } elseif ($sortBy === 'alphabetical') {
            $query->orderBy('nama_produk', 'asc'); // Mengurutkan berdasarkan nama produk secara abjad
        }

        // kalau pagination
        $katalogs = $query->paginate(12)->appends($request->query());
        // kalau get all
        // $katalogs = $query->get();

        return $this->showDataKatalog($katalogs, $kategori, $sortBy);
    }

    public function showDataKatalog($katalogs, $kategori, $sortBy)
    {
        return view('katalog.V_HalamanKatalog', compact('katalogs', 'kategori', 'sortBy'));
    }




    public function getDetailDataKatalog(string $id)
    {
        // cari data katalog berdasarkan ID
        $katalog = M_Katalog::withTrashed()->findOrFail($id); // Akan melempar 404 jika ID tidak ditemukan
        // Cari data lelang yang memiliki katalog_id sama dengan katalog ini
        $lelangTerkaits = M_Lelang::where('katalog_id', $katalog->id)->take(5)->get();
        // teruskan data ke pengeksekusi show
        return $this->showDetailDatakatalog($katalog, $lelangTerkaits);
    }

    public function showDetailDatakatalog(M_Katalog $katalog, $lelangTerkaits = null)
    {
        // tampilkan view
        return view('katalog.V_HalamanDetailKatalog', ['katalog' => $katalog, 'lelangTerkaits' => $lelangTerkaits]);
    }




    public function showFormTambahKatalogProduk()
    {
        return view('katalog.V_FormTambahProdukKatalog');
    }

    public function checkInputNotNull(Request $request)
    {
        // aturan input
        $rules = [
            'nama_produk' => 'required|string|max:128',
            'deskripsi_produk' => 'nullable|string',
            'harga_perkilo' => 'required|integer',
            'foto_produk' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
        //pesan error validasi
        $pesan = [
            'nama_produk.required' => 'Nama produk harus diisi!',
            'harga_perkilo.required' => 'Harga perkilo harus diisi!',
            'foto_produk.required' => 'Foto produk harus diisi!',
        ];

        // Buat validasi manual
        $validator = Validator::make($request->all(), $rules, $pesan);

        // kalo gagal validasi
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else {
            // eksekusi simpan datanya
            return $this->insertDataKatalog($request);
        }
    }

    public function insertDataKatalog(Request $request)
    {
        try {
            // Simpan file foto ke dalam folder storage
            $fotoPath = $request->file('foto_produk')->store('katalogs', 'public');

            // Simpan data produk ke database
            M_Katalog::create([
                'nama_produk' => $request->nama_produk,
                'deskripsi_produk' => $request->deskripsi_produk,
                'harga_perkilo' => $request->harga_perkilo,
                'foto_produk' => $fotoPath,
            ]);

            // Kirim pesan sukses ke session
            return redirect()->route('katalog.index')->with('success', [
                    'title' => 'Sukses',
                    'message'  => 'Data produk katalog '.$request->nama_produk.' berhasil dibuat!'
                ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', [
                    'title' => 'Kesalahan Sistem',
                    'message'  => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
                ])->withInput();
        }
    }




    public function showFormUbahKatalog(string $id)
    {
        $katalog = M_Katalog::findOrFail($id);
        return view('katalog.V_FormTambahProdukKatalog', compact('katalog'));
    }

    public function klikSimpanPerubahan(Request $request, $id)
    {
        return response()->json([
            'title' => 'Simpan perubahan?',
            'text' => 'Apakah Anda yakin ingin menyimpan perubahan ini?',
            'icon' => 'warning',
            'confirmButtonText' => 'Simpan',
            'cancelButtonText' => 'Batal',
            'confirmUrl' => route('katalog.update', $id),
        ]);
    }

    public function checkUbahKatalog(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'nama_produk' => 'required|string|max:255',
                'deskripsi_produk' => 'nullable|string',
                'harga_perkilo' => 'required|integer',
                'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'selected_image' => 'nullable|string',
            ]);

            if (!$id) {
                return redirect()->back()->with('error', [
                    'title' => 'Data tidak valid!',
                    'message' => 'ID katalog tidak valid',
                ])->withInput();
            }
            else {
                return $this->updateDataLelang($request, $id);
            }

        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();

            return redirect()->back()->with('error', [
                'title' => 'Data tidak valid!',
                'message' => implode('<br>', $errors),
            ])->withInput();
        }
    }

    public function updateDataLelang(Request $request, string $id)
    {
        $katalog = M_Katalog::findOrFail($id);

        // kalau pakai gambar lama
        if ($request->input('selected_image') !== 'new')
            {}
        else
        {
            $basenameLama = basename($katalog->foto_produk);
            // Hapus foto lama jika ada
            if ($katalog->foto_produk && Storage::exists($katalog->foto_produk)) {
                Storage::delete($katalog->foto_produk);
            }

            // Simpan foto baru ke folder katalog
            $fotoPath = $request->file('foto_produk')->store('katalogs', 'public');
            $katalog->foto_produk = $fotoPath;

            // Ambil basename file untuk dicari di folder lelangs
            $basenameFoto = basename($fotoPath);

            // Hapus gambar lama di folder lelangs
            if (Storage::exists('lelangs/'. $basenameLama)) {
                Storage::delete('lelangs/'. $basenameLama);
            }

            // Salin gambar baru ke folder lelangs
            $destinationPath = 'lelangs/' . $basenameFoto;
            Storage::copy($fotoPath, $destinationPath);

            // Update path di database lelangs
            $lelangs = M_Lelang::where('foto_produk', 'like', "lelangs/$basenameLama")->get();
            foreach ($lelangs as $lelang) {
                $lelang->foto_produk = $destinationPath;
                $lelang->save();
            }
        }

        // Perbarui atribut lainnya
        $katalog->nama_produk = $request->input('nama_produk');
        $katalog->deskripsi_produk = $request->input('deskripsi_produk');
        $katalog->harga_perkilo = $request->input('harga_perkilo');

        // Simpan perubahan
        $katalog->save();

        return redirect()->route('katalog.index')->with('success', [
                'title' => 'Update berhasil!',
                'message' => 'Produk berhasil diperbarui',
        ]);
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




    // API
    public function getKatalog($id): JsonResponse
    {
        $katalog = M_Katalog::find($id);

        if ($katalog) {
            return response()->json([
                'id' => $katalog->id,
                'nama_produk' => $katalog->nama_produk,
                'foto_produk' => $katalog->foto_produk, // Pastikan kolom ini benar
            ]);
        } else {
            return response()->json(['error' => 'Katalog tidak ditemukan'], 404);
        }
    }
}
