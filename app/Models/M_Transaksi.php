<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Transaksi extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        //kode_transaksi
        'alamat',
        'status_transaksi_id'=>1,
        //deadline_transaksi
        'kode_lelang',
        'metode_pembayaran_id'
    ];

}
