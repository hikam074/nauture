<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Rating extends Model
{
    protected $table = 'ratings';

    protected $fillable = ['rating', 'ulasan', 'kode_transaksi'];

}
