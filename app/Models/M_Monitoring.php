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

    // reference this pegawai_id ke user id
    public function pegawai()
    {
        return $this->belongsTo(User::class, 'pegawai_id');
    }
}
