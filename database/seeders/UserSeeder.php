<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // =========================================================
        // CENTRALIZED DEFAULT ACCOUNT 
        // =========================================================
        // IT / PICT Support is the ONLY seeded account. All other 
        // accounts (Registrar, Coach, Teacher, etc.) should be 
        // created manually via the Staff Management UI.
        
        User::updateOrCreate(
            ['email' => 'pict@nas.edu'],
            [
                'name' => 'PICT Support',
                'password' => Hash::make('password'),
                'role' => 'admin', 
            ]
        );
    }
}