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

Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if ($role === 'student') return redirect()->route('student.dashboard');
        if ($role === 'applicant') return redirect()->route('applicant.dashboard');
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

// Clear Cache Route (Utility)
Route::get('/clear-all', function() {
    try {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear'); 
        return response()->json(['message' => 'System Cache & Routes Cleared Successfully!']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/recent-activity', [DashboardController::class, 'getRecentActivity'])->name('recent.activity');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');

    // ==========================================
    //  🔔 NOTIFICATION ROUTES (ADDED THIS)
    // ==========================================
    Route::get('/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.readAll');
    // ==========================================

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    //  APPLICANT PORTAL ROUTES
    // ==========================================
    Route::prefix('applicant')->name('applicant.')->group(function() {
        
        // Dashboard
        Route::get('/dashboard', [ApplicantPortalController::class, 'index'])->name('dashboard');
        
        // Phase 1: Application Form
        Route::get('/apply', [ApplicantPortalController::class, 'create'])->name('create');
        Route::post('/apply', [ApplicantPortalController::class, 'store'])->name('store');
        
        // Phase 2: Requirements Submission (Upload & Re-upload)
        Route::get('/requirements', [ApplicantPortalController::class, 'showRequirements'])->name('requirements');
        Route::post('/requirements', [ApplicantPortalController::class, 'storeRequirements'])->name('store_requirements');

        // Phase 3: Official Enrollment (Qualified Applicants)
        Route::get('/enrollment', [ApplicantPortalController::class, 'showEnrollmentForm'])->name('enrollment.show');
        Route::post('/enrollment', [ApplicantPortalController::class, 'submitEnrollmentForm'])->name('enrollment.store');

        // Utilities
        Route::get('/view-file/{id}/{type}', [ApplicantPortalController::class, 'viewFile'])->name('view_file');
        
        // Edit Profile (Optional/Future)
        Route::get('/edit', [ApplicantPortalController::class, 'edit'])->name('edit');
        Route::patch('/edit', [ApplicantPortalController::class, 'update'])->name('update');
    });

    // ==========================================
    //  STUDENT PORTAL
    // ==========================================
    Route::get('/student/dashboard', [StudentPortalController::class, 'index'])->name('student.dashboard');

    // ==========================================
    //  TEACHER ROUTES
    // ==========================================
    Route::get('/teacher/advisory', [TeacherController::class, 'advisory'])->name('teacher.advisory');

    // ==========================================
    //  ADMIN / REGISTRAR MODULES
    // ==========================================
    // Admission / Application Review
    Route::get('/admission', [EnrollmentController::class, 'index'])->name('admission.index');
    
    // NOTE: Dito mapupunta ang admin kapag kinlick ang notification
    Route::get('/admission/{id}', [EnrollmentController::class, 'show'])->name('admission.show'); 
    
    Route::patch('/admission/{id}', [EnrollmentController::class, 'process'])->name('admission.process');
    
    // Document Review Actions
    Route::get('/admission/approve_document/{id}/{doc_key}', [EnrollmentController::class, 'approveDocument'])->name('admission.approve_document');
    Route::get('/admission/decline_document/{id}/{doc_key}', [EnrollmentController::class, 'declineDocument'])->name('admission.decline_document');
    Route::get('/admission/{id}/pdf', [EnrollmentController::class, 'generatePdf'])->name('admission.pdf');

    // Official Enrollment Processing
    Route::get('/official-enrollment', [OfficialEnrollmentController::class, 'index'])->name('official-enrollment.index');
    Route::get('/official-enrollment/process/{id}', [OfficialEnrollmentController::class, 'show'])->name('official-enrollment.show');
    Route::post('/official-enrollment/store/{id}', [OfficialEnrollmentController::class, 'store'])->name('official-enrollment.store');
    Route::patch('/official-enrollment/return/{id}', [OfficialEnrollmentController::class, 'returnToApplicant'])->name('official-enrollment.return');

    // ==========================================
    //  ACADEMIC RESOURCES
    // ==========================================
    Route::get('/students/bulk-upload', [StudentController::class, 'bulkUploadForm'])->name('students.bulk-upload');
    Route::post('/students/bulk-upload', [StudentController::class, 'processBulkUpload'])->name('students.process-bulk-upload');
    Route::get('/students-enrollment-list', [StudentController::class, 'enrollmentList'])->name('students.enrollment'); 
    Route::resource('students', StudentController::class);

    Route::resource('sections', SectionController::class);
    Route::resource('subjects', SubjectController::class);
    
    Route::get('/my-schedules', [ScheduleController::class, 'mySchedules'])->name('schedules.my');
    Route::resource('schedules', ScheduleController::class);
    
    Route::patch('/grades/bulk-update', [GradeController::class, 'bulkUpdate'])->name('grades.bulk_update'); 
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/{section}', [GradeController::class, 'show'])->name('grades.show');

    Route::post('/attendances/bulk-store', [AttendanceController::class, 'bulkStore'])->name('attendances.bulk_store');
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/{section}', [AttendanceController::class, 'show'])->name('attendances.show');

    Route::resource('teams', TeamController::class);
    Route::resource('training-plans', TrainingPlanController::class);
    Route::resource('medical-records', MedicalRecordController::class);
    Route::resource('staff', StaffController::class);

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