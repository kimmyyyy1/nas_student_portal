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
        Schema::table('grades', function (Blueprint $table) {
            // Tanggalin ang lumang columns
            $table->dropColumn(['mark', 'grading_period']); // Kung meron man nito

            // Idagdag ang bago
            $table->integer('first_quarter')->nullable();
            $table->integer('second_quarter')->nullable();
            $table->integer('third_quarter')->nullable();
            $table->integer('fourth_quarter')->nullable();
            $table->integer('final_grade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            //
        });
    }
};
