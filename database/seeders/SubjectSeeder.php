<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        Subject::create(['subject_code' => 'PE101', 'subject_name' => 'Fitness and Sports', 'description' => 'Core PE subject']);
        Subject::create(['subject_code' => 'FIL11', 'subject_name' => 'Filipino Language and Culture', 'description' => 'Core subject']);
        Subject::create(['subject_code' => 'BIO101', 'subject_name' => 'General Biology', 'description' => 'Science subject']);
    }
}