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

        // 2. Create a Sample Sponsor
        $sponsor = Sponsor::create([
            'name' => 'KBZ Bank',
            'contact_name' => 'Daw Thandar',
            'phone' => '0912345678',
            'email' => 'contact@kbzbank.com',
            'status' => 'active',
        ]);

        // 3. Create a Code for that Sponsor
        SponsorCode::create([
            'sponsor_id' => $sponsor->id,
            'code' => 'KBZ-FREE-2026',
            'discount' => 100,
            'max_uses' => 50,
            'used_count' => 0,
        ]);
    }
}