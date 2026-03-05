<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // IMPORTANT
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}