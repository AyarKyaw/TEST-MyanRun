<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Athlete extends Model
{
    protected $fillable = [
    'runner_id', // MUST BE HERE
    'face_image_path',
    'nat_type',
    'id_number',
    'first_name',
    'middle_name',
    'last_name',
    'father_name',
    'dob',
    'nationality',
    'gender',
    'address',
    'phone_2',
    'social_account',
];

    /**
     * Get the User associated with this Athlete
     */
    public function user()
    {
        // point runner_id in this table to runner_id in User table
        return $this->belongsTo(User::class, 'runner_id', 'runner_id');
    }
}