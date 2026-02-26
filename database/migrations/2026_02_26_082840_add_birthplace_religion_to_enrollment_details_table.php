<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollment_details', function (Blueprint $table) {
            $table->string('birthplace')->nullable()->after('sex');
            $table->string('religion')->nullable()->after('birthplace');
        });
    }

    public function down(): void
    {
        Schema::table('enrollment_details', function (Blueprint $table) {
            $table->dropColumn(['birthplace', 'religion']);
        });
    }
};
