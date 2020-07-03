<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /** @var array $fillable The attributes that are mass assignable. */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /** @var array $hidden The attributes that should be hidden for arrays. */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /** @var array $casts The attributes that should be cast to native types. */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
