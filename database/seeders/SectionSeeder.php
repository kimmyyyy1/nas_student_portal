<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionSeeder extends Seeder
{
    public function run()
    {
        // Grade 7 Sections
        Section::create(['section_name' => 'Diamond', 'grade_level' => 'Grade 7', 'adviser_name' => 'Mr. Ramos']);
        Section::create(['section_name' => 'Pearl', 'grade_level' => 'Grade 7', 'adviser_name' => 'Ms. Santos']);

        // Grade 8 Sections
        Section::create(['section_name' => 'Ruby', 'grade_level' => 'Grade 8', 'adviser_name' => 'Mrs. Garcia']);
        Section::create(['section_name' => 'Sapphire', 'grade_level' => 'Grade 8', 'adviser_name' => 'Mr. Tan']);

        // Grade 9 Sections
        Section::create(['section_name' => 'Jasper', 'grade_level' => 'Grade 9', 'adviser_name' => 'Ms. Lee']);
        Section::create(['section_name' => 'Onyx', 'grade_level' => 'Grade 9', 'adviser_name' => 'Mr. Cruz']);

        // Grade 10 Sections
        Section::create(['section_name' => 'Amethyst', 'grade_level' => 'Grade 10', 'adviser_name' => 'Mrs. Diaz']);
        Section::create(['section_name' => 'Jade', 'grade_level' => 'Grade 10', 'adviser_name' => 'Mr. Perez']);
    }
}