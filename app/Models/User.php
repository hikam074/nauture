<?php

namespace App\Models;

use App\Models\M_Role;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

     protected $table = 'users';

     protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'=> 3,
        'isSuspended',
        'alamat',
        'no_telp',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // reference this role_id ke roles id
    public function role()
    {
        return $this->belongsTo(M_Role::class, 'role_id');
    }

    // method mencari role ybs
    public function hasRole($role)
    {
        return $this->role->name === $role;
    }
}
