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
        Schema::table('grades', function (Blueprint $table) {
            // 1. Tanggalin ang lumang columns (kung meron)
            // Gamitan natin ng checking para iwas error kung wala na sila
            if (Schema::hasColumn('grades', 'mark')) {
                $table->dropColumn('mark');
            }
            if (Schema::hasColumn('grades', 'grading_period')) {
                $table->dropColumn('grading_period');
            }

            // 2. Idagdag ang mga bagong columns (kung wala pa)
            if (!Schema::hasColumn('grades', 'first_quarter')) {
                $table->integer('first_quarter')->nullable();
            }
            if (!Schema::hasColumn('grades', 'second_quarter')) {
                $table->integer('second_quarter')->nullable();
            }
            if (!Schema::hasColumn('grades', 'third_quarter')) {
                $table->integer('third_quarter')->nullable();
            }
            if (!Schema::hasColumn('grades', 'fourth_quarter')) {
                $table->integer('fourth_quarter')->nullable();
            }
            if (!Schema::hasColumn('grades', 'final_grade')) {
                $table->integer('final_grade')->nullable();
            }

            // Siguraduhin ding may schedule_id
            if (!Schema::hasColumn('grades', 'schedule_id')) {
                $table->unsignedBigInteger('schedule_id')->nullable()->after('student_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            //
        });
    }
};
