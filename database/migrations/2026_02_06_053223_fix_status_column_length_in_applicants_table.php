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
        // Gawing VARCHAR(255) ang status column para magkasya ang mahahabang status messages
        DB::statement("ALTER TABLE applicants MODIFY COLUMN status VARCHAR(255) DEFAULT 'Pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: Ibalik sa dati kung kinakailangan
        // DB::statement("ALTER TABLE applicants MODIFY COLUMN status VARCHAR(50)");
    }
};