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
        'jumlah_kg',
        'harga_dibuka',
        'tanggal_dibuka',
        'tanggal_ditutup',
        'pemenang_id',
        'foto_produk',
        'katalog_id',
    ];

    protected $casts = [
        'tanggal_dibuka' => 'datetime',
        'tanggal_ditutup' => 'datetime',
    ];

    // reference this katalog_id ke katalogs id
    public function katalog()
    {
        return $this->belongsTo(M_Katalog::class);
    }
    // reference this pemenang_id ke pasang_lelangs id
    public function pemenang()
    {
        return $this->belongsTo(M_PasangLelang::class);
    }

    // deklarasi this lelang_id bisa punya banyak lelang_id di pasang_lelangs
    public function pasangLelang()
    {
        return $this->hasMany(M_PasangLelang::class);
    }
    // deklarasi this lelang_id bisa punya banyak lelang_id di transaksis
    public function transaksi()
    {
        return $this->hasMany(M_Transaksi::class);
    }
    // deklarasi this lelang_id bisa punya banyak lelang_id di notifications
    public function notification()
    {
        return $this->hasMany(M_Notification::class);
    }
}
