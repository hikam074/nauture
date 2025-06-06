<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Transaksi extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        'order_id',
        'lelang_id',
        'pasang_lelang_id',
        'gross_amount',
        'alamat_id',
        'snap_token',
        'no_resi',
        'payment_time',
        'payment_method_id',
        'status_transaksi_id',
    ];

    // reference this lelang_id ke lelangs id
    public function lelang()
    {
        return $this->belongsTo(M_Lelang::class);
    }
    // reference this pasang_lelang_id ke pasang_lelangs id
    public function pasangLelang()
    {
        return $this->belongsTo(M_PasangLelang::class, 'pasang_lelang_id');
    }
    // reference this alamat ke alamats id
    public function alamat()
    {
        return $this->belongsTo(M_Alamat::class, 'alamat_id');
    }
    // reference this metode_pembayaran_id ke metode_pembayaran id
    public function paymentMethod()
    {
        return $this->belongsTo(M_paymentMethod::class);
    }
    // reference this status_transaksi_id ke status_transaksis id
    public function statusTransaksi()
    {
        return $this->belongsTo(M_StatusTransaksi::class);
    }
    // deklarasi this transaksi_id bisa punya banyak transaksi_id di log_masuks
    public function logMasuk()
    {
        return $this->hasMany(M_LogMasuk::class);
    }
    // deklarasi this transaksi_id hanya bisa ada 1 rating_id
    public function rating()
    {
        return $this->hasOne(M_Rating::class, 'transaksi_id', 'id');
    }
}
