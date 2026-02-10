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

// ==========================================
//  LOGGED IN USERS (SHARED ROUTES)
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard & Common Profile
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/recent-activity', [DashboardController::class, 'getRecentActivity'])->name('recent.activity');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    
    // Notifications
    Route::get('/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.readAll');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    //  GROUP 1: APPLICANT PORTAL (Only for Applicants)
    // ==========================================
    Route::prefix('applicant')->name('applicant.')->middleware('role:applicant')->group(function() {
        Route::get('/dashboard', [ApplicantPortalController::class, 'index'])->name('dashboard');
        Route::get('/apply', [ApplicantPortalController::class, 'create'])->name('create');
        Route::post('/apply', [ApplicantPortalController::class, 'store'])->name('store');
        Route::get('/requirements', [ApplicantPortalController::class, 'showRequirements'])->name('requirements');
        Route::post('/requirements', [ApplicantPortalController::class, 'storeRequirements'])->name('store_requirements');
        Route::get('/enrollment', [ApplicantPortalController::class, 'showEnrollmentForm'])->name('enrollment.show');
        Route::post('/enrollment', [ApplicantPortalController::class, 'submitEnrollmentForm'])->name('enrollment.store');
        Route::get('/view-file/{id}/{type}', [ApplicantPortalController::class, 'viewFile'])->name('view_file');
        Route::get('/edit', [ApplicantPortalController::class, 'edit'])->name('edit');
        Route::patch('/edit', [ApplicantPortalController::class, 'update'])->name('update');
    });

    // ==========================================
    //  GROUP 2: STUDENT PORTAL (Only for Students)
    // ==========================================
    Route::middleware('role:student')->group(function () {
        Route::get('/student/dashboard', [StudentPortalController::class, 'index'])->name('student.dashboard');
        // Add other student-only routes here (e.g., View Grades)
    });

    // ==========================================
    //  GROUP 3: TEACHER PORTAL (Only for Teachers)
    // ==========================================
    Route::middleware('role:teacher')->group(function () {
        Route::get('/teacher/advisory', [TeacherController::class, 'advisory'])->name('teacher.advisory');
        // Add grading routes here if teachers need to encode grades
    });

    // ==========================================
    //  GROUP 4: ADMIN / REGISTRAR (THE FORTRESS) 🛡️
    //  Dito natin nilagay ang "role:admin" para secured!
    // ==========================================
    Route::middleware('role:admin')->group(function () {
        
        // 1. Admission / Review
        Route::get('/admission', [EnrollmentController::class, 'index'])->name('admission.index');
        Route::get('/admission/{id}', [EnrollmentController::class, 'show'])->name('admission.show'); 
        Route::patch('/admission/{id}', [EnrollmentController::class, 'process'])->name('admission.process');
        Route::get('/admission/approve_document/{id}/{doc_key}', [EnrollmentController::class, 'approveDocument'])->name('admission.approve_document');
        Route::get('/admission/decline_document/{id}/{doc_key}', [EnrollmentController::class, 'declineDocument'])->name('admission.decline_document');
        Route::get('/admission/{id}/pdf', [EnrollmentController::class, 'generatePdf'])->name('admission.pdf');

        // 2. Official Enrollment (ITO ANG GUSTO MONG PROTEKTAHAN)
        Route::get('/official-enrollment', [OfficialEnrollmentController::class, 'index'])->name('official-enrollment.index');
        Route::get('/official-enrollment/process/{id}', [OfficialEnrollmentController::class, 'show'])->name('official-enrollment.show');
        Route::post('/official-enrollment/store/{id}', [OfficialEnrollmentController::class, 'store'])->name('official-enrollment.store');
        Route::patch('/official-enrollment/return/{id}', [OfficialEnrollmentController::class, 'returnToApplicant'])->name('official-enrollment.return');

        // 3. Management Modules
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

        // 4. Reports
        Route::prefix('reports')->name('reports.')->group(function() {
            Route::get('/grade-sheets', [ReportController::class, 'gradeSheets'])->name('grade_sheets');
            Route::get('/report-cards', [ReportController::class, 'reportCards'])->name('report_cards');
            Route::get('/school-forms', [ReportController::class, 'schoolForms'])->name('school_forms');
            Route::get('/awardees', [ReportController::class, 'awardees'])->name('awardees');
            Route::get('/ranking', [ReportController::class, 'ranking'])->name('ranking');
        });
        Route::resource('reports', ReportController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);

    }); // <-- END OF ADMIN MIDDLEWARE GROUP

}); // <-- END OF AUTH MIDDLEWARE

require __DIR__.'/auth.php';