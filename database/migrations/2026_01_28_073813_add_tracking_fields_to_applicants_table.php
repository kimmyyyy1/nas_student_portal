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
            // Check muna kung wala pa bago i-add para iwas error
            if (!Schema::hasColumn('applicants', 'document_remarks')) {
                $table->json('document_remarks')->nullable()->after('rejection_reason');
            }
            if (!Schema::hasColumn('applicants', 'date_checked')) {
                $table->timestamp('date_checked')->nullable()->after('document_remarks');
            }
            if (!Schema::hasColumn('applicants', 'file_timestamps')) {
                $table->json('file_timestamps')->nullable()->after('date_checked');
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
