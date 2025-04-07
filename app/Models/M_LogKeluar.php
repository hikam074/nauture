<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_LogKeluar extends Model
{
    protected $table = 'log_keluars';

    protected $fillable = [
        'pengajuan_dana_id',
        'deskripsi',
        'jumlah_keluar',
        'user_id',
    ];

    // reference this pengajuan_dana_id ke pengajuan_danas id
    public function pengajuanDana()
    {
        return $this->belongsTo(M_PengajuanDana::class);
    }
    // reference this user_id ke users id
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
