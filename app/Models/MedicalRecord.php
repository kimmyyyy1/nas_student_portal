<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'record_type',
        'status',
        'notes',
        'date_cleared',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}