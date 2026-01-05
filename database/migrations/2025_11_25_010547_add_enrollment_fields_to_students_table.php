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
        Schema::table('students', function (Blueprint $table) {
            $table->date('enrollment_date')->nullable()->after('status'); // Enrollment/Ingress Date
            $table->string('lis_status')->nullable()->after('enrollment_date'); // LIS Status
            $table->text('enrollment_remarks')->nullable()->after('lis_status'); // Remarks
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
};
