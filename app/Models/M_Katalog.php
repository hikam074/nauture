<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Katalog extends Model
{
    use HasFactory;

    protected $table = 'katalogs';

    protected $fillable = [
        'nama_produk',
        'deskripsi_produk',
        'harga_perkilo',
        'foto_produk',
    ];

}
