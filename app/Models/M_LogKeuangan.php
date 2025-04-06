<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class M_LogKeuangan extends Model
{
    use SoftDeletes;

    protected $table = 'log_keuangans';

    protected $fillable = [
        'deskripsi',
        'jumlah',
        'user_id',
        'kode_transaksi',
        'kode_pengajuan',
    ];

    // reference this kode_pengajuan ke pengajuan_danas kode_pengajuan
    public function pengajuanDana()
    {
        return $this->belongsTo(M_PengajuanDana::class, 'kode_pengajuan', 'kode_pengajuan');
    }

    // reference this kode_transaksi ke transaksis kode_transaksi
    public function transaksi()
    {
        return $this->belongsTo(M_Transaksi::class, 'kode_transaksi', 'kode_transaksi');
    }

    // reference this user_id ke user id
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
