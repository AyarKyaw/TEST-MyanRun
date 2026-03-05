<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dinner extends Model
{
    protected $fillable = ['company', 'name', 'image_path', 'date', 'is_active', 'location'];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    // Keep this if you use it elsewhere
    public function registrations() {
        return $this->hasMany(DinnerTicket::class);
    }

    // ADD THIS: This allows the Controller to use withCount(['tickets as ...'])
    public function tickets() {
        return $this->hasMany(DinnerTicket::class, 'dinner_id');
    }
}