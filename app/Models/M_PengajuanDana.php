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
        'monitoring_id',
        'deskripsi',
        'inventori_id',
        'total_nominal_diajukan',
        'jumlah_akan_dibeli',
        'status_pengajuan_id',
    ];

    // reference this monitoring_id ke monitorings id
    public function monitoring()
    {
        return $this->belongsTo(M_Monitoring::class, 'status_pengajuan_id');
    }
    // reference this inventori_id ke inventoris id
    public function inventori()
    {
        return $this->belongsTo(M_Inventori::class);
    }
    // reference this status_pengajuan_id ke status_pengajuans id
    public function statusPengajuan()
    {
        return $this->belongsTo(M_StatusPengajuan::class);
    }

    // deklarasi this pengajuan_dana_id bisa punya banyak pengajuan_dana_id di log_keluars
    public function logKeluar()
    {
        return $this->hasMany(M_LogKeluar::class);
    }
    // deklarasi this pengajuan_dana_id bisa punya banyak pengajuan_dana_id di xendit_payouts
    public function xenditPayout()
    {
        return $this->hasMany(M_XenditPayout::class);
    }
}
