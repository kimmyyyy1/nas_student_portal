<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('enrollment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
            
            // STUDENT INFO
            $table->string('lrn', 12);
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('extension_name')->nullable();
            $table->date('date_of_birth');
            $table->integer('age');
            $table->string('sex'); // Male, Female
            
            // ADDRESS
            $table->string('region');
            $table->string('province');
            $table->string('municipality_city');
            $table->string('barangay');
            $table->string('street_house_no');
            $table->string('zip_code');
            $table->string('email'); // Student Email

            // GROUPS
            $table->boolean('is_ip')->default(false);
            $table->string('ip_group')->nullable();
            $table->boolean('is_pwd')->default(false);
            $table->string('pwd_disability')->nullable();
            $table->boolean('is_4ps')->default(false);

            // SPORTS
            $table->string('sport');

            // PARENTS INFO
            $table->string('father_name')->nullable();
            $table->string('father_address')->nullable();
            $table->string('father_contact')->nullable();
            $table->string('father_email')->nullable();

            $table->string('mother_maiden_name')->nullable();
            $table->string('mother_address')->nullable();
            $table->string('mother_contact')->nullable();
            $table->string('mother_email')->nullable();

            $table->string('guardian_name'); // Required as per form
            $table->string('guardian_relationship');
            $table->string('guardian_address');
            $table->string('guardian_contact');
            $table->string('guardian_email');

            // SCHOOL INFO (Transfer Students)
            $table->string('last_grade_level');
            $table->string('last_school_year');
            $table->string('school_name');
            $table->string('school_id');
            $table->string('school_address');
            $table->string('school_type'); // Public, Private

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('enrollment_details');
    }
};