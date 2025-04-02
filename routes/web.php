<?php

use App\Http\Controllers\C_Dashboard;
use App\Http\Controllers\C_Katalog;
use App\Http\Controllers\C_Login;
use App\Http\Middleware\RoleMiddleware;
use App\Models\M_Katalog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// main page
Route::get('/', function () {
    return view('welcome');
});

// mengarahkan ke login page
Route::get('/login', function () {
    return view('auth.login');
    })
    ->name('login');

// proses login
Route::post('/login', [C_Login::class, 'authenticate'])
    ->name('login.process');

// dashboard
Route::get('/dashboard', [C_Dashboard::class, 'index'])
    ->name('dashboard');

// ke katalog
Route::get('/katalog', function () {
    $katalogs = M_Katalog::all();
    return view('katalog.index', ["katalogs" =>$katalogs]);
    });

// ke show.katalog
Route::get('/katalog/{id}', [C_katalog::class, 'show'])
    ->name('katalog.show');

// ke add.katalog (hanya untuk pegawai)
Route::get('/katalog/add', [C_Katalog::class, 'create'])
    ->middleware(['auth', 'role:pegawai'])
    ->name('katalog.add');

// Proses tambah katalog (hanya untuk pegawai)
Route::post('/katalog', [C_Katalog::class, 'store'])
    ->middleware(['auth', 'role:pegawai'])
    ->name('katalog.store');

// Logout
Route::post('/logout', [C_Login::class, 'logout'])
    ->name('logout');
