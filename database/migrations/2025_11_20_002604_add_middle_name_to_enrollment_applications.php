<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('enrollment_applications', function (Blueprint $table) {
            // Idagdag ang middle_name pagkatapos ng first_name
            $table->string('middle_name')->nullable()->after('first_name');
        });
    }

    public function down()
    {
        Schema::table('enrollment_applications', function (Blueprint $table) {
            $table->dropColumn('middle_name');
        });
    }
};