<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'dinner_id',      // Ensure this is here
        'company',
        'contact_name',
        'email',
        'phone',
        'viber',
        'quantity',
        'status'
    ];

    /**
     * Get the dinner that this sponsor is assigned to.
     */
    public function dinner()
    {
        return $this->belongsTo(Dinner::class, 'dinner_id');
    }

    /**
     * Get the invitation codes for this sponsor.
     */
    public function codes()
    {
        // Assuming your table is sponsor_codes and has a sponsor_id column
        return $this->hasMany(SponsorCode::class);
    }

    /**
     * Get the actual tickets generated for this sponsor.
     */
    public function tickets()
    {
        return $this->hasMany(DinnerTicket::class);
    }
}