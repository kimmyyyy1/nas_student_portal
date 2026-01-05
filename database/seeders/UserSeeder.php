<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. REGISTRAR (Admin)
        User::create([
            'name' => 'Registrar Admin',
            'email' => 'admin@nas.edu',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. COACH
        User::create([
            'name' => 'Coach Dimaano',
            'email' => 'coach@nas.edu',
            'password' => Hash::make('password'),
            'role' => 'coach',
        ]);

        // 3. TEACHER
        User::create([
            'name' => 'Teacher Santos',
            'email' => 'teacher@nas.edu',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        // 4. SASS (Medical, Nutrition, Guidance) 
        // Role changed from 'medical' to 'sass'
        User::create([
            'name' => 'SASS Officer',
            'email' => 'sass@nas.edu',
            'password' => Hash::make('password'),
            'role' => 'sass',
        ]);
        
        // 5. ICT
        User::create([
            'name' => 'ICT Support',
            'email' => 'ict@nas.edu',
            'password' => Hash::make('password'),
            'role' => 'admin', 
        ]);
    }
}