<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Photo extends Model
{
    protected $fillable = ['filename', 'storage_path', 'event_id'];

    /**
     * Get all BIB numbers captured inside this specific photo.
     */
    public function bibs(): HasMany
    {
        return $this->hasMany(PhotoBib::class, 'photo_id');
    }
}