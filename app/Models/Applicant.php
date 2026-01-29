<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    // IMPORTANT: Ito ang table na ginawa natin sa migration
    protected $table = 'applicants';

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

        // Address
        'region',
        'province',
        'municipality_city',
        'barangay',
        'street_address',
        'zip_code',

        // Academic & Sports
        'previous_school',
        'school_type',
        'grade_level_applied',
        'sport',
        'sport_specification',
        
        // Achievements
        'has_palaro_participation',
        'palaro_year', 
        'batang_pinoy_finisher',

        // Background & Special Categories
        'learn_about_nas',
        'referrer_name',
        'attended_campaign',
        'is_ip',
        'ip_group_name',
        'is_pwd',
        'pwd_disability',
        'is_4ps',

        // Guardian
        'guardian_name',
        'guardian_relationship',
        'guardian_contact',
        'guardian_email',

        // System Fields
        'uploaded_files',
        'status',
        'assessment_score', // Remarks (Text)
        'rejection_reason',
        
        // ✨ NEW FIELDS (Idinagdag para sa Admin Process & File Updates)
        'document_remarks', 
        'document_statuses',
        'date_checked',
        'file_timestamps', // <--- IDAGDAG ITO para sa individual file tracking
    ];

    protected $casts = [
        // Arrays (JSON Columns)
        'uploaded_files' => 'array',
        'document_remarks' => 'array', 
        'document_statuses' => 'array',
        'file_timestamps' => 'array', // <--- IDAGDAG ITO (JSON to Array)
        
        // Dates
        'date_of_birth' => 'date',
        'date_checked' => 'datetime',
        
        // Booleans (Automatic 1 or 0 conversion)
        'has_palaro_participation' => 'boolean',
        'is_ip' => 'boolean',
        'is_pwd' => 'boolean',
        'is_4ps' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}