<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentApplication extends Model
{
    use HasFactory;

    protected $table = 'enrollment_applications';

    protected $fillable = [
        'user_id',
        'lrn',
        'last_name',
        'first_name',
        'middle_name',
        'date_of_birth',
        'age',
        'gender',
        'birthplace',
        'religion',
        'email_address',
        
        // MGA BAGONG COLUMNS PARA SA CHECKBOXES
        'is_ip',      
        'is_pwd',     
        'is_4ps',     
        'is_others',  
        'others_specify',

        // MGA DATING STRING COLUMNS
        'special_categories',      
        'other_category_details',  

        'region',
        'province',
        'municipality_city',
        'barangay',
        'street_address',
        'zip_code',
        'previous_school',
        'school_type',
        'grade_level_applied',
        'sport',
        'has_palaro_participation',
        'palaro_year',
        'guardian_name',
        'guardian_relationship',
        'guardian_contact',
        'guardian_email',
        'uploaded_files',
        'status',
        'assessment_score',
        'rejection_reason',

        // --- IDAGDAG ITO SA DULO ---
        'date_checked', // <--- IMPORTANTE: Ito ang kulang kaya hindi nagse-save ang date!
    ];

    protected $casts = [
        'uploaded_files' => 'array',
        'date_of_birth' => 'date',
        // Idagdag din natin ang date_checked sa casts para automatic na Carbon instance siya
        'date_checked' => 'datetime', 
        'has_palaro_participation' => 'boolean',
        'is_ip' => 'boolean',
        'is_pwd' => 'boolean',
        'is_4ps' => 'boolean',
        'is_others' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}