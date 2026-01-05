<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('enrollment_applications', function (Blueprint $table) {
            // School Information 
            $table->string('previous_school')->nullable();
            $table->string('school_type')->nullable(); // Public or Private

            // Sports History 
            $table->boolean('has_palaro_participation')->default(false); // Y/N for Palaro/Batang Pinoy
            $table->string('palaro_year')->nullable(); // Year participated

            // Contact Info for Admission 
            $table->string('guardian_contact')->nullable();
            $table->string('email_address')->nullable();

            // Requirements Checklist (Stored as JSON array) 
            // Ito ang maglalaman ng listahan ng forms (Scholarship Form, Profile, etc.)
            $table->json('submitted_requirements')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollment_applications', function (Blueprint $table) {
            $table->dropColumn([
                'previous_school',
                'school_type',
                'has_palaro_participation',
                'palaro_year',
                'guardian_contact',
                'email_address',
                'submitted_requirements',
            ]);
        });
    }
};