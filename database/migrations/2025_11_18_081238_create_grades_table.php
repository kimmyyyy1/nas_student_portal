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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();

            // Kaninong grado ito?
            $table->foreignId('student_id')->constrained()->onDelete('cascade');

            // Para saang klase (schedule) ito?
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');

            // Ano ang marka? (e.g., 95.50, 88.25)
            $table->decimal('mark', 5, 2); 

            // Anong grading period?
            $table->string('grading_period'); // e.g., "1st Quarter", "Final"
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
