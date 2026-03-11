<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $fillable = [
    'company', 
    'contact_name', 
    'phone', 
    'email', 
    'viber', 
    'quantity'
    // Remove 'status' and 'transaction_date' from here
];

    public function codes()
    {
        return $this->hasMany(SponsorCode::class);
    }

    // Add this helper to make it easy to get only the ones already used
    public function usages()
    {
        return $this->hasMany(SponsorCode::class)->where('is_used', true);
    }

    public function sponsorCode() {
        return $this->hasOne(SponsorCode::class);
    }

    public function tickets()
    {
        // A Sponsor has many tickets linked by sponsor_id
        return $this->hasMany(\App\Models\DinnerTicket::class, 'sponsor_id');
    }
}