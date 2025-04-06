<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_MetodePembayaran extends Model
{
    protected $table = 'metode_pembayarans';

    protected $fillable = ['nama_metode_pembayaran'];
}
