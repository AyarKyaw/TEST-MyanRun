<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Clear existing data to prevent duplicate errors
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('admins')->truncate();
        DB::table('dinners')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Create the Admin Account
        Admin::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // 3. Insert the Golden Duck Dinner using your exact column names
        DB::table('dinners')->insert([
            'id' => 1,
            'company' => 'MYAN RUN',
            'location' => 'Golden Duck (Kan Taw Min), Yangon',
            'public_capacity' => 200,
            'sponsor_capacity' => 100,
            'name' => 'KBZ Community 10 Mile Run 2026',
            'image_path' => 'dinners/reZanpluQpK3CirJvI7tN2e2dHpOuyyVsT01Cnqj.jpg',
            'info_image' => NULL,
            'date' => '2026-04-03',
            'is_active' => 1,
            'created_at' => '2026-03-14 12:20:05',
            'updated_at' => '2026-03-14 13:25:36',
            'capacity' => 300,
        ]);

        echo "🚀 Database seeded: Admin and MyanRun Dinner are ready!\n";
    }
}