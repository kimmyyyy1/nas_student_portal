<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL/MariaDB, we need to use a raw query to update the enum properly
        // Or if using Doctrine DBAL, we can use change()
        
        DB::statement("ALTER TABLE applicants MODIFY COLUMN status ENUM(
            'Pending',
            'Submitted',
            'With Pending Requirements',
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
        ) DEFAULT 'With Pending Requirements'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op for now unless explicitly needed to rollback
    }
};
