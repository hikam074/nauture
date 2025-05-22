<?php

namespace App\Http\Controllers;

use App\Models\M_Lelang;
use App\Models\M_PasangLelang;
use App\Models\M_Katalog;
use App\Models\M_Transaksi;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class C_Lelang
{
    protected $notifController;
    public function __construct(C_Notification $notifController)
    {
        $this->notifController = $notifController;
    }

    public function getDataLelang(Request $request)
    {
        $filter = $request->query('filter', 'active'); // Default 'active' jika tidak ada filter.
        $sortBy = $request->query('sort_by', 'date_added'); // Default 'date_added' jika tidak ada sort.
        $katalogIds = $request->get('katalog_id', []); // Filter berdasarkan banyak katalog_id yang dipilih.
        $search = $request->query('search', '');

        // Jika 'Semua Katalog' dipilih (katalog_id kosong), ambil semua katalog_id
        if (in_array('', $katalogIds)) {
            $katalogIds = M_Katalog::pluck('id')->toArray(); // Ambil semua katalog ID
        }

        $query = M_Lelang::with(['katalog', 'pasangLelang']);

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

        // Ambil data di tabel `pasang_lelangs` jika ada
        $query->with('pasangLelang');

        // Filter berdasarkan katalog (jika ada katalog yang dipilih)
        if (!empty($katalogIds)) {
            $query->whereIn('katalog_id', $katalogIds); // Menambahkan filter berdasarkan beberapa katalog_id
        }

        if (!empty($search)) {
            // Filter berdasarkan nama produk atau deskripsi
            $query->where('nama_produk_lelang', 'LIKE', "%$search%")
                ->orWhere('deskripsi_produk', 'LIKE', "%$search%");
        }

        // Logika sort berdasarkan pilihan
        if ($sortBy === 'date_added') {
            $query->orderBy('created_at', 'asc'); // Mengurutkan berdasarkan tanggal ditambahkan
        } elseif ($sortBy === 'alphabetical') {
            $query->orderBy('nama_produk_lelang', 'asc'); // Mengurutkan berdasarkan nama produk secara abjad
        } elseif ($sortBy === 'highest_bid') {
            $query->orderBy('harga_dibuka', 'desc'); // Mengurutkan berdasarkan harga tertinggi
        }

        // kalo pagination
        $lelangs = $query->paginate(12)->appends($request->query());
        // kalo get all
        // $lelangs = $query->get();

        // Ambil semua katalog untuk filter dropdown
        $allKatalogs = M_Katalog::all(); // Ambil semua katalog

        return $this->showDataLelang($lelangs, $filter, $sortBy, $allKatalogs, $katalogIds);
    }

    public function showDataLelang($lelangs, $filter, $sortBy, $allKatalogs, $katalogIds)
    {
        return view('lelang.V_HalamanLelang', compact('lelangs', 'filter', 'sortBy', 'allKatalogs', 'katalogIds'));
    }




    public function getDetailDataLelang(string $id)
    {
        // Cari data lelang berdasarkan ID
        $lelang = M_Lelang::withTrashed()->findOrFail($id); // Akan melempar 404 jika ID tidak ditemukan
        // cari apakah user yang logged sudah nge bid
        $userBids = $lelang->pasangLelang->where('user_id', Auth::id())->first();
        // cari bid an tertinggi
        $topBid = $lelang->pasangLelang->sortByDesc('harga_pengajuan')->first();

        // if ($lelang->pemenang_id) {
        //     // ambil data dari transaksi
        //     $pemenang = M_Transaksi::findOrFail($id);
        //     return $this->showDetailDataLelang($lelang, $userBids, $topBid, $pemenang);
        // }

        // teruskan data ke pengeksekusi show
        return $this->showDetailDataLelang($lelang, $userBids, $topBid);
    }

    public function showDetailDataLelang(M_Lelang $lelang, $userBids = null, $topBid = null, $pemenang = null)
    {
        return view('lelang.V_HalamanDetailLelang', ['lelang' => $lelang, 'userBids' => $userBids, 'topBid' => $topBid, 'pemenang' => $pemenang]);
    }




    public function showFormTambahLelang()
    {
        // Ambil semua data katalog
        $katalogs = M_Katalog::all();

        return view('lelang.V_FormTambahLelang', compact('katalogs'));
    }

    public function checkDataLengkap(Request $request)
    {
        $rules = [
            'nama_produk_lelang' => 'required|string|max:128',
            'keterangan' => 'nullable|string',
            'jumlah_kg' => 'required|numeric|min:0',
            'harga_dibuka' => 'required|numeric|min:0',
            'tanggal_dibuka' => 'required|date',
            'tanggal_ditutup' => 'required|date',
            'waktu_dibuka' => [
                'required',
                'regex:/^([01]\d|2[0-3]):00$/',
                ],
            'waktu_ditutup' => [
                    'required',
                    'regex:/^([01]\d|2[0-3]):00$/',
                ],
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'katalog_id' => 'required|exists:katalogs,id',
            'current_img' => 'nullable|string', // URL gambar jika dari katalog
            'selected_image' => 'required|string', // validasi terpilih
        ];
        $pesan = [
            'nama_produk_lelang.required' => 'Nama produk lelang wajib diisi!',
            'jumlah_kg.required' => 'Jumlah kilogram wajib diisi!',
            'harga_dibuka.required' => 'Harga dibuka wajib diisi!',
            'tanggal_dibuka.required' => 'Tanggal dibuka wajib diisi!',
            'waktu_dibuka.regex' => 'Waktu dibuka harus dalam format HH:00!',
            'tanggal_ditutup.required' => 'Tanggal ditutup wajib diisi!',
            'waktu_ditutup.regex' => 'Waktu ditutup harus dalam format HH:00!',
            'katalog_id.required' => 'Katalog produk wajib diisi!',
            'katalog_id.exists' => 'Katalog yang dipilih tidak valid!',
        ];

        // Ambil data tanggal dan waktu
        $tanggalDibuka = $request->input('tanggal_dibuka');
        $tanggalDitutup = $request->input('tanggal_ditutup');
        $waktuDibuka = $request->input('waktu_dibuka');
        $waktuDitutup = $request->input('waktu_ditutup');

        // Cek jika tanggal buka dan tutupnya sama
        if ($tanggalDibuka == $tanggalDitutup) {
            // Gabungkan tanggal dan waktu untuk membandingkan dengan waktu sekarang
            $tanggalWaktuDibuka = Carbon::parse("$tanggalDibuka $waktuDibuka");
            $tanggalWaktuDitutup = Carbon::parse("$tanggalDitutup $waktuDitutup");

            // Pastikan waktu dibuka lebih dari waktu sekarang
            if ($tanggalWaktuDibuka <= Carbon::now()) {
                return redirect()->back()->with('error', [
                    'title' => 'Gagal',
                    'message' => 'Waktu dibuka harus lebih besar dari waktu sekarang!'
                ])->withInput();
            }

            // Pastikan waktu tutup lebih besar dari waktu buka
            if ($tanggalWaktuDitutup <= $tanggalWaktuDibuka) {
                return redirect()->back()->with('error', [
                    'title' => 'Gagal',
                    'message' => 'Waktu tutup harus lebih besar dari waktu buka!'
                ])->withInput();
            }
        }

        // Buat validasi manual
        $validator = Validator::make($request->all(), $rules, $pesan);

        // kalo gagal validasi
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validasi apakah harga kelipatan 10.000
        if ($request->input('harga_dibuka') % 10000 !== 0) {
            return redirect()->back()->with('error', [
                'title' => 'Gagal',
                'message'  => 'Harga yang ditetapkan harus kelipatan Rp10.000!'
            ])->withInput();
        }
        else {
            // eksekusi simpan datanya
            return $this->insertDataLelang($request);
        }
    }

    public function insertDataLelang(Request $request) {
        try {
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

            // dd($request->current_img);
            // dd($request->selected_image);

            // Simpan file foto ke folder `lelangs`
            $fotoProdukPath = null;
            if ($request->hasFile('foto_produk') && $request->selected_image == 'foto unggahan baru') {
                // Jika ada file upload, simpan langsung ke folder `lelangs`
                $fotoProdukPath = $request->file('foto_produk')->store('lelangs', 'public');
            // } elseif ($request->current_img  && $request->selected_image == 'foto dari katalog') {
            } else {
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

            //gabungkan date dan time inputan
            $datetime_dibuka = $request->input('tanggal_dibuka') . ' ' . $request->input('waktu_dibuka') . ':00';
            $datetime_ditutup = $request->input('tanggal_ditutup') . ' ' . $request->input('waktu_ditutup') . ':00';

            // Simpan data lelang
            $lelang = M_Lelang::create([
                'kode_lelang' => $kodeLelang,
                'nama_produk_lelang' => $request->nama_produk_lelang,
                'keterangan' => $request->keterangan,
                'jumlah_kg' =>$request->jumlah_kg,
                'harga_dibuka' => $request->harga_dibuka,
                'tanggal_dibuka' => $datetime_dibuka,
                'tanggal_ditutup' => $datetime_ditutup,
                'foto_produk' => $fotoProdukPath,
                'katalog_id' => $request->katalog_id,
            ]);

            if($lelang) {
                $judul = 'LELANG BARU DIBUKA!';
                $pesan = 'Lelang '.$lelang->nama_produk_lelang.' mulai dari '.$lelang->harga_dibuka.'! ditutup : '.$lelang->tanggal_ditutup;
                $url = config('onesignal.this_app_url').'/lelang/'.$lelang->id;
                $this->notifController->sendNotification($judul, $pesan, $url);
            }

            return redirect()->route('lelang.index')->with('success', [
                    'title' => 'Berhasil',
                    'message'  => 'Lelang berhasil ditambahkan dengan kode: ' . $kodeLelang
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', [
                    'title' => 'Kesalahan Sistem',
                    'message'  => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
                ])->withInput();
        }
    }




    public function showFormUbahProdukLelang(string $id)
    {
        // Ambil semua data katalog buat dropdown
        $katalogs = M_Katalog::all();

        $lelang = M_lelang::findOrFail($id);

        // Pisahkan tanggal dan waktu untuk 'tanggal_dibuka' dan 'tanggal_ditutup'
        $tanggalDibuka = $lelang->tanggal_dibuka ? Carbon::parse($lelang->tanggal_dibuka)->format('Y-m-d') : null;
        $lelang->waktu_dibuka = $lelang->tanggal_dibuka ? Carbon::parse($lelang->tanggal_dibuka)->format('H:i') : null;

        $tanggalDitutup = $lelang->tanggal_ditutup ? Carbon::parse($lelang->tanggal_ditutup)->format('Y-m-d') : null;
        $lelang->waktu_ditutup = $lelang->tanggal_ditutup ? Carbon::parse($lelang->tanggal_ditutup)->format('H:i') : null;

        return view('lelang.V_FormTambahLelang', compact('lelang', 'katalogs', 'tanggalDibuka', 'tanggalDitutup'));
    }

    public function klikSimpanPerubahan(Request $request, $id)
    {
        return response()->json([
            'title' => 'Simpan perubahan?',
            'text' => 'Apakah Anda yakin ingin menyimpan perubahan ini?',
            'icon' => 'warning',
            'confirmButtonText' => 'Simpan',
            'cancelButtonText' => 'Batal',
            'confirmUrl' => route('lelang.update', $id),
        ]);
    }

    public function checkUbahDataLelang(Request $request)
    {
        // Cari data lelang berdasarkan ID
        $lelang = M_Lelang::findOrFail($request->lelang_id);
        // dd($lelang->lelang_id);
        if (!$lelang) {
            return back()->with('error', [
                    'title' => 'Gagal',
                    'message'  => 'ID lelang tidak ditemukan'
                ])->withInput();
        }

        // Validasi tanggal dan waktu dibuka
        $tanggalDibuka = $request->input('tanggal_dibuka');
        $waktuDibuka = $request->input('waktu_dibuka');
        $tanggalDitutup = $request->input('tanggal_ditutup');
        $waktuDitutup = $request->input('waktu_ditutup');

        // Combine tanggal dan waktu menjadi format datetime
        $datetimeDibuka = $tanggalDibuka ? Carbon::createFromFormat('Y-m-d H:i', "$tanggalDibuka $waktuDibuka") : null;
        $datetimeDitutup = $tanggalDitutup ? Carbon::createFromFormat('Y-m-d H:i', "$tanggalDitutup $waktuDitutup") : null;

        // Validasi tanggal dibuka
        if ($datetimeDibuka && $datetimeDibuka->isPast()) {
            return back()->with('error', [
                    'title' => 'Tanggal waktu dibuka tidak valid!',
                    'message'  => 'Tanggal & waktu dibuka tidak boleh di masa lalu'
                ])->withInput();
        }

        // Validasi waktu dibuka (hanya jika tanggal dibuka hari ini)
        if ($datetimeDibuka && $datetimeDibuka->isToday() && $datetimeDibuka->format('H:i') < now()->format('H:i')) {
            return back()->with('error', [
                    'title' => 'Tanggal waktu dibuka tidak valid!',
                    'message'  => 'Waktu dibuka tidak boleh kurang dari waktu saat ini'
                ])->withInput();
        }

        // Validasi tanggal ditutup
        if ($datetimeDitutup && $datetimeDitutup->isPast()) {
            return back()->with('error', [
                    'title' => 'Tanggal waktu ditutup tidak valid!',
                    'message'  => 'Tanggal & waktu ditutup tidak boleh di masa lalu'
                ])->withInput();
        }

        // Validasi tanggal ditutup setelah atau sama dengan tanggal dibuka
        if ($datetimeDitutup && $datetimeDibuka && $datetimeDitutup->lessThan($datetimeDibuka)) {
            return back()->with('error', [
                    'title' => 'Tanggal waktu ditutup tidak valid!',
                    'message'  => 'Tanggal & waktu ditutup harus setelah tanggal dan waktu dibuka'
                ])->withInput();
        }

        // Validasi waktu ditutup (jika tanggal ditutup sama dengan hari ini)
        if ($datetimeDitutup && $datetimeDitutup->isToday() && $datetimeDitutup->format('H:i') < now()->format('H:i')) {
            return back()->with('error', [
                    'title' => 'Tanggal waktu ditutup tidak valid!',
                    'message'  => 'Waktu ditutup tidak boleh kurang dari waktu saat ini'
                ])->withInput();
        }

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
            'waktu_dibuka' => [
                'required',
                'regex:/^([01]\d|2[0-3]):00$/',
                ],
            'waktu_ditutup' => [
                    'required',
                    'regex:/^([01]\d|2[0-3]):00$/',
                ],
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'katalog_id' => 'nullable|exists:katalogs,id', // Validasi foreign key ke tabel katalog
            'current_img' => 'nullable|string', // URL gambar jika dari katalog
            'currentEdit_img' => 'nullable|string', // URL gambar saat ini (mode edit)
            'selected_image' => 'required|string', // validasi terpilih
        ]);
        return $this->updateDataLelang($request, $datetimeDibuka, $datetimeDitutup);
    }

    public function updateDataLelang(Request $request, $datetimeDibuka, $datetimeDitutup)
    {
        $lelang = M_Lelang::findOrFail($request->lelang_id);

        // Update nama
        $lelang->nama_produk_lelang = $request->nama_produk_lelang;
        // Update keterangan
        $lelang->keterangan = $request->keterangan;
        // Update harga dibuka
        $lelang->harga_dibuka = $request->harga_dibuka;

        // Update tanggal hanya jika belum jatuh tempo
        if ($lelang->tanggal_dibuka >= now()) {
            $lelang->tanggal_dibuka = $datetimeDibuka;
        }
        // Update tanggal hanya jika belum jatuh tempo
        if ($lelang->tanggal_ditutup >= now()) {
            $lelang->tanggal_ditutup = $datetimeDitutup;
        }
        // Update katalog_id
        $lelang->katalog_id = $request->katalog_id;

        // foto dari katalog baru
        if ($request->selected_image == 'foto dari katalog') {
            // Salin file dari katalog ke folder lelangs
            $katalog = M_Katalog::find($request->katalog_id);
            $sourcePath = $katalog->foto_produk;
            $destinationPath = 'lelangs/' . basename($katalog->foto_produk);
            Storage::copy($sourcePath, $destinationPath);
            $lelang->foto_produk = 'lelangs/' . basename($katalog->foto_produk);
        // foto saat ini, tidak ada perubahan
        } elseif ($request->selected_image == 'foto saat ini') {
        // foto unggahan baru
        } elseif ($request->selected_image == 'foto unggahan baru') {
            // Simpan file baru
            $filePath = $request->file('foto_produk')->store('lelangs', 'public');
            $lelang->foto_produk = $filePath;
        }

        // Simpan perubahan
        $lelang->save();

        // Redirect dengan pesan sukses
        return redirect()->route('lelang.index')->with('success', [
            'title' => 'Berhasil Ubah!',
            'message'  => 'Lelang berhasil diperbarui'
        ]);
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

    // menghandle delete apakah delete lelang atau pasangLelang
    public function handleDelete($id)
    {
        $userRole = Auth::user()->role->nama_role;

        if ($userRole === 'pegawai') {
            return $this->destroy($id);
        } elseif ($userRole === 'customer') {
            // Membuat instance controller C_PasangLelang dan memanggil forceDelete
            $pasangLelangController = new C_PasangLelang();
            return $pasangLelangController->forceDelete($id);
        }

        else {
            abort(403, 'Unauthorized action [C_Lelang::handleDelete]');
        }
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
