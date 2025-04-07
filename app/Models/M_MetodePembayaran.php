<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_MetodePembayaran extends Model
{
    protected $table = 'metode_pembayarans';

    protected $fillable = [
        'kode_metode_pembayaran',
        'nama_metode_pembayaran',
    ];

    // deklarasi this metode_pembayaran_id bisa punya banyak metode_pembayaran_id di transaksis
    public function transaksi()
    {
        return $this->hasMany(M_Transaksi::class);
    }
    // deklarasi this metode_pembayaran_id bisa punya banyak metode_pembayaran_id di xendit_payments
    public function xenditPayment()
    {
        return $this->hasMany(M_XenditPayment::class);
    }
}
