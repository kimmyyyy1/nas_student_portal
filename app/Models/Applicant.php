<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $table = 'applicants';

    /**
     * The attributes that are mass assignable.
     * Updated to match the strict Form & Controller inputs.
     */
    protected $fillable = [
        'user_id',
        'lrn',
        'last_name',
        'first_name',
        'middle_name',
        
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
        'school_type',           // Restored
        'sport',
        'sport_specification',
        'palaro_finisher',       // Replaced has_palaro_participation
        'batang_pinoy_finisher',
        
        // Background Info
        'learn_about_nas',
        'referrer_name',
        'attended_campaign',
        
        // Special Groups (Boolean inputs)
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
        'uploaded_files',
        'status',
        'assessment_score',
        'rejection_reason',
        'document_remarks',
        'document_statuses',
        'date_checked',
        'file_timestamps',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'uploaded_files' => 'array',
        'document_remarks' => 'array',
        'document_statuses' => 'array',
        'file_timestamps' => 'array',
        'date_of_birth' => 'date',
        
        // Booleans (The controller converts "Yes" to true/1)
        'is_ip' => 'boolean',
        'is_pwd' => 'boolean',
        'is_4ps' => 'boolean',
        
        // Note: palaro_finisher & batang_pinoy_finisher are stored as Strings ("Yes"/"No"), so no boolean cast needed.
    ];

    public function enrollmentRecord()
    {
        return $this->hasOne(EnrollmentRecord::class, 'applicant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}