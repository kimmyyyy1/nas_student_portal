<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // --- IDENTIFIERS  ---
            $table->string('nas_student_id')->unique()->nullable(); // NAS Student Number
            $table->string('lrn')->unique()->nullable(); // Learner Reference Number

            // --- PERSONAL INFORMATION  ---
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('sex'); // Sex 
            $table->date('birthdate'); // Birthdate 
            $table->integer('age')->nullable(); // Age 
            $table->string('birthplace')->nullable(); // Birthplace 
            $table->string('religion')->nullable(); // Religion 
            
            // --- SPECIAL STATUS (Checkboxes)  ---
            $table->boolean('is_ip')->default(false); // Indigenous People
            $table->boolean('is_pwd')->default(false); // Person with Disability
            $table->boolean('is_4ps')->default(false); // 4Ps Beneficiary

            // --- ACADEMIC & SPORTS INFO  ---
            $table->year('entry_year')->nullable(); // Entry Year
            $table->string('grade_level'); // Grade Level
            
            // Foreign Keys (Relasyon sa ibang tables na nagawa na natin)
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null'); // Sport 
            
            // --- CONTACT & ADDRESS  ---
            // Address breakdown
            $table->string('region')->nullable();
            $table->string('province')->nullable();
            $table->string('municipality_city')->nullable();
            $table->string('barangay')->nullable();
            $table->string('street_address')->nullable(); // Street & Zip code
            $table->string('zip_code')->nullable();
            
            $table->string('contact_number')->nullable(); // Student-Athlete’s Contact Number
            $table->string('email_address')->nullable(); // Student-Athlete’s Email Address

            // --- PARENT/GUARDIAN INFORMATION  ---
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->string('guardian_address')->nullable(); // Municipality/City, Province

            // --- SYSTEM STATUS ---
            // Status (New, Continuing, Transfer out, Graduate) 
            $table->string('status')->default('New'); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
