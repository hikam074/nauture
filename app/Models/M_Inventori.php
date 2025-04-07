<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class M_Inventori extends Model
{
    use SoftDeletes;

    protected $table = 'inventoris';

    protected $fillable = [
        'nama_inventori',
        'jumlah_stok',
        'foto_inventori'
    ];

    // deklarasi this inventori_id bisa punya banyak inventori_id di pengajuan_danas
    public function pengajuanDana()
    {
        return $this->hasMany(M_PengajuanDana::class);
    }
}
