<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaceCard extends Model
{
    protected $fillable = ['race_id', 'title', 'image'];

    public function race() {
        return $this->belongsTo(Race::class);
    }
}