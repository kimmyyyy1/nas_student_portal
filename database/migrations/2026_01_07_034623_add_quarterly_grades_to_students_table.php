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
            // Nagdadagdag tayo ng 4 na columns para sa Grades
            // 'nullable' kasi baka wala pang grade sa start ng school year
            // 'after' para maayos ang pwesto sa database
            
            $table->double('q1')->nullable()->after('sex'); 
            $table->double('q2')->nullable()->after('q1');
            $table->double('q3')->nullable()->after('q2');
            $table->double('q4')->nullable()->after('q3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Ito ang gagawin kapag nag "migrate:rollback" ka (tatanggalin ang columns)
            $table->dropColumn(['q1', 'q2', 'q3', 'q4']);
        });
    }
};