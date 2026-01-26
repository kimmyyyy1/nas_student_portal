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
        // Pinalitan ko ang name mula 'enrollment_applications' -> 'applicants'
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            
            // Link to User table (Para alam natin kung sinong user ang may-ari nito)
            $table->unsignedBigInteger('user_id')->nullable(); 

            // 1. Applicant Info
            $table->string('lrn')->nullable();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->date('date_of_birth');
            $table->integer('age')->nullable();
            $table->string('gender');
            $table->string('birthplace')->nullable();
            $table->string('religion')->nullable();
            $table->string('email_address')->nullable();

            // 2. Address Info
            $table->string('region')->nullable();
            $table->string('province')->nullable();
            $table->string('municipality_city')->nullable();
            $table->string('barangay')->nullable();
            $table->string('street_address')->nullable();
            $table->string('zip_code')->nullable();

            // 3. Academic & Sports
            $table->string('previous_school')->nullable();
            $table->string('school_type')->nullable();
            $table->string('grade_level_applied')->nullable();
            $table->string('sport')->nullable();
            $table->string('sport_specification')->nullable(); // For specific events
            
            // Achievements
            $table->boolean('has_palaro_participation')->default(0);
            $table->string('palaro_year')->nullable();
            $table->string('batang_pinoy_finisher')->nullable();

            // 4. Background & Special Categories (Yung mga bago nating dinagdag)
            $table->string('learn_about_nas')->nullable();
            $table->string('referrer_name')->nullable();
            $table->string('attended_campaign')->nullable();
            
            $table->boolean('is_ip')->default(0);
            $table->string('ip_group_name')->nullable();
            
            $table->boolean('is_pwd')->default(0);
            $table->string('pwd_disability')->nullable();
            
            $table->boolean('is_4ps')->default(0);

            // 5. Guardian Info
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->string('guardian_email')->nullable();

            // 6. Requirements & System Fields
            $table->json('uploaded_files')->nullable(); // Para sa mga pictures/pdf
            $table->string('status')->default('Pending'); // Pending, Qualified, Not Qualified
            $table->text('assessment_score')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};