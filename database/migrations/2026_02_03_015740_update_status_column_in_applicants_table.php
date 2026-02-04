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
        Schema::table('applicants', function (Blueprint $table) {
            $table->enum('status', [
                'With Pending Requirements',
                'With Complete Requirements & for 1st Level Assessment',
                'For 2nd Level Assessment',
                'Not Qualified',
                'Waitlisted',
                'Qualified',
                'Endorsed for Enrollment'
            ])->default('With Pending Requirements')->change();
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
