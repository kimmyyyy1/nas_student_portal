<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Idinagdag para sa adviser

class Section extends Model
{
    use HasFactory;

    /**
     * Ang mga fields na pwedeng i-mass assign.
     */
    protected $fillable = [
        'section_name',
        'grade_level',
        'adviser_id',   // <--- IMPORTANTE: Idinagdag ito para ma-save ang Staff ID
        'room_number',  // (Optional: Kung meron ka nito sa database, idagdag mo na rin)
    ];

    /**
     * Relasyon: Ang isang Section ay 'belongs to' (hawak ng) isang Adviser (Staff).
     * Ito ang susi para makuha ang pangalan ng teacher gamit ang ID.
     */
    public function adviser(): BelongsTo
    {
        // Sinasabi nito: "Ang adviser_id column ko ay nakaturo sa Staff model"
        return $this->belongsTo(Staff::class, 'adviser_id');
    }

    /**
     * Relasyon: Ang isang Section ay 'has many' (maraming) Students.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Relasyon: Ang isang Section ay pwedeng may maraming Schedules.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}