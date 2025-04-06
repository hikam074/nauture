<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_StatusTransaksi extends Model
{
    protected $table = 'status_transaksis';

    protected $fillable = ['nama_status_transaksi'];

}
