<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
    'runner_id', 
    'category', 
    'event', 
    'price', 
    'status', 
    'transaction_id', 
    'payment_method', 
    'qr_code_str'
];
}