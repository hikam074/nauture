<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_StatusPengajuan extends Model
{
    protected $table = 'status_pengajuans';

    protected $fillable = [
        'kode_status_pengajuan',
        'nama_status_pengajuan',
    ];

    // deklarasi this status_pengajuan_id bisa punya banyak status_pengajuan_id di pengajuan_danas
    public function pengajuanDana()
    {
        return $this->hasMany(M_PengajuanDana::class);
    }
}
