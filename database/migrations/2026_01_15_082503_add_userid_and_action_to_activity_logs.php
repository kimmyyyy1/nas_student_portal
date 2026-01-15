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
        Schema::table('activity_logs', function (Blueprint $table) {
            // Idagdag ang user_id kung wala pa
            if (!Schema::hasColumn('activity_logs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            // Idagdag ang action kung wala pa
            if (!Schema::hasColumn('activity_logs', 'action')) {
                $table->string('action')->nullable()->after('user_id');
            }

            // Optional: Pwede mong i-drop ang lumang columns kung di na gagamitin
            // $table->dropColumn(['model_type', 'model_id']);
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'action']);
        });
    }
};
