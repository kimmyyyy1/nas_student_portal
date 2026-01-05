<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import ito

class Team extends Model
{
    use HasFactory;

    /**
     * Ang mga fields na pwedeng i-mass assign.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'team_name',
        'sport',
        'coach_name',
    ];

    /**
     * Relasyon: Ang isang Team ay 'has many' (maraming) Students.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}