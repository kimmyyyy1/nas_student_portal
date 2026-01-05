<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'contact_number',
        'role',       // teacher, coach, medical, admin
        'department',
        'position',
    ];

    // Helper para sa Full Name
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}