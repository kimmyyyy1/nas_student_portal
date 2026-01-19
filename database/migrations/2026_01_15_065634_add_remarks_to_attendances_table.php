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
        // Check muna kung WALA PA yung column bago i-add
        if (!Schema::hasColumn('attendances', 'remarks')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->string('remarks')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });
    }
};
