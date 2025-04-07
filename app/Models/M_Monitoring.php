<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Monitoring extends Model
{
    protected $table = 'monitorings';

    protected $fillable = [
        'user_id',
        'deskripsi',
        'foto_monitoring',
    ];

    // reference this user_id ke users id
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // deklarasi this monitoring_id bisa punya banyak monitoring_id di pengajuan_danas
    public function pengajuanDana()
    {
        return $this->hasMany(M_PengajuanDana::class);
    }
}
