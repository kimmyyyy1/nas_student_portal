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
        Schema::table('attendances', function (Blueprint $table) {
            // Idagdag ang section_id para madali nating ma-filter per class
            // Nullable muna para hindi mag-error kung may laman na ang database mo
            $table->foreignId('section_id')->nullable()->after('student_id')->constrained()->onDelete('cascade');
            
            // Idagdag din ang remarks para sa notes (e.g. "Sick")
            $table->string('remarks')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropColumn(['section_id', 'remarks']);
        });
    }
};