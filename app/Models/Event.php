<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'name',
        'image_path',
        'date',
        'is_active',
        'location',     // Added this
        'video_url', 
        'description', 
    ];

    protected $casts = [
        'date' => 'date',
    ];
}