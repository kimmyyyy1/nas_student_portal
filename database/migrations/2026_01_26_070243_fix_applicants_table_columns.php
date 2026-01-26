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
            // Baguhin ang assessment_score mula Decimal/Int papuntang Text/String
            // Note: Kung ayaw gumana ng ->change(), kailangan mo ng 'doctrine/dbal' package. 
            // Kung wala, pwede mong i-drop at i-add ulit, pero ingat sa data loss.
            // Ang pinaka-safe kung development pa lang:
            $table->text('assessment_score')->nullable()->change(); 
            
            // Magdagdag ng column para sa remarks ng bawat documents
            $table->json('document_remarks')->nullable(); 
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
