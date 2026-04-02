<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTicketType extends Model
{
    protected $fillable = [
        'event_id', 'name', 'type', 'national_price', 'foreign_price', 
        'max_slots', 'prefix', 'start_number', 'category', 
        'national_image', 'foreign_image', 'ticket_png',
        'has_gender_bib', 'early_bird_limit', 'early_bird_discount'
    ];
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
