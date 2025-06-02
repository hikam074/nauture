<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Provinsi extends Model
{
    protected $table = 'provinsis';

    protected $fillable = [
        'provinsi_id',
        'nama_provinsi',
    ];

    // deklarasi this provinsi_id bisa punya banyak provinsi_id di provinsis
    public function city()
    {
        return $this->hasMany(M_City::class, 'provinsi_id');
    }
}
