<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class M_PasangLelang extends Model
{
    use SoftDeletes;

    protected $table = 'pasang_lelangs';

    protected $fillable = [
        'harga_pengajuan',
        'kode_lelang',
        'user_id',
    ];

    // reference this kode_lelang ke lelangs kode_lelang
    public function lelang()
    {
        return $this->belongsTo(M_Lelang::class, 'kode_lelang', 'kode_lelang');
    }

    // reference this user_id ke user id
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
