<?php
use App\Http\Controllers\C_BidWinner;
use App\Http\Controllers\C_PasangLelang;
use App\Http\Controllers\C_HalamanUtama;
use App\Http\Controllers\C_Katalog;
use App\Http\Controllers\C_Lelang;
use App\Http\Controllers\C_Login;
use App\Http\Controllers\C_Registrasi;
use App\Http\Controllers\C_Profil;
use App\Http\Controllers\C_Transaksi;
use App\Http\Controllers\C_Midtrans;

use App\Http\Middleware\RoleMiddleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Http;


// GET homepage
Route::get('/', [C_HalamanUtama::class, 'showHalamanUtama'])
    ->name('homepage');

// GET mengarahkan ke login page
Route::get('/login', [C_Login::class, 'showFormLogin'])
    ->name('login');
// POST proses login
Route::post('/login', [C_Login::class, 'checkInputDataValid'])
    ->name('login.process');

// GET mengarahkan ke register page
Route::get('/register', [C_Registrasi::class, 'showFormRegistrasiAkun'])
    ->name('register');
// POST proses register
Route::post('/register', [C_Registrasi::class, 'checkInputDataValid'])
    ->name('register.process');

// GET-KONFIRM Logout
Route::get('/logout', [C_Profil::class, 'klikLogout'])
    ->name('logout');
// GET proses logout
Route::get('/logout-process', [C_Profil::class, 'Logout'])
    ->name('logout.process');





// GET ke halaman katalog
Route::get('/katalog', [C_Katalog::class, 'getDataKatalog'])
    ->name('katalog.index');

// GET ke add.katalog (hanya untuk pegawai)
Route::get('/katalog/add', [C_Katalog::class, 'showFormTambahKatalogProduk'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.add');
// POST submit hasil katalog.add (hanya untuk pegawai)
Route::post('/katalog/add', [C_Katalog::class, 'checkInputNotNull'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.store');

// GET ke show.katalog
Route::get('/katalog/{id}', [C_Katalog::class, 'getDetailDataKatalog'])
    ->name('katalog.show');

// DELETE hapus katalog (hanya untuk pegawai)
Route::delete('/katalog/{id}', [C_Katalog::class, 'destroy'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.destroy');

// GET ke edit.katalog (hanya untuk pegawai)
Route::get('/katalog/{id}/edit', [C_Katalog::class, 'showFormUbahKatalog'])
    ->name('katalog.edit');
// GET-CONFIRM submit hasil edit.katalog (hanya untuk pegawai)
Route::get('/katalog/{id}/edit-konfirm', [C_Katalog::class, 'klikSimpanPerubahan'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.update.confirm');
// PUT submit hasil edit.katalog (hanya untuk pegawai)
Route::put('/katalog/{id}/edit', [C_Katalog::class, 'checkUbahKatalog'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.update');

// PATCH restore katalog (hanya untuk pegawai)
Route::patch('/katalog/{id}/restore', [C_Katalog::class, 'restore'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.restore');




// GET ke lelang
Route::get('/lelang', [C_Lelang::class, 'getDataLelang'])
    ->name('lelang.index');

// GET ke add.lelang (hanya untuk pegawai)
Route::get('/lelang/add', [C_Lelang::class, 'showFormTambahLelang'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('lelang.add');
// api ambil data katalog untuk lelang
Route::get('/api/katalog/{id}', [C_Katalog::class, 'getKatalog'])
    ;
// POST Proses post tambah lelang (hanya untuk pegawai)
Route::post('/lelang/add', [C_Lelang::class, 'checkDataLengkap'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('lelang.store');

// GET cek ketika hendak pasang lelang
Route::get('/lelang/auth', [C_PasangLelang::class, 'showFormPasangLelang']);

// GET ke show.lelang
Route::get('/lelang/{id}', [C_Lelang::class, 'getDetailDataLelang'])
    ->name('lelang.show');

// GET ke edit.lelang (hanya untuk pegawai)
Route::get('/lelang/{id}/edit', [C_Lelang::class, 'showFormUbahProdukLelang'])
    ->name('lelang.edit');
// GET-KONFIRM submit hasil edit.lelang (hanya untuk pegawai)
Route::get('/lelang/{id}/edit-konfirm', [C_Lelang::class, 'klikSimpanPerubahan'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('lelang.update.confirm');
// PUT proses simpan edit.lelang ke db (hanya untuk pegawai)
Route::put('/lelang/{id}/edit', [C_Lelang::class, 'checkUbahDataLelang'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('lelang.update');

// DELETE hapus lelang (hanya untuk pegawai) || DELETE batal pasang bid lelang (hanya untuk customer)
Route::delete('/lelang/{id}', [C_Lelang::class, 'handleDelete'])
    ->middleware('auth')
    ->name('lelang.destroy');

// PATCH restore lelang (hanya untuk pegawai)
Route::patch('/lelang/{id}/restore', [C_Lelang::class, 'restore'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('lelang.restore');

// POST proses pasang bid lelang (hanya untuk customer)
Route::post('/lelang/{id}', [C_PasangLelang::class, 'checkDataLengkap'])
    ->middleware('auth')
    ->middleware('role:customer')
    ->name('lelang.bid');




// GET show halaman profil
Route::get('/profil', [C_Profil::class, 'showDataProfil'])
    ->middleware('auth')
    ->name('profil.index');
// POST update profil
Route::post('/profil', [C_Profil::class, 'updateDataProfil'])
    ->middleware('auth')
    ->name('profil.update');

// GET show bid an saya
Route::get('/lelang-saya', [C_PasangLelang::class, 'showDataLelangUserIni'])
    ->middleware('auth')
    ->middleware('role:customer')
    ->name('lelang.saya');

// GET halaman transaksi saya
Route::get('/transaksi-saya', [C_Transaksi::class, 'showDataTransaksiUserIni'])
    ->middleware('auth')
    ->middleware('role:customer')
    ->name('transaksi.index');
// POST create tagihan transaksi
Route::post('/lelang-saya', [C_Transaksi::class, 'createTransaksi'])
    ->middleware('auth')
    ->middleware('role:customer')
    ->name('transaksi.create');
// GET halaman anda akan membayar
Route::get('/pay/{id}', [C_Transaksi::class, 'showHalamanChekout'])
    ->middleware('auth')
    ->middleware('role:customer')
    ->name('transaksi.checkout');
// GET halaman pembayaran sukses
Route::get('/pay/success/{id}', [C_Transaksi::class, 'showHalamanSukses'])
    ->middleware('auth')
    ->middleware('role:customer')
    ->name('transaksi.success');




//
