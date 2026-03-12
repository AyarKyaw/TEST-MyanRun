<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DinnerTicket extends Model
{
    protected $fillable = [
    'ticket_no',
    'registration_id',
    'dinner_id',
    'dinner_register_id',
    'sponsor_id',
    'type',
    'price',
    'quantity', // Add this
    'status',
    'payment_slip',
    'scanned_at'
];

protected $casts = [
    'scanned_at' => 'datetime',
];

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class, 'sponsor_id');
    }

    public function registration()
    {
        return $this->belongsTo(DinnerRegister::class, 'dinner_register_id');
    }
}
