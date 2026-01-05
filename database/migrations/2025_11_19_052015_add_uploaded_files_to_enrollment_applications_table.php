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
            // Dito natin ise-save ang file paths (JSON format)
            $table->json('uploaded_files')->nullable(); 
            
            // Pwede na nating tanggalin ang lumang checklist column kung gusto mo, 
            // pero hayaan muna natin para safe.
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
