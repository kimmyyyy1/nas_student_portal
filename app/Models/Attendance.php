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
        'schedule_id',
        'date',
        'status',
    ];

    // Relasyon: Ang isang Attendance ay kabilang sa isang Student
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // Relasyon: Ang isang Attendance ay kabilang sa isang Schedule
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}