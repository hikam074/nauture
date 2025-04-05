<?php

use App\Http\Controllers\C_Dashboard;
use App\Http\Controllers\C_Katalog;
use App\Http\Controllers\C_Login;
use App\Http\Middleware\RoleMiddleware;
use App\Models\M_Katalog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// dashboard
Route::get('/', [C_Dashboard::class, 'index'])
    ->name('dashboard');

// mengarahkan ke login page
Route::get('/login', function () {
    return view('auth.login');
    })
    ->name('login');

// proses login
Route::post('/login', [C_Login::class, 'authenticate'])
    ->name('login.process');

// ke katalog
Route::get('/katalog', [C_Katalog::class, 'index'])
    ->name('katalog.index');

// ke add.katalog (hanya untuk pegawai)
Route::get('/katalog/add', [C_Katalog::class, 'create'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.add');

// Proses post tambah katalog (hanya untuk pegawai)
Route::post('/katalog/add', [C_Katalog::class, 'store'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.store');

// ke show.katalog
Route::get('/katalog/{id}', [C_Katalog::class, 'show'])
    ->name('katalog.show');

// hapus katalog
Route::delete('/katalog/{id}', [C_Katalog::class, 'destroy'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.destroy');

// ke edit.katalog
Route::get('/katalog/{id}/edit', [C_Katalog::class, 'edit'])
    ->name('katalog.edit');

Route::post('/katalog/{id}/edit', [C_Katalog::class, 'update'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.update');

// restore katalog
Route::patch('/katalog/{id}/restore', [C_Katalog::class, 'restore'])
    ->middleware('auth')
    ->middleware('role:pegawai')
    ->name('katalog.restore');







// Logout
Route::post('/logout', [C_Login::class, 'logout'])
    ->name('logout');
