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
            // Identifiers
            $table->string('lrn')->nullable(); // 
            
            // Personal Additional
            $table->string('birthplace')->nullable(); // 
            $table->string('religion')->nullable(); // 
            $table->integer('age')->nullable(); // 

            // Special Status (Checkbox) 
            $table->boolean('is_ip')->default(false);
            $table->boolean('is_pwd')->default(false);
            $table->boolean('is_4ps')->default(false);

            // Address 
            $table->string('region')->nullable();
            $table->string('province')->nullable();
            $table->string('municipality_city')->nullable();
            $table->string('barangay')->nullable();
            $table->string('street_address')->nullable();
            $table->string('zip_code')->nullable();

            // Guardian Additional 
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship')->nullable();
            $table->string('guardian_email')->nullable(); 
            // Note: guardian_contact ay nasa table na
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
