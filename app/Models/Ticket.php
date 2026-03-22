<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
    'athlete_id',
    'bib_name',
    'bib_number',
    'category', // <--- MAKE SURE THIS IS HERE
    'price',
    'event',
    'experience_level',
    'transaction_id',
    'payment_method',
    't_shirt_size',
    'payment_slip',
    'status',
    'ticket_no',
];

    /**
     * Get the athlete that owns the ticket.
     */
    public function athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class, 'athlete_id');
    }
}