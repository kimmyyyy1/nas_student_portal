<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Idinagdag para sa relasyon

class Subject extends Model
{
    use HasFactory;

    /**
     * Ang mga fields na pwedeng i-mass assign.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subject_code',
        'subject_name',
        'description',
    ];

    /**
     * Relasyon: Ang isang Subject ay pwedeng may maraming Schedules.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}