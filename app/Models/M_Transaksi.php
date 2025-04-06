<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Transaksi extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        'kode_transaksi',
        'kode_lelang',
        'alamat',
        'deadline_transaksi',
        'status_transaksi_id' => 1,
        'metode_pembayaran_id',
    ];

    // reference this kode_lelang ke lelangs kode_lelang
    public function lelang()
    {
        return $this->belongsTo(M_Lelang::class, 'kode_lelang', 'kode_lelang');
    }

    // reference this status_transaksi_id ke status_transaksis id
    public function statusTransaksi()
    {
        return $this->belongsTo(M_StatusTransaksi::class, 'status_transaksi_id');
    }

    // reference this metode_pembayaran_id ke metode_pembayaran id
    public function metodePembayaran()
    {
        return $this->belongsTo(M_MetodePembayaran::class, 'metode_pembayaran_id');
    }

}
