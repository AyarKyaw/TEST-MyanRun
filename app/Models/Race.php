<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Race extends Model
{
    protected $fillable = ['name', 'slug', 'is_active'];

    protected static function boot() {
        parent::boot();
        static::creating(function ($race) {
            $race->slug = Str::slug($race->name);
        });
    }

    public function cards() {
        return $this->hasMany(RaceCard::class);
    }
}