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
            if (!Schema::hasColumn('applicants', 'school_type')) {
                $table->string('school_type')->nullable();
            }
            if (!Schema::hasColumn('applicants', 'palaro_finisher')) {
                $table->string('palaro_finisher')->nullable(); // Yes or No
            }
            if (!Schema::hasColumn('applicants', 'batang_pinoy_finisher')) {
                $table->string('batang_pinoy_finisher')->nullable(); // Yes or No
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
