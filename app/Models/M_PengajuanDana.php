<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class M_PengajuanDana extends Model
{
    use SoftDeletes;

    protected $table = 'pengajuan_danas';

    protected $fillable = [
        'kode_pengajuan',
        'deskripsi',
        'total_nominal_diajukan',
        'jumlah_akan_dibeli',
        'monitoring_id',
        'inventori_id',
        'status_pengajuan_id',
    ];

    // reference this status_pengajuan_id ke status_pengajuans id
    public function statusPengajuan()
    {
        return $this->belongsTo(M_StatusPengajuan::class, 'status_pengajuan_id');
    }

    // reference this inventori_id ke inventoris id
    public function inventori()
    {
        return $this->belongsTo(M_Inventori::class, 'inventori_id');
    }

}
