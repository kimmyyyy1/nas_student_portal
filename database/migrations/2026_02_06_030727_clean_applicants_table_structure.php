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
        Schema::table('applicants', function (Blueprint $table) {
            // Listahan ng mga columns na gusto mong tanggalin
            $columnsData = [
                'palaro_finisher',
                'batang_pinoy_finisher',
                'school_type',
                'grade_level_applied',
                'has_palaro_participation'
            ];

            foreach ($columnsData as $column) {
                // Check muna kung existing ang column bago burahin (SAFE METHOD)
                if (Schema::hasColumn('applicants', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
