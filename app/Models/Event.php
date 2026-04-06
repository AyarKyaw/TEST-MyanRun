<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'name',
        'image_path',
        'date',
        'is_active',
        'location',
        'video_url', 
        'description', 
        'early_bird_limit', 
    ];

    /**
     * Relationship: The admins assigned to this event.
     */
    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(Admin::class, 'admin_event');
    }

    public function tickets()
    {
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