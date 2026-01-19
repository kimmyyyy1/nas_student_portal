<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            
            // 👇 1. I-COMMENT OUT MO MUNA ITO (Dahil nabura na ito kanina)
            // $table->dropForeign(['schedule_id']); 

            // 👇 2. ITO ANG IMPORTANTE: Tanggalin ang Unique Index (Ito yung fix sa unang error)
            // Gamitin natin ang array syntax para sure na tama ang index name na mahanap niya
            try {
                $table->dropUnique(['student_id', 'schedule_id', 'date']);
            } catch (\Exception $e) {
                // Kung wala na yung unique index, ignore lang natin para di mag-error
            }

            // 👇 3. Check natin kung existing pa ang column bago i-drop (Safety check)
            if (Schema::hasColumn('attendances', 'schedule_id')) {
                $table->dropColumn('schedule_id');
            }

            // 👇 4. Idagdag ang bagong section_id (Check muna kung wala pa)
            if (!Schema::hasColumn('attendances', 'section_id')) {
                $table->foreignId('section_id')->after('student_id')->constrained()->onDelete('cascade');
            }

            // 👇 5. Ibalik ang unique constraint gamit ang section_id
            // Try-catch ulit in case na-add na ito sa previous run
            try {
                $table->unique(['student_id', 'section_id', 'date']);
            } catch (\Exception $e) {
                // Ignore if exists
            }
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Pabalik na process
            $table->dropForeign(['section_id']);
            $table->dropColumn('section_id');
            // $table->dropUnique(['student_id', 'section_id', 'date']); // Comment out to be safe

            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');
        });
    }
};