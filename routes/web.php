<?php
use App\Http\Controllers\C_PasangLelang;
use App\Http\Controllers\C_HalamanUtama;
use App\Http\Controllers\C_Katalog;
use App\Http\Controllers\C_Lelang;
use App\Http\Controllers\C_Login;
use App\Http\Controllers\C_Notifikasi;
use App\Http\Controllers\C_Registrasi;
use App\Http\Controllers\C_Profil;
use App\Http\Controllers\C_Transaksi;

use Illuminate\Support\Facades\Route;


Route::prefix('/')->group(function () {
    Route::get('/', [C_HalamanUtama::class, 'showHalamanUtama'])->name('homepage'); // GET homepage
    Route::get('/login', [C_Login::class, 'showFormLogin'])->name('login'); // GET mengarahkan ke login page
    Route::post('/login', [C_Login::class, 'checkInputDataValid'])->name('login.process'); // POST proses login
    Route::get('/register', [C_Registrasi::class, 'showFormRegistrasiAkun'])->name('register'); // GET mengarahkan ke register page
    Route::post('/register', [C_Registrasi::class, 'checkInputDataValid'])->name('register.process'); // POST proses register
    Route::get('/logout', [C_Profil::class, 'klikLogout'])->name('logout'); // GET-KONFIRM Logout
    Route::get('/logout-process', [C_Profil::class, 'Logout'])->name('logout.process'); // GET proses logout
});

// MENU KATALOG
Route::prefix('/katalog')->group(function () {
    Route::get('/', [C_Katalog::class, 'getDataKatalog'])->name('katalog.index'); // GET ke halaman katalog
    Route::get('/add', [C_Katalog::class, 'showFormTambahKatalogProduk'])->middleware('auth')->middleware('role:pegawai')->name('katalog.add'); // GET ke add.katalog (hanya untuk pegawai)
    Route::post('/add', [C_Katalog::class, 'checkInputNotNull'])->middleware('auth')->middleware('role:pegawai')->name('katalog.store'); // POST submit hasil katalog.add (hanya untuk pegawai)
    Route::get('/{id}', [C_Katalog::class, 'getDetailDataKatalog'])->name('katalog.show'); // GET ke show.katalog
    Route::delete('/{id}', [C_Katalog::class, 'destroy'])->middleware('auth')->middleware('role:pegawai')->name('katalog.destroy'); // DELETE hapus katalog (hanya untuk pegawai)
    Route::get('/{id}/edit', [C_Katalog::class, 'showFormUbahKatalog'])->middleware('auth')->middleware('role:pegawai')->name('katalog.edit'); // GET ke edit.katalog (hanya untuk pegawai)
    Route::get('/{id}/edit-konfirm', [C_Katalog::class, 'klikSimpanPerubahan'])->middleware('auth')->middleware('role:pegawai')->name('katalog.update.confirm'); // GET-CONFIRM submit hasil edit.katalog (hanya untuk pegawai)
    Route::put('/{id}/edit', [C_Katalog::class, 'checkUbahKatalog'])->middleware('auth')->middleware('role:pegawai')->name('katalog.update'); // PUT submit hasil edit.katalog (hanya untuk pegawai)
    Route::patch('/{id}/restore', [C_Katalog::class, 'restore'])->middleware('auth')->middleware('role:pegawai')->name('katalog.restore'); // PATCH restore katalog (hanya untuk pegawai)
});

// MENU LELANG
Route::prefix('/lelang')->group(function () {
    Route::get('/', [C_Lelang::class, 'getDataLelang'])->name('lelang.index'); // GET ke lelang
    Route::get('/add', [C_Lelang::class, 'showFormTambahLelang'])->middleware('auth')->middleware('role:pegawai')->name('lelang.add'); // GET ke add.lelang (hanya untuk pegawai)
    Route::post('/add', [C_Lelang::class, 'checkDataLengkap'])->middleware('auth')->middleware('role:pegawai')->name('lelang.store'); // POST Proses post tambah lelang (hanya untuk pegawai)
    Route::get('/auth', [C_PasangLelang::class, 'showFormPasangLelang']); // GET cek ketika hendak pasang lelang
    Route::get('/{id}', [C_Lelang::class, 'getDetailDataLelang'])->name('lelang.show'); // GET ke show.lelang
    Route::get('/{id}/edit', [C_Lelang::class, 'showFormUbahProdukLelang'])->middleware('auth')->middleware('role:pegawai')->name('lelang.edit'); // GET ke edit.lelang (hanya untuk pegawai)
    Route::get('/{id}/edit-konfirm', [C_Lelang::class, 'klikSimpanPerubahan'])->middleware('auth')->middleware('role:pegawai')->name('lelang.update.confirm'); // GET-KONFIRM submit hasil edit.lelang (hanya untuk pegawai)
    Route::get('/{id}/edit-konfirm', [C_Lelang::class, 'klikSimpanPerubahan'])->middleware('auth')->middleware('role:pegawai')->name('lelang.update.confirm'); // GET-KONFIRM submit hasil edit.lelang (hanya untuk pegawai)
    Route::put('/{id}/edit', [C_Lelang::class, 'checkUbahDataLelang'])->middleware('auth')->middleware('role:pegawai')->name('lelang.update'); // PUT proses simpan edit.lelang ke db (hanya untuk pegawai)
    Route::delete('/{id}', [C_Lelang::class, 'handleDelete'])->middleware('auth')->name('lelang.destroy'); // DELETE hapus lelang (hanya untuk pegawai) || DELETE batal pasang bid lelang (hanya untuk customer)
    Route::patch('/{id}/restore', [C_Lelang::class, 'restore'])->middleware('auth')->middleware('role:pegawai')->name('lelang.restore'); // PATCH restore lelang (hanya untuk pegawai)
    Route::post('/{id}', [C_PasangLelang::class, 'checkDataLengkap'])->middleware('auth')->middleware('role:customer')->name('lelang.bid'); // POST proses pasang bid lelang (hanya untuk customer)
});

Route::get('/api/katalog/{id}', [C_Katalog::class, 'getKatalog']);

// MENU DASHBOARD
Route::prefix('/dashboard')->group(function () {
    Route::get('/profil', [C_Profil::class, 'getDataProfil'])->middleware('auth')->name('profil.index'); // GET show halaman profil
    Route::get('/profil/ubah', [C_Profil::class, 'editDataProfil'])->middleware('auth')->name('profil.edit'); // POST update profil
    Route::post('/profil/ubah', [C_Profil::class, 'checkInputDataTerbaru'])->middleware('auth')->name('profil.update'); // POST update profil

    Route::get('/', [C_Profil::class, 'showHalamanDashboard'])->middleware('auth')->name('dashboard.index'); // GET show halaman dashboard
    Route::get('/notifikasi', [C_Notifikasi::class, 'getDataNotifikasi'])->middleware('auth')->name('dashboard.notifikasi'); // GET show halaman notif
    Route::get('/katalog', [C_katalog::class, 'getSemuaKatalog'])->middleware('auth')->middleware('role:pegawai,owner')->name('dashboard.katalog'); // GET halaman list katalog
    Route::get('/lelang', [C_lelang::class, 'getSemuaLelang'])->middleware('auth')->middleware('role:pegawai,owner')->name('dashboard.lelang'); // GET halaman list lelang
    Route::get('/transaksi', [C_Transaksi::class, 'showDataTransaksi'])->middleware('auth')->middleware('role:pegawai,owner')->name('dashboard.transaksi'); // GET halaman list transaksi
    Route::patch('/', [C_Transaksi::class, ''])->middleware('auth')->middleware('role:pegawai')->name('dashboard.updateStatsTransaksi');

    Route::get('/transaksi-saya', [C_Transaksi::class, 'showDataTransaksiUserIni'])->middleware('auth')->middleware('role:customer')->name('transaksi.index'); // GET halaman transaksi saya
    Route::get('/lelang-saya', [C_PasangLelang::class, 'getDataLelangUserIni'])->middleware('auth')->middleware('role:customer')->name('lelang.saya'); // GET show bid an saya
    Route::post('/lelang-saya', [C_Transaksi::class, 'checkBatasWaktuPembayaran'])->middleware('auth')->middleware('role:customer')->name('transaksi.create'); // POST create tagihan transaksi
    Route::get('/lelang-saya/pay/{id}', [C_Transaksi::class, 'showHalamanChekout'])->middleware('auth')->middleware('role:customer')->name('transaksi.checkout'); // GET halaman anda akan membayar

});




// // GET homepage
// Route::get('/', [C_HalamanUtama::class, 'showHalamanUtama'])
//     ->name('homepage');

// // GET mengarahkan ke login page
// Route::get('/login', [C_Login::class, 'showFormLogin'])
//     ->name('login');
// // POST proses login
// Route::post('/login', [C_Login::class, 'checkInputDataValid'])
//     ->name('login.process');

// // GET mengarahkan ke register page
// Route::get('/register', [C_Registrasi::class, 'showFormRegistrasiAkun'])
//     ->name('register');
// // POST proses register
// Route::post('/register', [C_Registrasi::class, 'checkInputDataValid'])
//     ->name('register.process');

// // GET-KONFIRM Logout
// Route::get('/logout', [C_Profil::class, 'klikLogout'])
//     ->name('logout');
// // GET proses logout
// Route::get('/logout-process', [C_Profil::class, 'Logout'])
//     ->name('logout.process');









// // GET ke halaman katalog
// Route::get('/katalog', [C_Katalog::class, 'getDataKatalog'])
//     ->name('katalog.index');

// // GET ke add.katalog (hanya untuk pegawai)
// Route::get('/katalog/add', [C_Katalog::class, 'showFormTambahKatalogProduk'])
//     ->middleware('auth')
//     ->middleware('role:pegawai')
//     ->name('katalog.add');
// // POST submit hasil katalog.add (hanya untuk pegawai)
// Route::post('/katalog/add', [C_Katalog::class, 'checkInputNotNull'])
//     ->middleware('auth')
//     ->middleware('role:pegawai')
//     ->name('katalog.store');

// // GET ke show.katalog
// Route::get('/katalog/{id}', [C_Katalog::class, 'getDetailDataKatalog'])
//     ->name('katalog.show');

// // DELETE hapus katalog (hanya untuk pegawai)
// Route::delete('/katalog/{id}', [C_Katalog::class, 'destroy'])
//     ->middleware('auth')
//     ->middleware('role:pegawai')
//     ->name('katalog.destroy');

// // GET ke edit.katalog (hanya untuk pegawai)
// Route::get('/katalog/{id}/edit', [C_Katalog::class, 'showFormUbahKatalog'])
//     ->name('katalog.edit');
// // GET-CONFIRM submit hasil edit.katalog (hanya untuk pegawai)
// Route::get('/katalog/{id}/edit-konfirm', [C_Katalog::class, 'klikSimpanPerubahan'])
//     ->middleware('auth')
//     ->middleware('role:pegawai')
//     ->name('katalog.update.confirm');
// // PUT submit hasil edit.katalog (hanya untuk pegawai)
// Route::put('/katalog/{id}/edit', [C_Katalog::class, 'checkUbahKatalog'])
//     ->middleware('auth')
//     ->middleware('role:pegawai')
//     ->name('katalog.update');

// // PATCH restore katalog (hanya untuk pegawai)
// Route::patch('/katalog/{id}/restore', [C_Katalog::class, 'restore'])
//     ->middleware('auth')
//     ->middleware('role:pegawai')
//     ->name('katalog.restore');







// // GET ke lelang
// Route::get('/lelang', [C_Lelang::class, 'getDataLelang'])
//     ->name('lelang.index');

// GET ke add.lelang (hanya untuk pegawai)
// Route::get('/lelang/add', [C_Lelang::class, 'showFormTambahLelang'])
//     ->middleware('auth')
//     ->middleware('role:pegawai')
//     ->name('lelang.add');
// api ambil data katalog untuk lelang
// // POST Proses post tambah lelang (hanya untuk pegawai)
// Route::post('/lelang/add', [C_Lelang::class, 'checkDataLengkap'])
//     ->middleware('auth')
//     ->middleware('role:pegawai')
//     ->name('lelang.store');

// // GET cek ketika hendak pasang lelang
// Route::get('/lelang/auth', [C_PasangLelang::class, 'showFormPasangLelang']);

// // GET ke show.lelang
// Route::get('/lelang/{id}', [C_Lelang::class, 'getDetailDataLelang'])
//     ->name('lelang.show');

// GET ke edit.lelang (hanya untuk pegawai)
// Route::get('/lelang/{id}/edit', [C_Lelang::class, 'showFormUbahProdukLelang'])
//     ->name('lelang.edit');
// // GET-KONFIRM submit hasil edit.lelang (hanya untuk pegawai)
// Route::get('/lelang/{id}/edit-konfirm', [C_Lelang::class, 'klikSimpanPerubahan'])
//     ->middleware('auth')
//     ->middleware('role:pegawai')
//     ->name('lelang.update.confirm');
// // PUT proses simpan edit.lelang ke db (hanya untuk pegawai)
// Route::put('/lelang/{id}/edit', [C_Lelang::class, 'checkUbahDataLelang'])
//     ->middleware('auth')
//     ->middleware('role:pegawai')
//     ->name('lelang.update');

// // DELETE hapus lelang (hanya untuk pegawai) || DELETE batal pasang bid lelang (hanya untuk customer)
// Route::delete('/lelang/{id}', [C_Lelang::class, 'handleDelete'])
//     ->middleware('auth')
//     ->name('lelang.destroy');

// // PATCH restore lelang (hanya untuk pegawai)
// Route::patch('/lelang/{id}/restore', [C_Lelang::class, 'restore'])
//     ->middleware('auth')
//     ->middleware('role:pegawai')
//     ->name('lelang.restore');

// // POST proses pasang bid lelang (hanya untuk customer)
// Route::post('/lelang/{id}', [C_PasangLelang::class, 'checkDataLengkap'])
//     ->middleware('auth')
//     ->middleware('role:customer')
//     ->name('lelang.bid');




// // GET show halaman profil
// Route::get('/profil', [C_Profil::class, 'showDataProfil'])
//     ->middleware('auth')
//     ->name('profil.index');
// // POST update profil
// Route::post('/profil', [C_Profil::class, 'updateDataProfil'])
//     ->middleware('auth')
//     ->name('profil.update');

// // GET show bid an saya
// Route::get('/lelang-saya', [C_PasangLelang::class, 'showDataLelangUserIni'])
//     ->middleware('auth')
//     ->middleware('role:customer')
//     ->name('lelang.saya');

// // GET halaman transaksi saya
// Route::get('/transaksi-saya', [C_Transaksi::class, 'showDataTransaksiUserIni'])
//     ->middleware('auth')
//     ->middleware('role:customer')
//     ->name('transaksi.index');
// POST create tagihan transaksi
// Route::post('/lelang-saya', [C_Transaksi::class, 'createTransaksi'])
//     ->middleware('auth')
//     ->middleware('role:customer')
//     ->name('transaksi.create');
// // GET halaman anda akan membayar
// Route::get('/pay/{id}', [C_Transaksi::class, 'showHalamanChekout'])
//     ->middleware('auth')
//     ->middleware('role:customer')
//     ->name('transaksi.checkout');





