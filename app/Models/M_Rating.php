<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Rating extends Model
{
    protected $table = 'ratings';

    protected $fillable = [
        'rating',
        'ulasan',
        'kode_transaksi'
    ];

    // reference this kode_transaksi ke transaksis kode_transaksi
    public function kode_transaksi()
    {
        return $this->belongsTo(M_Transaksi::class, 'kode_transaksi', 'kode_transaksi');
    }

}
