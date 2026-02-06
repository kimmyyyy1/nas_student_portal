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
            // Listahan ng mga columns na BURADO NA sa form at HINDI NA KAILANGAN
            $unusedColumns = [
                'grade_level_applied',        // Tinanggal na natin
                'has_palaro_participation',   // Pinalitan ng 'palaro_finisher'
                'palaro_year',                // Wala sa form
                'extension_name',             // Wala sa form
                'birthplace',                 // Wala sa form
                'religion',                   // Wala sa form
                'previous_school',            // Wala sa form
                
                // Parents Info (Removed from form)
                'father_name', 'father_address', 'father_contact', 'father_email',
                'mother_name', 'mother_address', 'mother_contact', 'mother_email',
                
                // Extra Guardian Info (Removed)
                'guardian_address', 
                
                // School Details (Removed)
                'last_grade_level', 'last_school_year', 'school_id', 'school_address'
            ];

            foreach ($unusedColumns as $column) {
                // Check muna kung nage-exist bago burahin para iwas error
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
        Schema::table('applicants', function (Blueprint $table) {
            //
        });
    }
};
