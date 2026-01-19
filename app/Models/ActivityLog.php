<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',      // 👈 ITO ANG KULANG KANINA
        'action',       // 👈 ITO RIN
        'description',
        // Pwede mong retain ang 'model_type' at 'model_id' kung gagamitin mo sa future, 
        // pero sa ngayon, user_id, action, at description ang kailangan.
    ];

    /**
     * Get the user that performed the activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}