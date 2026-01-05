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
        Schema::create('enrollment_applications', function (Blueprint $table) {
            $table->id();

            // Application Details (Mirroring Student Details)
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('grade_level_applied');

            // Processing Status
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected
            $table->string('rejection_reason')->nullable(); // Kung bakit ni-reject
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_applications');
    }
};
