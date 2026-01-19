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
        Schema::table('students', function (Blueprint $table) {
            
            // SCENARIO A: May 'photo' column ka na dati? I-rename natin sa 'id_picture'.
            if (Schema::hasColumn('students', 'photo')) {
                // Gumamit tayo ng raw SQL para siguradong gumana sa TiDB
                DB::statement("ALTER TABLE students CHANGE photo id_picture VARCHAR(255) NULL");
            }
            
            // SCENARIO B: Wala pang 'id_picture' at wala ring 'photo'? Mag-add tayo bago.
            elseif (!Schema::hasColumn('students', 'id_picture')) {
                $table->string('id_picture')->nullable()->after('email_address');
            }
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Ibalik sa dati kung sakaling i-rollback
            if (Schema::hasColumn('students', 'id_picture')) {
                DB::statement("ALTER TABLE students CHANGE id_picture photo VARCHAR(255) NULL");
            }
        });
    }
};