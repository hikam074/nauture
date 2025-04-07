<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class M_Katalog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'katalogs';

    protected $fillable = [
        'nama_produk',
        'deskripsi_produk',
        'harga_perkilo',
        'foto_produk',
    ];

    // deklarasi this katalog_id bisa punya banyak katalog_id di lelangs
    public function lelang()
    {
        return $this->hasMany(M_Lelang::class);
    }
}
