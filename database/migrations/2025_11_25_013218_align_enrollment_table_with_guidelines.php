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
        Schema::table('enrollment_applications', function (Blueprint $table) {
            // Internal Fields (Para sa Registrar) 
            $table->string('application_number')->nullable()->after('id'); 
            $table->string('mode_of_application')->default('Online')->after('application_number'); 
            $table->date('date_checked')->nullable()->after('updated_at');
            
            // Address Fields Specifics [cite: 32-34]
            // (Meron na tayong region, province, municipality_city, barangay. Siguraduhin lang natin)
            
            // Sport Subcategory [cite: 35]
            // (Gagamitin natin ang existing 'sport' column para dito "Sport and subcategory")
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollment_applications', function (Blueprint $table) {
            //
        });
    }
};
