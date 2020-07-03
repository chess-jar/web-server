<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    /** @var array $fillable The attributes that are mass assignable. */
    protected $fillable = [
        'user_id', 'phone',
    ];

    /** @var array $casts The attributes that should be cast to native types. */
    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the phone.
     *
     * @return mixed Returns the relationship.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
