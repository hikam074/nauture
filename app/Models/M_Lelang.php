<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class M_Lelang extends Model
{
    use SoftDeletes;

    protected $table = 'lelangs';

    protected $fillable = [
        'kode_lelang',
        'nama_produk_lelang',
        'keterangan',
        'harga_dibuka',
        'tanggal_dibuka',
        'tanggal_ditutup',
        'foto_produk',
        'foto_produk',
        'katalog_id',
    ];

    public function pasangLelang()
    {
        return $this->hasMany(M_PasangLelang::class, 'kode_lelang', 'kode_lelang');
    }

    public function transaksi()
    {
        return $this->hasMany(M_Transaksi::class, 'kode_lelang');
    }

}
