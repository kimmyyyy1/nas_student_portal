<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- CONTROLLER IMPORTS ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;           // Admin/Main Dashboard
use App\Http\Controllers\EnrollmentController;          // Admin: Admission Management
use App\Http\Controllers\OfficialEnrollmentController;  // Admin: Enrollment Process
use App\Http\Controllers\ApplicantPortalController;     // Applicant: Portal
use App\Http\Controllers\StudentPortalController;       // Student: Portal (Enrolled)
use App\Http\Controllers\TeacherController;             // Teacher: Portal

// --- RESOURCE CONTROLLERS ---
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TrainingPlanController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- ROOT REDIRECT LOGIC ---
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        
        // 1. Kung Student (Enrolled) -> Student Portal
        if ($role === 'student') {
            return redirect()->route('student.dashboard');
        }
        // 2. Kung Applicant (Ongoing) -> Applicant Portal
        if ($role === 'applicant') {
            return redirect()->route('applicant.dashboard');
        }
        
        // 3. Kung Admin/Staff/Teacher/Coach -> Main Dashboard
        return redirect()->route('dashboard');
    }
    // Kung hindi naka-login -> Login Page
    return view('auth.login');
});

// --- AUTHENTICATED ROUTES GROUP ---
Route::middleware(['auth', 'verified'])->group(function () {

    // --- MAIN DASHBOARD (Unified Controller logic) ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- USER PROFILE (Common) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    //  APPLICANT PORTAL (For Applicants)
    // ==========================================
    Route::get('/applicant/dashboard', [ApplicantPortalController::class, 'index'])->name('applicant.dashboard');
    Route::get('/applicant/apply', [ApplicantPortalController::class, 'create'])->name('applicant.create');
    Route::post('/applicant/apply', [ApplicantPortalController::class, 'store'])->name('applicant.store');
    Route::get('/applicant/edit', [ApplicantPortalController::class, 'edit'])->name('applicant.edit');
    Route::patch('/applicant/edit', [ApplicantPortalController::class, 'update'])->name('applicant.update');
    Route::post('/applicant/submit-requirements', [ApplicantPortalController::class, 'submitRequirements'])->name('applicant.submit_requirements');

    // ==========================================
    //  STUDENT PORTAL (For Enrolled Students)
    // ==========================================
    Route::get('/student/dashboard', [StudentPortalController::class, 'index'])->name('student.dashboard');

    // ==========================================
    //  TEACHER PORTAL
    // ==========================================
    Route::get('/teacher/advisory', [TeacherController::class, 'advisory'])->name('teacher.advisory');

    // ==========================================
    //  ADMIN / REGISTRAR MODULES
    // ==========================================

    // 1. Admission Management
    Route::get('/admission', [EnrollmentController::class, 'index'])->name('admission.index');
    Route::get('/admission/{id}', [EnrollmentController::class, 'show'])->name('admission.show');
    Route::patch('/admission/{id}', [EnrollmentController::class, 'process'])->name('admission.process');
    Route::get('/admission/{id}/pdf', [EnrollmentController::class, 'generatePdf'])->name('admission.pdf');

    // 2. Official Enrollment Process
    Route::get('/official-enrollment/process/{id}', [OfficialEnrollmentController::class, 'show'])->name('official-enrollment.show');
    Route::post('/official-enrollment/store/{id}', [OfficialEnrollmentController::class, 'store'])->name('official-enrollment.store');

    // ==========================================
    //  ACADEMIC RESOURCES (CRUD)
    // ==========================================
    
    // Student Management
    Route::resource('students', StudentController::class);
    Route::get('/students-enrollment-list', [StudentController::class, 'enrollmentList'])->name('students.enrollment'); 

    // Sections & Subjects
    Route::resource('sections', SectionController::class);
    Route::resource('subjects', SubjectController::class);
    
    // Schedules
    Route::resource('schedules', ScheduleController::class);
    Route::get('/my-schedules', [ScheduleController::class, 'mySchedules'])->name('schedules.my');
    
    // Grades
    Route::resource('grades', GradeController::class);
    
    // Attendance
    Route::resource('attendances', AttendanceController::class);

    // ==========================================
    //  SPORTS & MEDICAL MODULES (Coach & Admin)
    // ==========================================
    Route::resource('teams', TeamController::class);
    Route::resource('training-plans', TrainingPlanController::class);
    Route::resource('medical-records', MedicalRecordController::class);
    
    // ==========================================
    //  SYSTEM MANAGEMENT (Admin Only)
    // ==========================================
    
    // Staff / User Management
    Route::resource('staff', StaffController::class);

    // Reports Management
    Route::resource('reports', ReportController::class);
    
    // Specific Report Routes (Ito ang hinahanap ng error mo kanina)
    Route::get('/reports/grade-sheets', [ReportController::class, 'gradeSheets'])->name('reports.grade_sheets');
    Route::get('/reports/report-cards', [ReportController::class, 'reportCards'])->name('reports.report_cards');
    Route::get('/reports/school-forms', [ReportController::class, 'schoolForms'])->name('reports.school_forms');
    Route::get('/reports/awardees', [ReportController::class, 'awardees'])->name('reports.awardees');
    Route::get('/reports/ranking', [ReportController::class, 'ranking'])->name('reports.ranking');

});

require __DIR__.'/auth.php';