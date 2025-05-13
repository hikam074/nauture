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
}
