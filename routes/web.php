<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

// --- CORE CONTROLLERS ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController; 

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

// 1. ROOT REDIRECT LOGIC
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'student') return redirect()->route('student.dashboard');
        if ($user->role === 'applicant') return redirect()->route('applicant.dashboard');
        if ($user->role === 'teacher') return redirect()->route('dashboard'); 
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

// 2. UTILITY ROUTE: CLEAR CACHE
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

    // ⚡ THE MASTER DASHBOARD ROUTE ⚡
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/recent-activity', [DashboardController::class, 'getRecentActivity'])->name('recent.activity');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    
    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    //  GROUP 1: APPLICANT PORTAL
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
    //  GROUP 2: STUDENT PORTAL
    // ==========================================
    Route::middleware('role:student')->group(function () {
        Route::get('/student/dashboard', [StudentPortalController::class, 'index'])->name('student.dashboard');
        
        // ⚡ DISABLED MUNA PARA SA CLEANUP (CLOUD ERROR FIX) ⚡
        Route::get('/student/renew-enrollment', \App\Livewire\ContinuingEnrollment::class)->name('student.renew-enrollment');
    });

    // ==========================================
    //  GROUP 3: TEACHER PORTAL
    // ==========================================
    Route::middleware('role:teacher')->group(function () {
        Route::get('/teacher/advisory', [TeacherController::class, 'advisory'])->name('teacher.advisory');
    });

    // ==========================================
    //  GROUP 3.5: SHARED (ADMIN & TEACHER) 🏫
    // ==========================================
    Route::middleware('role:admin,teacher')->group(function () {
        Route::get('/my-schedules', [ScheduleController::class, 'mySchedules'])->name('schedules.my');
        
        Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
        Route::get('/grades/{section}', [GradeController::class, 'show'])->name('grades.show');
        Route::patch('/grades/bulk-update', [GradeController::class, 'bulkUpdate'])->name('grades.bulk_update'); 

        Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::get('/attendances/{section}', [AttendanceController::class, 'show'])->name('attendances.show');
        Route::post('/attendances/bulk-store', [AttendanceController::class, 'bulkStore'])->name('attendances.bulk_store');
    });

    // ==========================================
    //  GROUP 4: ADMIN (THE FORTRESS) 🛡️
    // ==========================================
    
    // 4A. ADMIN ONLY (PICT Support)
    Route::middleware('role:admin')->group(function () {
        
        // System Settings
        Route::get('/admin/settings', [SettingController::class, 'index'])->name('admin.settings');
        Route::post('/admin/settings', [SettingController::class, 'update'])->name('admin.settings.update');

        Route::resource('teams', TeamController::class);
        Route::resource('training-plans', TrainingPlanController::class);
        Route::resource('medical-records', MedicalRecordController::class);
        Route::resource('staff', StaffController::class);
    });

    // 4B. ADMIN & REGISTRAR SHARED
    Route::middleware('role:admin,registrar')->group(function () {
        
        // 1. Admission / Review
        Route::get('/admission', [EnrollmentController::class, 'index'])->name('admission.index');
        Route::get('/admission/{id}', [EnrollmentController::class, 'show'])->name('admission.show'); 
        Route::patch('/admission/{id}', [EnrollmentController::class, 'process'])->name('admission.process');
        Route::get('/admission/approve_document/{id}/{doc_key}', [EnrollmentController::class, 'approveDocument'])->name('admission.approve_document');
        Route::get('/admission/decline_document/{id}/{doc_key}', [EnrollmentController::class, 'declineDocument'])->name('admission.decline_document');
        Route::get('/admission/{id}/pdf', [EnrollmentController::class, 'generatePdf'])->name('admission.pdf');

        // 2. Official Enrollment (Admission Confirmation)
        Route::get('/official-enrollment', [OfficialEnrollmentController::class, 'index'])->name('official-enrollment.index');
        Route::get('/official-enrollment/export', [OfficialEnrollmentController::class, 'export'])->name('official-enrollment.export');
        Route::get('/official-enrollment/process/{id}', [OfficialEnrollmentController::class, 'show'])->name('official-enrollment.show');
        Route::post('/official-enrollment/store/{id}', [OfficialEnrollmentController::class, 'store'])->name('official-enrollment.store');
        Route::patch('/official-enrollment/return/{id}', [OfficialEnrollmentController::class, 'returnToApplicant'])->name('official-enrollment.return');

        // 3. Management Modules
        Route::get('/students/bulk-upload', [StudentController::class, 'bulkUploadForm'])->name('students.bulk-upload');
        Route::post('/students/bulk-upload', [StudentController::class, 'processBulkUpload'])->name('students.process-bulk-upload');
        Route::get('/students-enrollment-list', [StudentController::class, 'enrollmentList'])->name('students.enrollment'); 
        
        // ⚡ CLEANUP: Disabled 'create' and 'store' for students as they come from Admission ⚡
        Route::post('/students/bulk-update-status', [StudentController::class, 'bulkUpdateStatus'])->name('students.bulk-update-status');
        Route::post('/students/bulk-finalize', [StudentController::class, 'bulkFinalize'])->name('students.bulk-finalize');
        Route::post('/students/bulk-unfinalize', [StudentController::class, 'bulkUnfinalize'])->name('students.bulk-unfinalize');
        Route::post('/students/{student}/toggle-lock', [StudentController::class, 'toggleLock'])->name('students.toggle-lock');
        Route::resource('students', StudentController::class)->except(['create', 'store']);

        Route::resource('sections', SectionController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('schedules', ScheduleController::class);

        // 4. Reports
        Route::prefix('reports')->name('reports.')->group(function() {
            Route::get('/grade-sheets', [ReportController::class, 'gradeSheets'])->name('grade_sheets');
            Route::get('/report-cards', [ReportController::class, 'reportCards'])->name('report_cards');
            Route::get('/school-forms', [ReportController::class, 'schoolForms'])->name('school_forms');
            Route::get('/awardees', [ReportController::class, 'awardees'])->name('awardees');
            Route::get('/ranking', [ReportController::class, 'ranking'])->name('ranking');
        });
        Route::resource('reports', ReportController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);

    }); 

}); 

require __DIR__.'/auth.php';