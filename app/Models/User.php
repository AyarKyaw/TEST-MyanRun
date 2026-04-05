<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'runner_id', // Added this
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
public function tickets(): HasManyThrough
{
    return $this->hasManyThrough(
        Ticket::class,     // The final model we want (Ticket)
        Athlete::class,    // The middle model (Athlete)
        'runner_id',       // Foreign key on Athletes table (User's runner_id)
        'athlete_id',      // Foreign key on Tickets table (Athlete's id)
        'runner_id',       // Local key on Users table
        'id'               // Local key on Athletes table
    );
}

    public function athlete()
    {
        return $this->hasOne(Athlete::class, 'runner_id', 'runner_id');
    }

    public function getFullNameAttribute()
{
    return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
}
}