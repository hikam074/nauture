<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class M_Katalog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'katalogs';

    protected $fillable = [
        'nama_produk',
        'deskripsi_produk',
        'harga_perkilo',
        'foto_produk',
    ];

}
