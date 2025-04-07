<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Transaksi extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        'kode_transaksi',
        'lelang_id',
        'pasang_lelang_id',
        'nominal',
        'alamat',
        'deadline_transaksi',
        'metode_pembayaran_id',
        'status_transaksi_id',
        'bukti_transfer',
    ];

    // reference this lelang_id ke lelangs id
    public function lelang()
    {
        return $this->belongsTo(M_Lelang::class);
    }
    // reference this pasang_lelang_id ke pasang_lelangs id
    public function pasangLelang()
    {
        return $this->belongsTo(M_PasangLelang::class);
    }
    // reference this metode_pembayaran_id ke metode_pembayaran id
    public function metodePembayaran()
    {
        return $this->belongsTo(M_MetodePembayaran::class);
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
    // deklarasi this transaksi_id bisa punya banyak transaksi_id di xendit_payments
    public function xenditPayment()
    {
        return $this->hasMany(M_XenditPayment::class);
    }
}
