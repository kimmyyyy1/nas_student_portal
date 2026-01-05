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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            // Foreign key para sa Subject
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            
            // Foreign key para sa Section
            $table->foreignId('section_id')->constrained()->onDelete('cascade');

            // Foreign key para sa Staff (ang guro)
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');

            // Impormasyon ng Schedule
            $table->string('day'); // e.g., "MWF" (Monday-Wednesday-Friday) or "TTh"
            $table->time('time_start'); // Oras ng simula
            $table->time('time_end');   // Oras ng tapos
            $table->string('room')->nullable(); // e.g., "Room 101" (Optional)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
