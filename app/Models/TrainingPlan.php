<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_name',
        'team_id',
        'details',
        'start_date',
        'end_date',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}