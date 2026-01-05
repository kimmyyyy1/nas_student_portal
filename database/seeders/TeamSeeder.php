<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    public function run()
    {
        Team::create(['team_name' => 'Volleyball - Girls', 'sport' => 'Volleyball', 'coach_name' => 'Maria Reyes']);
        Team::create(['team_name' => 'Track & Field - Boys', 'sport' => 'Track & Field', 'coach_name' => 'Jose Dimaano']);
        Team::create(['team_name' => 'E-Sports League', 'sport' => 'E-Sports', 'coach_name' => 'Ernesto Lopez']);
    }
}