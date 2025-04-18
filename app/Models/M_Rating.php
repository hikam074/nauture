<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Rating extends Model
{
    protected $table = 'ratings';

    protected $fillable = [
        'transaksi_id',
        'rating',
        'ulasan',
    ];

    // reference this transaksi_id ke transaksis id
    public function transaksi()
    {
        return $this->belongsTo(M_Transaksi::class);
    }
}
