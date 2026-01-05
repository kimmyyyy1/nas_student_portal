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
            // Tama ito: 'teacher_id' ang ating idinagdag
            $table->foreignId('teacher_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            // I-comment out muna ito para hindi mag-error:
            // $table->dropForeign(['teacher_id']); 
            
            // Subukang i-drop lang ang column. 
            // Kung wala ang column, mag-eerror din ito, kaya lagyan natin ng check.
            if (Schema::hasColumn('sections', 'teacher_id')) {
                $table->dropColumn('teacher_id');
            }
        });
    }
};