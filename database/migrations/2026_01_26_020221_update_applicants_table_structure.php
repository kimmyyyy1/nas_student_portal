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
        // 1. Rename table if old name exists (enrollment_applications -> applicants)
        if (Schema::hasTable('enrollment_applications') && !Schema::hasTable('applicants')) {
            Schema::rename('enrollment_applications', 'applicants');
        }

        // 2. Add missing columns to 'applicants' table
        Schema::table('applicants', function (Blueprint $table) {
            
            // Check & Add: User ID Link
            if (!Schema::hasColumn('applicants', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            // Check & Add: LRN & Personal Info
            if (!Schema::hasColumn('applicants', 'lrn')) $table->string('lrn')->nullable()->after('user_id');
            if (!Schema::hasColumn('applicants', 'middle_name')) $table->string('middle_name')->nullable()->after('first_name');
            if (!Schema::hasColumn('applicants', 'age')) $table->integer('age')->nullable()->after('date_of_birth');
            if (!Schema::hasColumn('applicants', 'birthplace')) $table->string('birthplace')->nullable();
            if (!Schema::hasColumn('applicants', 'religion')) $table->string('religion')->nullable();
            if (!Schema::hasColumn('applicants', 'email_address')) $table->string('email_address')->nullable();

            // Check & Add: Address Info
            if (!Schema::hasColumn('applicants', 'region')) $table->string('region')->nullable();
            if (!Schema::hasColumn('applicants', 'province')) $table->string('province')->nullable();
            if (!Schema::hasColumn('applicants', 'municipality_city')) $table->string('municipality_city')->nullable();
            if (!Schema::hasColumn('applicants', 'barangay')) $table->string('barangay')->nullable();
            if (!Schema::hasColumn('applicants', 'street_address')) $table->string('street_address')->nullable();
            if (!Schema::hasColumn('applicants', 'zip_code')) $table->string('zip_code')->nullable();

            // Check & Add: Academic & Sports
            if (!Schema::hasColumn('applicants', 'previous_school')) $table->string('previous_school')->nullable();
            if (!Schema::hasColumn('applicants', 'school_type')) $table->string('school_type')->nullable();
            if (!Schema::hasColumn('applicants', 'sport')) $table->string('sport')->nullable();
            if (!Schema::hasColumn('applicants', 'sport_specification')) $table->string('sport_specification')->nullable();
            
            // Check & Add: Achievements
            if (!Schema::hasColumn('applicants', 'has_palaro_participation')) $table->boolean('has_palaro_participation')->default(0);
            if (!Schema::hasColumn('applicants', 'palaro_year')) $table->string('palaro_year')->nullable();
            if (!Schema::hasColumn('applicants', 'batang_pinoy_finisher')) $table->string('batang_pinoy_finisher')->nullable();

            // Check & Add: Background & Special Categories
            if (!Schema::hasColumn('applicants', 'learn_about_nas')) $table->string('learn_about_nas')->nullable();
            if (!Schema::hasColumn('applicants', 'referrer_name')) $table->string('referrer_name')->nullable();
            if (!Schema::hasColumn('applicants', 'attended_campaign')) $table->string('attended_campaign')->nullable();
            
            if (!Schema::hasColumn('applicants', 'is_ip')) $table->boolean('is_ip')->default(0);
            if (!Schema::hasColumn('applicants', 'ip_group_name')) $table->string('ip_group_name')->nullable();
            
            if (!Schema::hasColumn('applicants', 'is_pwd')) $table->boolean('is_pwd')->default(0);
            if (!Schema::hasColumn('applicants', 'pwd_disability')) $table->string('pwd_disability')->nullable();
            
            if (!Schema::hasColumn('applicants', 'is_4ps')) $table->boolean('is_4ps')->default(0);

            // Check & Add: Guardian Info
            if (!Schema::hasColumn('applicants', 'guardian_name')) $table->string('guardian_name')->nullable();
            if (!Schema::hasColumn('applicants', 'guardian_relationship')) $table->string('guardian_relationship')->nullable();
            if (!Schema::hasColumn('applicants', 'guardian_contact')) $table->string('guardian_contact')->nullable();
            if (!Schema::hasColumn('applicants', 'guardian_email')) $table->string('guardian_email')->nullable();

            // Check & Add: Requirements & System Fields
            if (!Schema::hasColumn('applicants', 'uploaded_files')) $table->json('uploaded_files')->nullable();
            if (!Schema::hasColumn('applicants', 'assessment_score')) $table->text('assessment_score')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Pwede nating i-drop ang mga columns kung gusto i-rollback, pero sa ngayon, hayaan na lang natin.
    }
};