<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

// --- CORE CONTROLLERS ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

// --- PORTAL CONTROLLERS ---
use App\Http\Controllers\ApplicantPortalController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\TeacherController;

// --- MODULE CONTROLLERS ---
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\OfficialEnrollmentController;
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

// ==========================================
//  ROOT REDIRECT LOGIC
// ==========================================
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        // Redirect based on role
        if ($role === 'student') return redirect()->route('student.dashboard');
        if ($role === 'applicant') return redirect()->route('applicant.dashboard');
        // Admin & Teachers go to main dashboard
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

// ==========================================
//  DEBUG / UTILITY ROUTE (Clear Cache)
// ==========================================
Route::get('/clear-all', function() {
    try {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        return response()->json(['message' => 'System Cache Cleared Successfully!']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// ==========================================
//  AUTHENTICATED ROUTES GROUP
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {

    // --- MAIN DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 👇 AJAX ROUTES (For Live Dashboard Updates)
    Route::get('/recent-activity', [DashboardController::class, 'getRecentActivity'])->name('recent.activity');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');

    // --- USER PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    //  APPLICANT PORTAL
    // ==========================================
    Route::prefix('applicant')->name('applicant.')->group(function() {
        Route::get('/dashboard', [ApplicantPortalController::class, 'index'])->name('dashboard');
        Route::get('/apply', [ApplicantPortalController::class, 'create'])->name('create');
        Route::post('/apply', [ApplicantPortalController::class, 'store'])->name('store');
        Route::get('/edit', [ApplicantPortalController::class, 'edit'])->name('edit');
        Route::patch('/edit', [ApplicantPortalController::class, 'update'])->name('update');
        Route::post('/submit-requirements', [ApplicantPortalController::class, 'submitRequirements'])->name('submit_requirements');
        
        // 👇 NEW ROUTE: Proxy View for Cloudinary Files (To keep Favicon/Domain)
        Route::get('/view-file/{id}/{type}', [ApplicantPortalController::class, 'viewFile'])->name('view_file');
    });

    // ==========================================
    //  STUDENT PORTAL
    // ==========================================
    Route::get('/student/dashboard', [StudentPortalController::class, 'index'])->name('student.dashboard');

    // ==========================================
    //  TEACHER SPECIFIC ROUTES
    // ==========================================
    Route::get('/teacher/advisory', [TeacherController::class, 'advisory'])->name('teacher.advisory');

    // ==========================================
    //  ADMIN / REGISTRAR MODULES
    // ==========================================
    
    // Admissions
    Route::get('/admission', [EnrollmentController::class, 'index'])->name('admission.index');
    Route::get('/admission/{id}', [EnrollmentController::class, 'show'])->name('admission.show');
    Route::patch('/admission/{id}', [EnrollmentController::class, 'process'])->name('admission.process');
    Route::get('/admission/{id}/pdf', [EnrollmentController::class, 'generatePdf'])->name('admission.pdf');

    // Official Enrollment (Qualified Applicants)
    Route::get('/official-enrollment', [OfficialEnrollmentController::class, 'index'])->name('official-enrollment.index');
    Route::get('/official-enrollment/process/{id}', [OfficialEnrollmentController::class, 'show'])->name('official-enrollment.show');
    Route::post('/official-enrollment/store/{id}', [OfficialEnrollmentController::class, 'store'])->name('official-enrollment.store');
    
    // 👇 NEW ROUTE: Save Remarks & Return to Applicant
    Route::patch('/official-enrollment/return/{id}', [OfficialEnrollmentController::class, 'returnToApplicant'])->name('official-enrollment.return');

    // ==========================================
    //  ACADEMIC RESOURCES
    // ==========================================
    
    // Students (Bulk Upload must be BEFORE resource)
    Route::get('/students/bulk-upload', [StudentController::class, 'bulkUploadForm'])->name('students.bulk-upload');
    Route::post('/students/bulk-upload', [StudentController::class, 'processBulkUpload'])->name('students.process-bulk-upload');
    Route::get('/students-enrollment-list', [StudentController::class, 'enrollmentList'])->name('students.enrollment'); 
    Route::resource('students', StudentController::class);

    // Sections & Subjects
    Route::resource('sections', SectionController::class);
    Route::resource('subjects', SubjectController::class);
    
    // Schedules
    Route::get('/my-schedules', [ScheduleController::class, 'mySchedules'])->name('schedules.my');
    Route::resource('schedules', ScheduleController::class);
    
    // Grades Management
    Route::patch('/grades/bulk-update', [GradeController::class, 'bulkUpdate'])->name('grades.bulk_update'); 
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/{section}', [GradeController::class, 'show'])->name('grades.show');

    // Attendance Management
    Route::post('/attendances/bulk-store', [AttendanceController::class, 'bulkStore'])->name('attendances.bulk_store');
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/{section}', [AttendanceController::class, 'show'])->name('attendances.show');

    // ==========================================
    //  SPORTS & MEDICAL MODULES
    // ==========================================
    Route::resource('teams', TeamController::class);
    Route::resource('training-plans', TrainingPlanController::class);
    Route::resource('medical-records', MedicalRecordController::class);
    
    // ==========================================
    //  SYSTEM MANAGEMENT
    // ==========================================
    Route::resource('staff', StaffController::class);

    // ==========================================
    //  REPORTS MANAGEMENT
    // ==========================================
    Route::prefix('reports')->name('reports.')->group(function() {
        Route::get('/grade-sheets', [ReportController::class, 'gradeSheets'])->name('grade_sheets');
        Route::get('/report-cards', [ReportController::class, 'reportCards'])->name('report_cards');
        Route::get('/school-forms', [ReportController::class, 'schoolForms'])->name('school_forms');
        Route::get('/awardees', [ReportController::class, 'awardees'])->name('awardees');
        Route::get('/ranking', [ReportController::class, 'ranking'])->name('ranking');
    });
    Route::resource('reports', ReportController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);

});

require __DIR__.'/auth.php';