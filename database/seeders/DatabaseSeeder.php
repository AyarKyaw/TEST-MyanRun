<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Sponsor;
use App\Models\SponsorCode;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // DELETE OR COMMENT OUT the default User::factory line!
        // It's trying to insert into a table that doesn't match your new design.

        // 1. Create your Admin
        Admin::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}