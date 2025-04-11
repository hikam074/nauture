<?php

use App\Http\Controllers\C_Homepage;
use App\Http\Controllers\C_Katalog;
use App\Http\Controllers\C_Lelang;
use App\Http\Controllers\C_Login;
use App\Http\Controllers\C_Register;
use App\Http\Middleware\RoleMiddleware;
use App\Models\M_Katalog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// homepage
Route::get('/', [C_Homepage::class, 'index'])
    ->name('homepage');

// mengarahkan ke login page
Route::get('/login', [C_Login::class, 'show'])
    ->name('login');
// proses login
Route::post('/login', [C_Login::class, 'authenticate'])
    ->name('login.process');

// mengarahkan ke register page
Route::get('/register', [C_Register::class, 'show'])
    ->name('register');
// proses register
Route::post('/register', [C_Register::class, 'register'])
    ->name('register.process');


// ke katalog
Route::get('/katalog', [C_Katalog::class, 'index'])
    ->name('katalog.index');
// ke add.katalog (hanya untuk pegawai)
Route::get('/katalog/add', [C_Katalog::class, 'create'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.add');
// submit hasil katalog.add (hanya untuk pegawai)
Route::post('/katalog/add', [C_Katalog::class, 'store'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.store');
// ke show.katalog
Route::get('/katalog/{id}', [C_Katalog::class, 'show'])
    ->name('katalog.show');
// hapus katalog (hanya untuk pegawai)
Route::delete('/katalog/{id}', [C_Katalog::class, 'destroy'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.destroy');
// ke edit.katalog (hanya untuk pegawai)
Route::get('/katalog/{id}/edit', [C_Katalog::class, 'edit'])
    ->name('katalog.edit');
// submit hasil edit.katalog (hanya untuk pegawai)
Route::post('/katalog/{id}/edit', [C_Katalog::class, 'update'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.update');
// restore katalog (hanya untuk pegawai)
Route::patch('/katalog/{id}/restore', [C_Katalog::class, 'restore'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.restore');

// ke lelang
Route::get('/lelang', [C_Lelang::class, 'index'])
    ->name('lelang.index');
// ke add.lelang (hanya untuk pegawai)
Route::get('/lelang/add', [C_Lelang::class, 'create'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('lelang.add');
// api ambil data katalog untuk lelang
Route::get('/api/katalog/{id}', [C_Katalog::class, 'getKatalog'])
    ;
// Proses post tambah lelang (hanya untuk pegawai)
Route::post('/lelang/add', [C_Lelang::class, 'store'])
->middleware('auth')
->middleware('role:pegawai')
->name('lelang.store');
// ke show.lelang
Route::get('/lelang/{id}', [C_Lelang::class, 'show'])
    ->name('lelang.show');
// ke edit.lelang (hanya untuk pegawai)
Route::get('/lelang/{id}/edit', [C_Lelang::class, 'edit'])
->name('lelang.edit');
// submit hasil edit.lelang (hanya untuk pegawai)
Route::put('/lelang/{id}/edit', [C_Lelang::class, 'update'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('lelang.update');
// hapus lelang (hanya untuk pegawai)
Route::delete('/lelang/{id}', [C_Lelang::class, 'destroy'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('lelang.destroy');
// restore lelang (hanya untuk pegawai)
Route::patch('/lelang/{id}/restore', [C_Lelang::class, 'restore'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('lelang.restore');


// Logout
Route::post('/logout', [C_Login::class, 'logout'])
    ->name('logout');
