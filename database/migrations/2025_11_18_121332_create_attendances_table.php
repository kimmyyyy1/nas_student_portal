<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // 👇 GINAMIT NATIN AY SECTION ID PARA TUMUGMA SA UI MO
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('section_id')->constrained()->onDelete('cascade'); 

            $table->date('date');
            $table->string('status'); // Present, Late, Absent, Excused

            // Bawal ang duplicate attendance para sa isang student sa isang section sa iisang araw
            $table->unique(['student_id', 'section_id', 'date']);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};