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
        Schema::table('students', function (Blueprint $table) {
            $table->string('sport')->nullable()->after('team_id');
            $table->string('sport_specification')->nullable()->after('sport');
            $table->string('ip_group_name')->nullable()->after('is_ip');
            $table->string('pwd_disability')->nullable()->after('is_pwd');
            
            $table->string('heard_about_nas')->nullable();
            $table->string('referrer_name')->nullable();
            $table->string('attended_articulation')->default('No');

            $table->string('school_name')->nullable();
            $table->string('school_type')->nullable();
            $table->string('last_grade_level')->nullable();
            $table->string('last_school_year')->nullable();
            $table->string('school_id')->nullable();
            $table->string('school_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'sport',
                'sport_specification',
                'ip_group_name',
                'pwd_disability',
                'heard_about_nas',
                'referrer_name',
                'attended_articulation',
                'school_name',
                'school_type',
                'last_grade_level',
                'last_school_year',
                'school_id',
                'school_address'
            ]);
        });
    }
};
