<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SectionSeeder::class,
            StaffSeeder::class,
            SubjectSeeder::class,
            TeamSeeder::class,
            // Pwede mo ring i-run ang UserSeeder para sa default admin account, kung ginawa mo
        ]);
    }
}