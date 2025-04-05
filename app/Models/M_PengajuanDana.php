<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class M_PengajuanDana extends Model
{
    use SoftDeletes;

    protected $table = 'pengajuan_danas';

    protected $fillable = [
        'deskripsi',
        'total_nominal_diajukan',
        'jumlah_akan_dibeli',
        'monitoring_id',
        'inventori_id',
        'status_pengajuan_id',
    ];
}
