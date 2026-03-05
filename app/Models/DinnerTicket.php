<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DinnerTicket extends Model
{
    protected $fillable = ['dinner_register_id', 'ticket_no', 'type', 'price', 'status', 'payment_slip', 'dinner_id'];

    public function registration() {
        return $this->belongsTo(DinnerRegister::class, 'dinner_register_id');
    }
}
