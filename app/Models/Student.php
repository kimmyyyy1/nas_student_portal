<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        // --- Identifiers & Media ---
        'nas_student_id',
        'lrn',
        'photo', 

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
        'promotion_status', // Promoted, Conditional, Retained (New Field)
        'general_average',  // For Grading (New Field)
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

    // Grades (Detailed Subject Grades)
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    // Attendance
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // Awards Received (New Relationship)
    public function awards(): HasMany
    {
        return $this->hasMany(Award::class);
    }

    // Medical Records (Physical Evaluation)
    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }

    // Link sa User Account (Login)
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}