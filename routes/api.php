<?php

use App\Http\Controllers\C_Alamat;
use App\Http\Controllers\C_Midtrans;
use App\Http\Controllers\C_Notifikasi;
use App\Http\Controllers\C_RajaOngkir;
use App\Http\Controllers\C_Whatsapp;
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
Route::get('/send-notification', [C_Notifikasi::class, 'sendNotification']);
// GET this_database cari city
Route::get('/cari-city/{id}', [C_Alamat::class, 'getDataCity'])
    ->name('nauture.cariCity');
// POST fonnte kirim pesan
Route::post('/send-whatsapp', [C_Whatsapp::class, 'sendMessage']);
