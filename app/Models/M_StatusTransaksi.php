<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_StatusTransaksi extends Model
{
    protected $table = 'status_transaksis';

    protected $fillable = [
        'kode_status_transaksi',
        'nama_status_transaksi',
    ];

    // deklarasi this status_transaksi_id bisa punya banyak status_transaksi_id di transaksis
    public function transaksi()
    {
        return $this->hasMany(M_Transaksi::class);
    }
    // deklarasi this status_transaksi_id bisa punya banyak status_transaksi_id di xendit_payments
    public function xenditPayment()
    {
        return $this->hasMany(M_XenditPayment::class);
    }
    // deklarasi this status_transaksi_id bisa punya banyak status_transaksi_id di xendit_payouts
    public function xenditPayout()
    {
        return $this->hasMany(M_XenditPayout::class);
    }
}
