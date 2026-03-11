<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponsorCode extends Model
{
    use HasFactory;

    // Add this array to allow these fields to be saved in bulk
    protected $fillable = [
        'sponsor_id', 
        'code', 
        'is_used', 
        'used_by_name' // <--- Add this!
    ];

    /**
     * Relationship back to the Sponsor
     */
    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }
}
