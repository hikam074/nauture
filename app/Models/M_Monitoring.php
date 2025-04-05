<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Monitoring extends Model
{
    protected $table = 'monitorings';

    protected $fillable = [
        'deskripsi',
        'foto_monitoring',
        'pegawai_id',
    ];

    public function pengajuan_dana()
    {
        return $this->hasMany(M_PengajuanDana::class, 'monitoring_id');
    }
}
