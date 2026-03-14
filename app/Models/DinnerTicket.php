<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DinnerTicket extends Model
{
    // Ensure you have these in your $fillable array as well
    protected $fillable = [
        'sponsor_id', 
        'dinner_id', 
        'dinner_register_id', 
        'ticket_no', 
        'type', 
        'status', 
        'price', 
        'quantity',
        'payment_slip'
    ];

    /**
     * Define the relationship to the Dinner.
     */
    public function dinner()
    {
        return $this->belongsTo(Dinner::class);
    }

    /**
     * Define the relationship to the Registration.
     */
    public function registration()
    {
        return $this->belongsTo(DinnerRegister::class, 'dinner_register_id');
    }

    /**
     * Define the relationship to the Sponsor (if applicable).
     */
    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }
}