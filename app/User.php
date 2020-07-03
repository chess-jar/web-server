<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, LaratrustUserTrait, Notifiable;

    /** @var array $fillable The attributes that are mass assignable. */
    protected $fillable = [
        'name', 'username', 'email', 'password',
    ];

    /** @var array $hidden The attributes that should be hidden for arrays. */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /** @var array $casts The attributes that should be cast to native types. */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the phone record associated with the user.
     *
     * @return mixed Returns the relationship.
     */
    public function phone()
    {
        return $this->hasOne('App\Phone');
    }

    /**
     * Log the users ip address.
     *
     * @param bool $registering Is the user registering.
     *
     * @return void Returns nothing.
     */
    protected function logIp($registering = false)
    {
        $currentIp = request()->ip();
        if ($registering) {
            $user->register_ip_address = $currentIp;
        }
        $user->last_login_ip_address = $currentIp;
        $user->save();
    }
}
