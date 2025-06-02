<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_City extends Model
{
    protected $table = 'cities';

    protected $fillable = [
        'city_id',
        'nama_city',
        'provinsi_id',
    ];

    // reference this provinsi_id ke provinsis id
    public function provinsi()
    {
        return $this->belongsTo(M_Provinsi::class);
    }
    // deklarasi this city_id bisa punya banyak city_id di alamats
    public function alamat()
    {
        return $this->hasMany(M_Transaksi::class, 'transaksi_id');
    }
}
