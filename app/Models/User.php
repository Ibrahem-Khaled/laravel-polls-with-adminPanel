<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id'];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function polls()
    {
        return $this->hasMany(Poll::class);
    }

    public function answers()
    {
        return $this->belongsToMany(Option::class, 'option_users', 'user_id', 'option_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function userExpoToken()
    {
        return $this->hasOne(userPushToken::class);
    }
 

    /////////////////////////////////////////////////////////////
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
