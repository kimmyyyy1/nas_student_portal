<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->unique();
            $table->string('setting_value')->nullable();
            $table->timestamps();
        });

        // Default Data
        DB::table('system_settings')->insert([
            ['setting_key' => 'current_school_year', 'setting_value' => '2025-2026', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'admission_start_date', 'setting_value' => '2026-01-01', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'admission_end_date', 'setting_value' => '2026-05-31', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'enrollment_start_date', 'setting_value' => '2026-06-01', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'enrollment_end_date', 'setting_value' => '2026-08-31', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};