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
        Schema::table('enrollment_applications', function (Blueprint $table) {
            
            // Check kung WALA pa ang 'special_categories', saka lang i-add
            if (!Schema::hasColumn('enrollment_applications', 'special_categories')) {
                $table->text('special_categories')->nullable()->after('email_address');
            }

            // Check kung WALA pa ang 'other_category_details', saka lang i-add
            if (!Schema::hasColumn('enrollment_applications', 'other_category_details')) {
                $table->string('other_category_details')->nullable()->after('special_categories');
            }
            
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
