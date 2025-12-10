<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $table = 'Admin';
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'mobile_number',
        'email_verified_at',
        'password',
        'role_id',
        'status',
        'remember_token',
        'created_at',
        'updated_at',
        'iStatus',
        'iSDelete',


    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $hidden = [
        'password'
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }
}
