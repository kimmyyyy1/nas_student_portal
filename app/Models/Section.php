<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Idinagdag para sa relasyon

class Section extends Model
{
    use HasFactory;

    /**
     * Ang mga fields na pwedeng i-mass assign.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'section_name',
        'grade_level',
        'adviser_name',
    ];

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
    // ITO ANG INAYOS: tinanggal ang "publicaS"
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}