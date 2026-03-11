<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DinnerRegister extends Model
{
    use HasFactory;

    // This points the model to your specific table name
    protected $table = 'dinner_registers';

    // These are the only fields we are allowing to be saved
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'viber',
    ];

    /**
     * Optional: Relationship back to the User who made the booking
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(DinnerTicket::class, 'dinner_register_id');
    }
}