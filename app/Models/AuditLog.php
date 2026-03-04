<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'applicant_id',
        'action',
        'details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
