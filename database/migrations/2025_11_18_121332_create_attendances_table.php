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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // Sino ang inatendan?
            $table->foreignId('student_id')->constrained()->onDelete('cascade');

            // Anong klase (schedule) ang inatendan?
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');

            // Kailan ito?
            $table->date('date');

            // Ano ang status?
            $table->string('status'); // e.g., Present, Absent, Late, Excused

            // Para maiwasan ang duplicate entries (bawal ang isang student twice sa isang klase sa isang araw)
            $table->unique(['student_id', 'schedule_id', 'date']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
