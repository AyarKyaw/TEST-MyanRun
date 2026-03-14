<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponsorCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'sponsor_id',
        'dinner_id',
        'dinner_ticket_id',
        'dinner_register_id', // Link to the ID for data integrity
        'code', 
        'max_uses',
        'used_count',
        'status',
        'used_by_name' // Store the string name for quick display
    ];

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }

    public function ticket()
    {
        // This links the 'dinner_ticket_id' column to the DinnerTicket model
        return $this->belongsTo(DinnerTicket::class, 'dinner_ticket_id');
    }

    // Link to the user registration
    public function registration()
    {
        return $this->belongsTo(DinnerRegister::class, 'dinner_register_id');
    }
}