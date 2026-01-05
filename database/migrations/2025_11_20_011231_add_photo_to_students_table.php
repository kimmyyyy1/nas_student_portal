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
        Schema::table('students', function (Blueprint $table) {
            // Nagdadagdag ng 'photo' column pagkatapos ng 'email_address'
            // Nullable ito sakaling walang picture ang student
            $table->string('photo')->nullable()->after('email_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Tatanggalin ang column kapag nag-rollback
            $table->dropColumn('photo');
        });
    }
};