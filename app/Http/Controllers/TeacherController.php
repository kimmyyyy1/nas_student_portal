<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Section; 

class TeacherController extends Controller
{
    /**
     * Display the teacher's advisory class list.
     * Route: GET /teacher/advisory
     */
    public function advisory()
    {
        // 1. Kunin ang ID ng naka-login na Teacher
        $teacherId = Auth::id();

        // 2. Hanapin ang section kung saan siya ang Adviser
        // IMPORTANT: Siguraduhin na ang 'teacher_id' ay nage-exist sa 'sections' table.
        // Kung ang error mo ay "Column not found", i-check mo sa database kung ano ang column name
        // (baka 'adviser_id', 'user_id', o 'faculty_id').
        
        $section = Section::where('teacher_id', $teacherId) 
                    ->with('students') // Dapat may defined relationship na 'students' sa Section Model
                    ->first();

        // 3. Ibalik ang view kasama ang section data
        return view('teacher.advisory', compact('section'));
    }
}