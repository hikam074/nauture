<?php

use App\Http\Controllers\C_PasangLelang;
use App\Http\Controllers\C_HalamanUtama;
use App\Http\Controllers\C_Katalog;
use App\Http\Controllers\C_Lelang;
use App\Http\Controllers\C_Login;
use App\Http\Controllers\C_Registrasi;
use App\Http\Controllers\C_Profil;
use App\Http\Middleware\RoleMiddleware;
use App\Models\M_Katalog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// homepage
Route::get('/', [C_HalamanUtama::class, 'showHalamanUtama'])
    ->name('homepage');

// mengarahkan ke login page
Route::get('/login', [C_Login::class, 'showFormLogin'])
    ->name('login');
// proses login
Route::post('/login', [C_Login::class, 'checkInputDataValid'])
    ->name('login.process');

// mengarahkan ke register page
Route::get('/register', [C_Registrasi::class, 'showFormRegistrasiAkun'])
    ->name('register');
// proses register
Route::post('/register', [C_Registrasi::class, 'checkInputDataValid'])
    ->name('register.process');


// ke katalog
Route::get('/katalog', [C_Katalog::class, 'getDataKatalog'])
    ->name('katalog.index');
// ke add.katalog (hanya untuk pegawai)
Route::get('/katalog/add', [C_Katalog::class, 'showFormTambahKatalogProduk'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.add');
// submit hasil katalog.add (hanya untuk pegawai)
Route::post('/katalog/add', [C_Katalog::class, 'checkInputNotNull'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.store');
// ke show.katalog
Route::get('/katalog/{id}', [C_Katalog::class, 'getDetailDataKatalog'])
    ->name('katalog.show');
// hapus katalog (hanya untuk pegawai)
Route::delete('/katalog/{id}', [C_Katalog::class, 'destroy'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.destroy');
// ke edit.katalog (hanya untuk pegawai)
Route::get('/katalog/{id}/edit', [C_Katalog::class, 'showFormUbahKatalog'])
    ->name('katalog.edit');
// submit hasil edit.katalog (hanya untuk pegawai)
Route::get('/katalog/{id}/edit-konfirm', [C_Katalog::class, 'klikSimpanPerubahan'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.update.confirm');
// submit hasil edit.katalog (hanya untuk pegawai)
Route::put('/katalog/{id}/edit', [C_Katalog::class, 'checkUbahKatalog'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.update');
// restore katalog (hanya untuk pegawai)
Route::patch('/katalog/{id}/restore', [C_Katalog::class, 'restore'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.restore');


// ke lelang
Route::get('/lelang', [C_Lelang::class, 'getDataLelang'])
    ->name('lelang.index');
// ke add.lelang (hanya untuk pegawai)
Route::get('/lelang/add', [C_Lelang::class, 'showFormTambahLelang'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('lelang.add');
// api ambil data katalog untuk lelang
Route::get('/api/katalog/{id}', [C_Katalog::class, 'getKatalog'])
    ;
// Proses post tambah lelang (hanya untuk pegawai)
Route::post('/lelang/add', [C_Lelang::class, 'checkDataLengkap'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('lelang.store');
// cek ketika hendak pasang lelang
Route::get('/lelang/auth', [C_PasangLelang::class, 'showFormPasangLelang']);
// ke show.lelang
Route::get('/lelang/{id}', [C_Lelang::class, 'getDetailDataLelang'])
    ->name('lelang.show');
// ke edit.lelang (hanya untuk pegawai)
Route::get('/lelang/{id}/edit', [C_Lelang::class, 'showFormUbahProdukLelang'])
    ->name('lelang.edit');
// submit hasil edit.lelang (hanya untuk pegawai)
Route::get('/lelang/{id}/edit-konfirm', [C_Lelang::class, 'klikSimpanPerubahan'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('lelang.update.confirm');
// proses simpan edit.lelang ke db (hanya untuk pegawai)
Route::put('/lelang/{id}/edit', [C_Lelang::class, 'checkUbahDataLelang'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('lelang.update');
// hapus lelang (hanya untuk pegawai) || batal pasang bid lelang (hanya untuk customer)
Route::delete('/lelang/{id}', [C_Lelang::class, 'handleDelete'])
    ->middleware('auth')
    ->name('lelang.destroy');
// restore lelang (hanya untuk pegawai)
Route::patch('/lelang/{id}/restore', [C_Lelang::class, 'restore'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('lelang.restore');
// proses post pasang bid lelang (hanya untuk customer)
Route::post('/lelang/{id}', [C_PasangLelang::class, 'checkDataLengkap'])
    ->middleware('auth')
    ->middleware('role:customer')
    ->name('lelang.bid');



// Logout
Route::get('/logout', [C_Profil::class, 'klikLogout'])
    ->name('logout');
Route::get('/logout-process', [C_Profil::class, 'Logout'])
    ->name('logout.process');
