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
            
            // Check kung wala pang 'room_number', saka lang idagdag
            if (!Schema::hasColumn('sections', 'room_number')) {
                $table->string('room_number')->nullable()->after('section_name');
            }

            // Check na rin natin kung wala pang 'adviser_id' (just in case)
            if (!Schema::hasColumn('sections', 'adviser_id')) {
                $table->unsignedBigInteger('adviser_id')->nullable()->after('section_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            if (Schema::hasColumn('sections', 'room_number')) {
                $table->dropColumn('room_number');
            }
            if (Schema::hasColumn('sections', 'adviser_id')) {
                $table->dropColumn('adviser_id');
            }
        });
    }
};