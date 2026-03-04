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
                'Pending',
                'Submitted',
                'With Pending Requirements',
                'Requirements Submitted & For Review',
                'With Complete Requirements & for 1st Level Assessment',
                'For 2nd Level Assessment',
                'Submitted for 1st Level Assessment',
                'Waitlisted',
                'Qualified',
                'Qualified (Returned)',
                'Accepted',
                'Not Qualified',
                'Rejected',
                'Failed',
                'For Enrollment Verification',
                'Officially Enrolled',
                'Admitted',
                'Enrolled',
                'Pending Renewal',
                'Renewal (Returned)'
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
