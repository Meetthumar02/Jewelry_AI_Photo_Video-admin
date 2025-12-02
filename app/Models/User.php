<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'total_credits',
        'is_subscribed',
        'brand_logo_path',
        'subscription_start',
        'subscription_end',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'   => 'datetime',
        'is_subscribed'       => 'boolean',
        'subscription_start' => 'datetime',
        'subscription_end'   => 'datetime',
    ];
}
