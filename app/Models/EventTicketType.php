<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTicketType extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'type',
        'price',
        'max_slots',
        'image',
        'foreign_price',
        'national_price',
        'national_image',
        'foreign_image',
        'prefix',
        'start_number'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
