<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_PaymentMethod extends Model
{
    protected $table = 'payment_methods';

    protected $fillable = [
        'nama_metode_pembayaran',
        'kode_metode_pembayaran',
    ];

    // deklarasi this metode_pembayaran_id bisa punya banyak metode_pembayaran_id di transaksis
    public function transaksi()
    {
        return $this->hasMany(M_Transaksi::class);
    }
}
