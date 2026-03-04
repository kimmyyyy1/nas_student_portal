<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// IMPORTANT: Import the correct model
use App\Models\EnrollmentDetail; 

class Applicant extends Model
{
    use HasFactory;

    protected $table = 'applicants';

    protected $fillable = [
        'user_id',
        'lrn',
        'last_name',
        'first_name',
        'middle_name',
        'extension_name', // Added this just in case
        'photo', // 2x2 Image Path
        
        // Personal Details
        'date_of_birth',
        'age',
        'gender',
        
        // Address
        'region',
        'province',
        'municipality_city',
        'barangay',
        'street_address',
        'zip_code',
        
        // Sports & School Info
        'school_type',
        'school_last_grade_level',
        'school_last_year_completed',
        'school_id',
        'sport',
        'sport_specification',
        'palaro_finisher',
        'batang_pinoy_finisher',
        
        // Background Info
        'learn_about_nas',
        'referrer_name',
        'attended_campaign',
        
        // Special Groups
        'is_ip',
        'ip_group_name',
        'is_pwd',
        'pwd_disability',
        'is_4ps',
        
        // Guardian Info
        'guardian_name',
        'guardian_relationship',
        'guardian_contact',
        'guardian_email',
        
        // System / Admin Fields
        'email_address',
        'sex',
        'uploaded_files',
        'status',
        'is_enrolled', // Added tracking field
        'student_id', // Official Student Number inputted by the Registrar
        'assessment_score',
        'rejection_reason',
        'is_locked', // Record Locking Facility
        'document_remarks',
        'document_statuses',
        'date_checked',
        'file_timestamps',
    ];

    protected $casts = [
        'uploaded_files' => 'array',
        'document_remarks' => 'array',
        'document_statuses' => 'array',
        'file_timestamps' => 'array',
        'date_of_birth' => 'date',
        
        'is_ip' => 'boolean',
        'is_pwd' => 'boolean',
        'is_4ps' => 'boolean',
        'is_enrolled' => 'boolean',
        'is_locked' => 'boolean',
    ];

    // --- ITO ANG FIXED RELATIONSHIP ---
    // Pinalitan mula 'enrollmentRecord' -> 'enrollmentDetail'
    // At nakaturo na sa tamang Model
    public function enrollmentDetail()
    {
        return $this->hasOne(EnrollmentDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}