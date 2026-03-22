<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
public function tickets(): HasMany
{
    // The second parameter tells Laravel to look for 'athlete_id' instead of 'user_id'
    return $this->hasMany(Ticket::class, 'athlete_id');
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
}