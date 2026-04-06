<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'name',
        'image_path',
        'date',
        'is_active',
        'location',     // Added this
        'video_url', 
        'description', 
        'early_bird_limit', 
    ];

    public function tickets()
    {
        // Second parameter is the foreign key on tickets table
        // Third parameter is the local key on events table
        return $this->hasMany(Ticket::class, 'event', 'name'); 
    }

    public function ticketTypes()
    {
        return $this->hasMany(EventTicketType::class);
    }

    protected $casts = [
        'date' => 'date',
    ];
}