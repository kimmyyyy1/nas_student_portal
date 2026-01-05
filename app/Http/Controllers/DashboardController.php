<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\EnrollmentApplication;
use App\Models\Section;
use App\Models\Team;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Redirect logic (kung Student o Applicant, ibato sa sarili nilang dashboard)
        if ($user->role === 'student') {
            return redirect()->route('student.dashboard');
        }
        if ($user->role === 'applicant') {
            return redirect()->route('applicant.dashboard');
        }

        // 2. KUNIN ANG COUNTS PARA SA ADMIN DASHBOARD
        $totalStudents = Student::where('status', 'Enrolled')->count();
        $totalApplicants = EnrollmentApplication::where('status', 'Pending')->count();
        $totalSections = Section::count();
        $totalTeams = Team::count();

        // Pwede mo ring dagdagan ng iba pa:
        // $totalStaff = User::where('role', 'admin')->orWhere('role', 'staff')->count();

        // 3. Ipasa ang variables sa view
        return view('dashboard', compact(
            'totalStudents', 
            'totalApplicants', 
            'totalSections', 
            'totalTeams'
        ));
    }
}