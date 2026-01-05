<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'schedule_id',
        'mark',
        'grading_period',
    ];

    // Relasyon: Ang isang Grade ay kabilang sa isang Student
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // Relasyon: Ang isang Grade ay kabilang sa isang Schedule
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}