<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoBib extends Model
{
    protected $fillable = ['photo_id', 'bib_number'];

    /**
     * Get the original photo resource that belongs to this specific BIB entry.
     */
    public function photo(): BelongsTo
    {
        return $this->belongsTo(Photo::class, 'photo_id');
    }
}