<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Alamat extends Model
{
    protected $table = 'alamats';

    protected $fillable = [
        'provinsi_id',
        'city_id',
        'detail_alamat',
        'kode_pos'
    ];

    // reference this provinsi_id ke cities id
    public function provinsi()
    {
        return $this->belongsTo(M_Provinsi::class, 'provinsi_id');
    }
    // reference this city_id ke cities id
    public function city()
    {
        return $this->belongsTo(M_City::class, 'city_id');
    }
    // function mengambil semua alamat
}
