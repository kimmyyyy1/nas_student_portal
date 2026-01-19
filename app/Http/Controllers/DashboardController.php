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

        // =========================================================
        // 1. REDIRECT LOGIC (Students & Applicants)
        // =========================================================
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
        
        // Initial Data for Blade View (Page Load)
        $totalStudents = Student::count(); 
        $totalApplicants = EnrollmentApplication::where('status', 'Pending')->count();
        
        $activeSections = Section::count(); 
        $sportsTeams = Team::count();       
        $upcomingPlans = 0; // Replace with Event::count() later if needed

        // Get activities for initial page load
        $activities = ActivityLog::with('user')
                        ->latest()
                        ->take(5)
                        ->get();

        return view('dashboard', compact(
            'totalStudents', 
            'totalApplicants', 
            'activeSections', 
            'sportsTeams',
            'upcomingPlans',
            'activities'
        ));
    }

    // =========================================================
    // 4. AJAX ENDPOINTS (For Live Updates)
    // =========================================================

    // Returns Recent Activity Logs as JSON
    public function getRecentActivity()
    {
        $activities = ActivityLog::with('user')
                        ->latest()
                        ->take(5)
                        ->get()
                        ->map(function ($activity) {
                            return [
                                'action' => $activity->action,
                                'description' => $activity->description,
                                'time_ago' => $activity->created_at->diffForHumans(),
                                'user' => $activity->user ? [
                                    'name' => $activity->user->name,
                                    'role' => $activity->user->role,
                                ] : null,
                            ];
                        });

        return response()->json($activities);
    }

    // Returns Statistics Counts as JSON
    public function getStats()
    {
        return response()->json([
            'totalStudents' => Student::count(),
            'activeSections' => Section::count(),
            'totalTeams' => Team::count(),
            'upcomingPlans' => 0, // Update this if you have an Events model
        ]);
    }
}   