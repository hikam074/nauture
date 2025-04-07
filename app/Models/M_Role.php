<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'nama_role'
    ];

    // deklarasi this role_id bisa punya banyak role_id di users
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
