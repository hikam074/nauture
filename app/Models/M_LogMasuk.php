<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_LogMasuk extends Model
{
    protected $table = 'log_masuks';

    protected $fillable = [
        'transaksi_id',
        'deskripsi',
        'jumlah_masuk',
        'user_id',
    ];

    // reference this transaksi_id ke transaksis id
    public function transaksi()
    {
        return $this->belongsTo(M_Transaksi::class);
    }
    // reference this user_id ke users id
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
