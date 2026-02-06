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
        Schema::table('applicants', function (Blueprint $table) {
            
            // Father's Info
            if (!Schema::hasColumn('applicants', 'father_name')) {
                $table->string('father_name')->nullable();
            }
            if (!Schema::hasColumn('applicants', 'father_address')) {
                $table->string('father_address')->nullable();
            }
            if (!Schema::hasColumn('applicants', 'father_contact')) {
                $table->string('father_contact')->nullable();
            }
            if (!Schema::hasColumn('applicants', 'father_email')) {
                $table->string('father_email')->nullable();
            }

            // Mother's Info
            if (!Schema::hasColumn('applicants', 'mother_name')) {
                $table->string('mother_name')->nullable();
            }
            if (!Schema::hasColumn('applicants', 'mother_address')) {
                $table->string('mother_address')->nullable();
            }
            if (!Schema::hasColumn('applicants', 'mother_contact')) {
                $table->string('mother_contact')->nullable();
            }
            if (!Schema::hasColumn('applicants', 'mother_email')) {
                $table->string('mother_email')->nullable();
            }

            // Guardian's Extra Info
            if (!Schema::hasColumn('applicants', 'guardian_address')) {
                $table->string('guardian_address')->nullable();
            }

            // School History
            if (!Schema::hasColumn('applicants', 'last_grade_level')) {
                $table->string('last_grade_level')->nullable();
            }
            if (!Schema::hasColumn('applicants', 'last_school_year')) {
                $table->string('last_school_year')->nullable();
            }
            if (!Schema::hasColumn('applicants', 'school_id')) {
                $table->string('school_id')->nullable();
            }
            if (!Schema::hasColumn('applicants', 'school_address')) {
                $table->string('school_address')->nullable();
            }
            
            // Extension Name (kung wala pa)
            if (!Schema::hasColumn('applicants', 'extension_name')) {
                $table->string('extension_name')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            //
        });
    }
};
