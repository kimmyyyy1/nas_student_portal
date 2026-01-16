<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\EnrollmentApplication;
use App\Models\Section;
use App\Models\Team;
use App\Models\User;
use App\Models\Staff;
use App\Models\Schedule;
use App\Models\ActivityLog; 

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Redirect logic (Students & Applicants)
        // Kung student o applicant ang nag-login, ilipat sa sarili nilang dashboard
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
            
            // Hanapin ang staff record gamit ang email
            $staff = Staff::where('email', $user->email)->first();

            $advisorySection = null;
            $advisoryCount = 0;
            $mySchedules = collect([]);
            $staffError = null;

            if ($staff) {
                // Check kung may advisory section
                $advisorySection = Section::where('adviser_id', $staff->id)->first();

                if ($advisorySection) {
                    $advisoryCount = Student::where('section_id', $advisorySection->id)->count();
                }

                // Kunin ang schedule ng teacher
                $mySchedules = Schedule::with(['subject', 'section'])
                                ->where('staff_id', $staff->id)
                                ->orderBy('day')
                                ->orderBy('time_start')
                                ->get();
            } else {
                $staffError = "Staff profile not found. Please contact Admin to link your account.";
            }

            return view('dashboard', compact(
                'advisorySection', 
                'advisoryCount', 
                'mySchedules', 
                'staffError'
            ));
        }

        // =========================================================
        // 3. LOGIC FOR ADMIN (Default View)
        // =========================================================
        
        // Counts para sa Dashboard Cards
        $totalStudents = Student::count(); 
        $totalApplicants = EnrollmentApplication::where('status', 'Pending')->count(); // Optional kung gusto mo ipakita
        
        // Ito ang mga variables na tinatawag sa dashboard.blade.php
        $activeSections = Section::count(); // Renamed from totalSections to match blade if needed, or stick to variable
        $sportsTeams = Team::count();       // Renamed from totalTeams
        $upcomingPlans = 0;                 // FIX: Variable name matched to Blade ($upcomingPlans)

        // Get activities for initial page load (Latest 5)
        $activities = ActivityLog::with('user')
                        ->latest()
                        ->take(5)
                        ->get();

        return view('dashboard', compact(
            'totalStudents', 
            'totalApplicants', 
            'activeSections', 
            'sportsTeams',
            'upcomingPlans', // Passed correctly now
            'activities'
        ));
    }

    // 👇 AJAX FUNCTION: Tinatawag ito ng JavaScript para sa Live Updates
    public function getRecentActivity()
    {
        $activities = ActivityLog::with('user')
                        ->latest()
                        ->take(5)
                        ->get()
                        ->map(function ($activity) {
                            return [
                                'action' => $activity->action,       // Para sa kulay (Registration, Login, etc.)
                                'description' => $activity->description, // Detalye ng ginawa
                                'time_ago' => $activity->created_at->diffForHumans(), // "2 hours ago"
                                'user' => $activity->user ? [        // Details ng user na gumawa
                                    'name' => $activity->user->name,
                                    'role' => $activity->user->role,
                                ] : null,
                            ];
                        });

        return response()->json($activities);
    }
}