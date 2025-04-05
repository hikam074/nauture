<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_StatusTransaksi extends Model
{
    protected $table = 'status_transaksis';

    protected $fillable = ['nama_status_transaksi'];

    public function transaksi()
    {
        return $this->hasMany(M_Transaksi::class, 'status_transaksi_id');
    }
}
