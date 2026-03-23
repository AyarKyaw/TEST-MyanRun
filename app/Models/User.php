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
    /**
     * Auto-generate Runner ID on creation
     */
    protected static function booted()
{
    static::creating(function ($user) {
        // 1. Specifically look for the highest runner_id string
        $lastUser = self::where('runner_id', 'LIKE', 'RUN-%')
                        ->orderBy('runner_id', 'desc')
                        ->first();

        if (!$lastUser) {
            $number = 1;
        } else {
            // 2. Extract digits only from the string (e.g., "RUN-005" -> 5)
            $lastNumber = (int) preg_replace('/[^0-9]/', '', $lastUser->runner_id);
            $number = $lastNumber + 1;
        }

        // 3. Format it back to RUN-00X
        $user->runner_id = 'RUN-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    });
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