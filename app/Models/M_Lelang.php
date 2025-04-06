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
        'pemenang_id',
        'katalog_id',
    ];

    // reference this katalog_id ke katalogs id
    public function katalog()
    {
        return $this->belongsTo(M_Katalog::class, 'katalog_id');
    }

}
