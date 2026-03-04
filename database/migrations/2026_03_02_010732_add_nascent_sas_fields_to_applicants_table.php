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
        Schema::table('applicants', function (Blueprint $table) {
            // New School tracking information
            if (!Schema::hasColumn('applicants', 'school_last_grade_level')) {
                $table->string('school_last_grade_level')->nullable();
            }
            if (!Schema::hasColumn('applicants', 'school_last_year_completed')) {
                $table->string('school_last_year_completed')->nullable();
            }
            if (!Schema::hasColumn('applicants', 'school_id')) {
                $table->string('school_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'school_last_grade_level',
                'school_last_year_completed',
                'school_id'
            ]);
        });
    }
};
