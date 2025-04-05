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
    public function lelang()
    {
        return $this->belongsTo(M_Lelang::class, 'kode_lelang', 'kode_lelang');
    }

}
