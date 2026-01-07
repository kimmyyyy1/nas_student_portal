<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class StudentPortalController extends Controller
{
    /**
     * Show the Student Dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Hanapin ang Student record gamit ang email o student_id link
        // Eager load relationships para optimized ang database query
        $student = Student::where('email_address', $user->email)
                          ->orWhere('id', $user->student_id)
                          ->with([
                              'section.schedules.subject',  // Para sa Class Schedule
                              'section.schedules.staff',    // Para sa Teacher info
                              'team',                       // Para sa Sports info
                              'grades.schedule.subject',    // Para sa Grades table
                              'attendances.schedule.subject', // Para sa Attendance log
                              'awards',                     // Para sa Awards & Recognition
                              'media'                       // <--- IMPORTANT: Para makuha ang Spatie Picture
                          ])
                          ->first();

        // Kung walang student record (error prevention), ibalik sa login page may error msg
        if (!$student) {
            return redirect()->route('login')->with('error', 'No student record found linked to this account.');
        }

        // Return view
        return view('student-portal.dashboard', compact('student'));
    }
}