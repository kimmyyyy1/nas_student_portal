<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import ito

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'section_id',
        'staff_id',
        'day',
        'time_start',
        'time_end',
        'room',
    ];

    // Relasyon sa Subject (BelongsTo)
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    // Relasyon sa Section (BelongsTo)
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    // Relasyon sa Staff (BelongsTo)
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    // Relasyon sa Grades (HasMany)
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    // Relasyon sa Attendance (HasMany) - BAGONG RELASYON
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}