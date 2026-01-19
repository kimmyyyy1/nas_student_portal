<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'student_id',
        'section_id', // Ito ang pinalit natin sa schedule_id
        // 'schedule_id', // ❌ TANGGALIN ITO: Wala na ito sa database
        'date',
        'status',
        'remarks', // Optional: Siguraduhin lang na may 'remarks' column sa database mo. Kung wala, tanggalin din ito.
        'recorded_by', // Optional: Kung gusto mong i-save kung sino nag-record (gaya sa controller kanina)
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    // ❌ TANGGALIN ANG SCHEDULE RELATIONSHIP
    // Wala na tayong foreign key na schedule_id, kaya mag-eerror ito kung iiwan.
    /* public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    } 
    */
}