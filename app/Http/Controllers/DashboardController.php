<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\EnrollmentApplication;
use App\Models\Section;
use App\Models\Team;
use App\Models\User;
use App\Models\Staff;     // Added
use App\Models\Schedule;  // Added

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Redirect logic (Students & Applicants)
        if ($user->role === 'student') {
            return redirect()->route('student.dashboard');
        }
        if ($user->role === 'applicant') {
            return redirect()->route('applicant.dashboard');
        }

        // =========================================================
        // 2. LOGIC FOR TEACHER
        // =========================================================
        if ($user->role === 'teacher') {
            
            // A. Hanapin ang Staff Record gamit ang EMAIL
            $staff = Staff::where('email', $user->email)->first();

            // Default values
            $advisorySection = null;
            $advisoryCount = 0;
            $mySchedules = collect([]);
            $staffError = null;

            if ($staff) {
                // B. Hanapin ang Section kung saan siya ang Adviser
                $advisorySection = Section::where('adviser_id', $staff->id)->first();

                // C. Bilangin ang estudyante kung may advisory class
                if ($advisorySection) {
                    // Dito pwede nating bilangin lahat ng nasa section, enrolled man o hindi
                    $advisoryCount = Student::where('section_id', $advisorySection->id)->count();
                }

                // D. Kunin ang Schedules/Loads niya
                $mySchedules = Schedule::with(['subject', 'section'])
                                ->where('staff_id', $staff->id)
                                ->orderBy('day')
                                ->orderBy('time_start')
                                ->get();
            } else {
                $staffError = "Staff profile not found. Please contact Admin to link your account.";
            }

            // Ipasa ang teacher variables sa view
            return view('dashboard', compact(
                'advisorySection', 
                'advisoryCount', 
                'mySchedules', 
                'staffError'
            ));
        }

        // =========================================================
        // 3. LOGIC FOR ADMIN (Default)
        // =========================================================
        
        // KUNIN ANG COUNTS
        
        // FIX: Binago ko ito para bilangin LAHAT ng students (hindi lang Enrolled)
        $totalStudents = Student::count(); 

        $totalApplicants = EnrollmentApplication::where('status', 'Pending')->count(); 
        $totalSections = Section::count();
        $totalTeams = Team::count();
        
        // Extra variables para sa Admin Dashboard view
        $upcomingPlansCount = 0; 
        $activities = collect([]); 

        // Ipasa ang admin variables sa view
        return view('dashboard', compact(
            'totalStudents', 
            'totalApplicants', 
            'totalSections', 
            'totalTeams',
            'upcomingPlansCount',
            'activities'
        ));
    }
}