<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentDetail extends Model
{
    use HasFactory;

    // Ito ang magsasabi sa Laravel na pwede nating i-save ang lahat ng fields galing sa form
    protected $guarded = []; 

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}