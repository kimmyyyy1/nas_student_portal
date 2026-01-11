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
        Schema::table('sections', function (Blueprint $table) {
            // Idadagdag natin ang adviser_id column
            $table->unsignedBigInteger('adviser_id')->nullable()->after('section_name');
            
            // Optional: Kung gusto mong siguraduhin na valid user ito (Foreign Key)
            // $table->foreign('adviser_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn('adviser_id');
        });
    }
};
