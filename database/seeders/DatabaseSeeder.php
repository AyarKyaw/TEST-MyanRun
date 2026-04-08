<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Athlete;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('admins')->truncate();
        DB::table('events')->truncate();
        DB::table('event_ticket_types')->truncate(); // Updated table name
        DB::table('athletes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Create the Super Admin
        Admin::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // 3. Create a Real Event (Cherry Trail Run 2026)
        DB::table('events')->insert([
            'id' => 1,
            'company' => 'MYAN RUN',
            'name' => 'Cherry Trail Run 2026',
            'early_bird_limit' => 50,
            'image_path' => 'events/M9KwAiaYYMkPlWzxS478ndRW6Jmk9e7nj8uQDCBA.jpg',
            'date' => '2026-12-15',
            'location' => 'Pyin Oo Lwin',
            'is_active' => 1,
            'description' => 'A scenic trail run through the cherry blossoms.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Create Event Ticket Types with Dual Pricing & BIB Config
        DB::table('event_ticket_types')->insert([
            [
                'id' => 1,
                'event_id' => 1,
                'name' => '21K Half Marathon',
                'type' => 'solo',
                'national_price' => '45000',
                'foreign_price' => '60', // USD or higher value
                'max_slots' => 200,
                'prefix' => 'CTR21',
                'start_number' => 1001,
                'has_gender_bib' => 1,
                'early_bird_limit' => 50,
                'early_bird_discount' => '5000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'event_id' => 1,
                'name' => '10K Challenge',
                'type' => 'solo',
                'national_price' => '25000',
                'foreign_price' => '35',
                'max_slots' => 300,
                'prefix' => 'CTR10',
                'start_number' => 5001,
                'has_gender_bib' => 0,
                'early_bird_limit' => 50,
                'early_bird_discount' => '3000',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        User::create([
            'runner_id' => 'RUN-0001000',
            'first_name' => 'Ayar',
            'last_name' => 'Kyaw',
            'email' => 'rooney.ayarkyaw@gmail.com',
            'phone' => '09123456789', // Match 'phone' column in users
            'password' => Hash::make('password'),
        ]);

        // 5. Create a Real Athlete
        Athlete::create([
            'runner_id'        => 'RUN-0001000',
            'first_name'       => 'Ayar',
            'last_name'        => 'Kyaw',
            'middle_name'      => null, // Added based on your table list
            'father_name'      => 'U Ba',
            'blood_type'       => 'B',
            // Using 'phone_2' since 'contact' was missing in your column list
            'phone_2'          => '09123456789', 
            'gender'           => 'male',
            'dob'              => '1995-05-20',
            'nationality'      => 'Myanmar',
            'id_number'        => '12/YAKANA(N)123456',
            'nat_type'         => 'national',
            'has_itra'         => 0,
            'medical_details'  => 'None',
            'address'          => 'Yangon, Myanmar',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        echo "🚀 Seeded: Cherry Trail (10K/21K) with BIB prefixes and pricing ready!\n";
    }
}