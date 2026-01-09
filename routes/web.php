<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

// --- CONTROLLER IMPORTS ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\OfficialEnrollmentController;
use App\Http\Controllers\ApplicantPortalController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\TeacherController;

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
        if ($role === 'student') return redirect()->route('student.dashboard');
        if ($role === 'applicant') return redirect()->route('applicant.dashboard');
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

// --- DEBUG ROUTE: FORCE CLEAR CACHE & CHECK CONFIG ---
// Ito ang binago natin para makita ang laman ng settings mo
Route::get('/clear-all', function() {
    try {
        // 1. Clear Cache
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        
        // 2. Basahin ang Config pagkatapos i-clear
        $config = config('cloudinary');
        $fileSystem = config('filesystems.disks.cloudinary');

        // 3. I-return ang resulta bilang JSON para madaling basahin
        return response()->json([
            'message' => 'Cache Cleared Successfully!',
            
            // Check 1: Nababasa ba ang Cloudinary Config File?
            'cloudinary_config_found' => !is_null($config),
            
            // Check 2: Meron bang 'cloud' key sa loob? (Ito ang error mo)
            'has_cloud_key' => isset($config['cloud']),
            
            // Check 3: Ano ang laman ng config? (Para makita natin kung nested)
            'cloudinary_config_content' => $config,
            
            // Check 4: Check ang Filesystem Disk settings
            'filesystem_disk_config' => $fileSystem,
            
            // Check 5: Nababasa ba ang ENV variable?
            'env_url_check' => env('CLOUDINARY_URL'),
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// --- AUTHENTICATED ROUTES GROUP ---
Route::middleware(['auth', 'verified'])->group(function () {

    // --- MAIN DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- USER PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    //  APPLICANT PORTAL
    // ==========================================
    Route::get('/applicant/dashboard', [ApplicantPortalController::class, 'index'])->name('applicant.dashboard');
    Route::get('/applicant/apply', [ApplicantPortalController::class, 'create'])->name('applicant.create');
    Route::post('/applicant/apply', [ApplicantPortalController::class, 'store'])->name('applicant.store');
    Route::get('/applicant/edit', [ApplicantPortalController::class, 'edit'])->name('applicant.edit');
    Route::patch('/applicant/edit', [ApplicantPortalController::class, 'update'])->name('applicant.update');
    Route::post('/applicant/submit-requirements', [ApplicantPortalController::class, 'submitRequirements'])->name('applicant.submit_requirements');

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
    Route::get('/admission', [EnrollmentController::class, 'index'])->name('admission.index');
    Route::get('/admission/{id}', [EnrollmentController::class, 'show'])->name('admission.show');
    Route::patch('/admission/{id}', [EnrollmentController::class, 'process'])->name('admission.process');
    Route::get('/admission/{id}/pdf', [EnrollmentController::class, 'generatePdf'])->name('admission.pdf');

    Route::get('/official-enrollment/process/{id}', [OfficialEnrollmentController::class, 'show'])->name('official-enrollment.show');
    Route::post('/official-enrollment/store/{id}', [OfficialEnrollmentController::class, 'store'])->name('official-enrollment.store');

    // ==========================================
    //  ACADEMIC RESOURCES (CRUD)
    // ==========================================
    Route::resource('students', StudentController::class);
    Route::get('/students-enrollment-list', [StudentController::class, 'enrollmentList'])->name('students.enrollment'); 

    Route::resource('sections', SectionController::class);
    Route::resource('subjects', SubjectController::class);
    
    Route::resource('schedules', ScheduleController::class);
    Route::get('/my-schedules', [ScheduleController::class, 'mySchedules'])->name('schedules.my');
    
    // --- GRADES MANAGEMENT ---
    Route::patch('/grades/bulk-update', [GradeController::class, 'bulkUpdate'])->name('grades.bulk_update'); 
    Route::get('/grades/{section}', [GradeController::class, 'show'])->name('grades.show');
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');

    // --- ATTENDANCE MANAGEMENT ---
    Route::post('/attendances/bulk-store', [AttendanceController::class, 'bulkStore'])->name('attendances.bulk_store');
    Route::get('/attendances/{section}', [AttendanceController::class, 'show'])->name('attendances.show');
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');

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

    // --- REPORTS MANAGEMENT ---
    Route::get('/reports/grade-sheets', [ReportController::class, 'gradeSheets'])->name('reports.grade_sheets');
    Route::get('/reports/report-cards', [ReportController::class, 'reportCards'])->name('reports.report_cards');
    Route::get('/reports/school-forms', [ReportController::class, 'schoolForms'])->name('reports.school_forms');
    Route::get('/reports/awardees', [ReportController::class, 'awardees'])->name('reports.awardees');
    Route::get('/reports/ranking', [ReportController::class, 'ranking'])->name('reports.ranking');
    
    Route::resource('reports', ReportController::class);

});

require __DIR__.'/auth.php';