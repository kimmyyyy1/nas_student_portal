<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentApplication extends Model
{
    use HasFactory;

    /**
     * Siguraduhin natin na ang table name ay tama.
     */
    protected $table = 'enrollment_applications';

    /**
     * Idinagdag natin ang mga bagong checkbox columns dito sa $fillable.
     * Kapag wala sila rito, "is-snob" o ibabalewala sila ni Laravel kapag nag-save ka.
     */
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

        // MGA DATING STRING COLUMNS (Panatilihin natin para sa compatibility)
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
    ];

    /**
     * Dito sa $casts, ginagawa nating 'boolean' ang mga is_ columns.
     * Para kapag kinuha mo sa code, automatic silang true o false.
     */
    protected $casts = [
        'uploaded_files' => 'array',
        'date_of_birth' => 'date',
        'has_palaro_participation' => 'boolean',
        'is_ip' => 'boolean',
        'is_pwd' => 'boolean',
        'is_4ps' => 'boolean',
        'is_others' => 'boolean',
    ];

    /**
     * Relationship to the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}