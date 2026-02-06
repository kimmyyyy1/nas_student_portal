<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentRecord extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'enrollment_records';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_id',
        
        // Mandatory Forms
        'scholarship_form',
        'student_athlete_profile_form',
        'ppe_clearance_form',
        
        // General Requirements
        'psa_birth_certificate',
        'report_card',
        'guardian_valid_id',
        
        // Conditional/Special Requirements
        'kukkiwon_certificate',   // For Taekwondo
        'ip_certification',       // For IP Members
        'pwd_id',                 // For PWDs
        'four_ps_certification',  // For 4Ps Beneficiaries
        
        // Enrollment Tracking
        'enrollment_status',
        'document_remarks',       // JSON field for admin comments per file
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'document_remarks' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: Link back to the main Applicant record.
     */
    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }
}