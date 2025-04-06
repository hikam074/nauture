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

}
