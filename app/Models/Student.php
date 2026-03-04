<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Student extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia; 

    protected $fillable = [
        // --- Identifiers ---
        'nas_student_id',
        'lrn',
        'id_picture', // IMPORTANT: Picture File Path

        // --- Personal Info ---
        'first_name',
        'last_name',
        'middle_name',
        'sex',
        'birthdate',
        'age',
        'birthplace',
        'religion',

        // --- Special Categories (Checkboxes) ---
        'is_ip',   // Indigenous People
        'is_pwd',  // Person with Disability
        'is_4ps',  // 4Ps Beneficiary

        // --- Academic & Sports Info ---
        'entry_year',
        'grade_level',
        'status',           // New, Continuing, Transfer out, Graduate, Enrolled
        'promotion_status', // Promoted, Conditional, Retained
        'general_average',  // Final Grade
        
        // --- Quarterly Grades ---
        'q1',
        'q2',
        'q3',
        'q4',
        
        'section_id',
        'team_id',
        
        // --- Enrollment System Fields (Registrar) ---
        'enrollment_date',
        'lis_status',
        'enrollment_remarks',

        // --- Contact & Address ---
        'region',
        'province',
        'municipality_city',
        'barangay',
        'street_address',
        'zip_code',
        'contact_number',
        'email_address',

        // --- Guardian Info ---
        'guardian_name',
        'guardian_relationship',
        'guardian_email',
        'guardian_contact',
        'guardian_address',

        // --- NASCENT SAS Fields ---
        'sport',
        'sport_specification',
        'ip_group_name',
        'pwd_disability',
        'heard_about_nas',
        'referrer_name',
        'attended_articulation',
        'school_name',
        'school_type',
        'last_grade_level',
        'last_school_year',
        'school_id',
        'school_address',

        // ❌ NOTE: WALA DAPAT DITO ANG 'role'. 
        // Ang 'role' ay nasa User table, hindi sa Student table.
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_ip' => 'boolean',
        'is_pwd' => 'boolean',
        'is_4ps' => 'boolean',
        'birthdate' => 'date',
        'enrollment_date' => 'date',
        'q1' => 'double',
        'q2' => 'double',
        'q3' => 'double',
        'q4' => 'double',
        'general_average' => 'double',
    ];

    // --- RELATIONSHIPS ---

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function awards(): HasMany
    {
        return $this->hasMany(Award::class);
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}