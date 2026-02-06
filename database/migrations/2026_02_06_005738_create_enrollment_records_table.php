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
        Schema::create('enrollment_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');
            
            // Forms & Requirements (Stored as Cloudinary URLs or file paths)
            $table->string('scholarship_form')->nullable();
            $table->string('student_athlete_profile_form')->nullable();
            $table->string('ppe_clearance_form')->nullable();
            $table->string('psa_birth_certificate')->nullable();
            $table->string('report_card')->nullable();
            $table->string('guardian_valid_id')->nullable();
            
            // Conditional Requirements
            $table->string('kukkiwon_certificate')->nullable(); // Taekwondo
            $table->string('ip_certification')->nullable();   // IP Member
            $table->string('pwd_id')->nullable();              // PWD
            $table->string('four_ps_certification')->nullable(); // 4Ps

            $table->enum('enrollment_status', ['Pending', 'Verified', 'Enrolled'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_records');
    }
};
