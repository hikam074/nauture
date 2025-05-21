<?php

use App\Http\Controllers\C_Midtrans;
use App\Http\Controllers\C_OpenRoute;
use App\Http\Controllers\C_RajaOngkir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// POST midtrans ngirim data status transaksi ke sini
Route::post('/midtrans-callback', [C_Midtrans::class, 'handleNotification'])
    ->name('midtrans.notification');
// GET rajaongkir cari lokasi tujuan
Route::get('/cari-lokasi', [C_RajaOngkir::class, 'cariDestination'])
    ->name('rajaongkir.cariDestination');
// POST rajaongkir hitung ongkir
Route::post('/cek-ongkir', [C_RajaOngkir::class, 'hitungOngkir'])
    ->name('rajaongkir.hitungOngkir');
// POST openroutfe hitung jarak
Route::post('/cek-jarak', [C_OpenRoute::class, 'cariRuteJarak'])
    ->name('openroute.hitungJarak');
