s<?php

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
        Schema::table('users', function (Blueprint $table) {
            // 1. TANGGALIN MUNA ANG FOREIGN KEY CONSTRAINT
            // Ito ang pumipigil sa pag-change ng column type
            $table->dropForeign('users_student_id_foreign');

            // 2. NGAYON PWEDE NANG BAGUHIN ANG COLUMN TYPE (Integer -> String)
            $table->string('student_id', 30)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Babala: Mahirap itong ibalik kapag may alphanumeric values na ang student_id
            // Pero kung pipilitin, ganito ang logic:
            
            // 1. Ibalik sa Integer (Mag-eerror ito kung may letters na ang laman)
            $table->integer('student_id')->nullable()->change();

            // 2. Ibalik ang Foreign Key (kung kailangan)
            // $table->foreign('student_id')->references('id')->on('students');
        });
    }
};