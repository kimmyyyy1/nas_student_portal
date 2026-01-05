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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->text('description'); // e.g., "Enrollment submitted: Apolinario Mabini..."
            $table->string('model_type')->nullable(); // e.g., "Student", "Section"
            $table->unsignedBigInteger('model_id')->nullable(); // e.g., 1 (ang ID ng student)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
