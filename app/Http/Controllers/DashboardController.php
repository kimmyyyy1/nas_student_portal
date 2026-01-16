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
            
            $staff = Staff::where('email', $user->email)->first();

            $advisorySection = null;
            $advisoryCount = 0;
            $mySchedules = collect([]);
            $staffError = null;

            if ($staff) {
                $advisorySection = Section::where('adviser_id', $staff->id)->first();

                if ($advisorySection) {
                    $advisoryCount = Student::where('section_id', $advisorySection->id)->count();
                }

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
        // 3. LOGIC FOR ADMIN (Default)
        // =========================================================
        
        $totalStudents = Student::count(); 
        $totalApplicants = EnrollmentApplication::where('status', 'Pending')->count(); 
        $totalSections = Section::count();
        $totalTeams = Team::count();
        
        $upcomingPlansCount = 0; 

        // Get activities for initial page load
        $activities = ActivityLog::with('user')
                        ->latest()
                        ->take(5)
                        ->get();

        return view('dashboard', compact(
            'totalStudents', 
            'totalApplicants', 
            'totalSections', 
            'totalTeams',
            'upcomingPlansCount',
            'activities'
        ));
    }

    // 👇 UPDATED FUNCTION: Returns Complete Data for AJAX
    public function getRecentActivity()
    {
        $activities = ActivityLog::with('user')
                        ->latest()
                        ->take(5)
                        ->get()
                        ->map(function ($activity) {
                            return [
                                'action' => $activity->action,           // Need for Color Logic
                                'description' => $activity->description, // Need for Details
                                'time_ago' => $activity->created_at->diffForHumans(),
                                'user' => $activity->user ? [            // Need for Name & Role
                                    'name' => $activity->user->name,
                                    'role' => $activity->user->role,
                                ] : null,
                            ];
                        });

        return response()->json($activities);
    }
}