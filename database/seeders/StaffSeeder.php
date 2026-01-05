<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    public function run()
    {
        Staff::create(['first_name' => 'Maria', 'last_name' => 'Reyes', 'role' => 'Adviser', 'email' => 'm.reyes@nas.edu']);
        Staff::create(['first_name' => 'Jose', 'last_name' => 'Dimaano', 'role' => 'Coach', 'email' => 'j.dimaano@nas.edu']);
        Staff::create(['first_name' => 'Ernesto', 'last_name' => 'Lopez', 'role' => 'Teacher', 'email' => 'e.lopez@nas.edu']);
    }
}