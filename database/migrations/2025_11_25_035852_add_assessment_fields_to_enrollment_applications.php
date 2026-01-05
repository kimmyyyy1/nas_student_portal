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
        Schema::table('enrollment_applications', function (Blueprint $table) {
            // Assessment Score (e.g., 85.50)
            $table->decimal('assessment_score', 5, 2)->nullable()->after('status'); 
            
            // Rank (e.g., 1, 2, 3...)
            $table->integer('rank')->nullable()->after('assessment_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollment_applications', function (Blueprint $table) {
            //
        });
    }
};
