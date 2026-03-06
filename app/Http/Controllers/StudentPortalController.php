<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Models\Student;
use App\Models\Applicant;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewApplicantNotification;

class StudentPortalController extends Controller
{
    /**
     * Show the Student Dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Hanapin ang Student record gamit ang email o student_id link
        // Eager load relationships para optimized ang database query
        $student = Student::where('email_address', $user->email)
                          ->orWhere('id', $user->student_id)
                          ->with([
                              'section.schedules.subject',  // Para sa Class Schedule
                              'section.schedules.staff',    // Para sa Teacher info
                              'team',                       // Para sa Sports info
                              'grades.schedule.subject',    // Para sa Grades table
                              
                              // 👇 FIX: Tinanggal ang '.schedule.subject' kasi per-day ang attendance natin
                              'attendances',                // Load lang ang attendance records
                              
                              'awards',                     // Para sa Awards & Recognition
                              'media'                       // Para makuha ang Spatie Picture
                          ])
                          ->first();

        // Kung walang student record (error prevention), ibalik sa login page may error msg
        if (!$student) {
            return redirect()->route('login')->with('error', 'No student record found linked to this account.');
        }

        // Return view
        return view('student-portal.dashboard', compact('student'));
    }

    /**
     * Show the Continuing Enrollment Renewal Form.
     */
    public function renewEnrollment()
    {
        $user = Auth::user();
        $student = Student::where('nas_student_id', $user->student_id)->first();
        
        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'Student record not found.');
        }

        $applicant = Applicant::where('user_id', $user->id)
                         ->orWhere('lrn', $student->lrn ?? null)
                         ->first();

        // Calculate next grade
        $currentGrade = $student->grade_level ?? ($student->section->grade_level ?? 'Grade 7');
        $currentGradeNum = (int) filter_var($currentGrade, FILTER_SANITIZE_NUMBER_INT);
        $nextGradeNum = $currentGradeNum < 12 && $currentGradeNum > 0 ? $currentGradeNum + 1 : ($currentGradeNum ?: 7);
        $computedGradeLevel = "Grade " . $nextGradeNum;

        $statuses = [];
        $remarks = [];
        $currentFiles = [];
        $grade_level = $computedGradeLevel;

        if ($applicant) {
            $statuses = is_string($applicant->document_statuses) ? json_decode($applicant->document_statuses, true) : ($applicant->document_statuses ?? []);
            $remarks = is_string($applicant->document_remarks) ? json_decode($applicant->document_remarks, true) : ($applicant->document_remarks ?? []);
            $currentFiles = is_string($applicant->uploaded_files) ? json_decode($applicant->uploaded_files, true) : ($applicant->uploaded_files ?? []);
            
            if (isset($remarks['renewal_grade_level'])) {
                $grade_level = $remarks['renewal_grade_level'];
            }
        }

        return view('student.renew_enrollment', compact('student', 'applicant', 'statuses', 'remarks', 'currentFiles', 'grade_level'));
    }

    /**
     * Store the Continuing Enrollment Renewal Data.
     */
    public function storeRenewal(Request $request)
    {
        set_time_limit(600);
        $user = Auth::user();
        $student = Student::where('nas_student_id', $user->student_id)->firstOrFail();
        
        $applicant = Applicant::where('user_id', $user->id)
                         ->orWhere('lrn', $student->lrn ?? null)
                         ->first();

        $statuses = $applicant ? (is_string($applicant->document_statuses) ? json_decode($applicant->document_statuses, true) : ($applicant->document_statuses ?? [])) : [];
        $currentFiles = $applicant ? (is_string($applicant->uploaded_files) ? json_decode($applicant->uploaded_files, true) : ($applicant->uploaded_files ?? [])) : [];

        // Validation Rules
        $fields = [
            'sa_info_form'          => 'renewal_sa_info_form',
            'basic_ed_form'         => 'renewal_basic_ed_form',
            'scholarship_agreement' => 'renewal_scholarship_agreement',
            'uniform_measurement'   => 'renewal_uniform_measurement',
            'health_assessment'     => 'renewal_health_assessment',
            'passport'              => 'renewal_passport',
            'mother_id'             => 'renewal_mother_id',
            'father_id'             => 'renewal_father_id',
            'guardian_id'           => 'renewal_guardian_id',
            'student_id_file'       => 'renewal_student_id',
        ];

        $rules = [];
        foreach ($fields as $inputName => $dbKey) {
            $status = $statuses[$dbKey] ?? 'pending';
            $isMissing = !isset($currentFiles[$dbKey]) || empty($currentFiles[$dbKey]);
            $isOptional = in_array($inputName, ['passport', 'mother_id', 'father_id', 'student_id_file']);
            
            if (!$isOptional && ($isMissing || $status === 'declined')) {
                $rules[$inputName] = 'required|file|mimes:jpg,jpeg,png,pdf|max:20480';
            } else {
                $rules[$inputName] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480';
            }
        }

        $request->validate($rules);

        // Upload to Local Storage
        try {
            $fileUrls = [];
            $updatedStatuses = $statuses;
            $isNewSubmission = false;

            foreach ($fields as $inputName => $key) {
                if ($request->hasFile($inputName)) {
                    $file = $request->file($inputName);
                    
                    // Keep the exact filename or generate a unique one. Let's make it unique but readable.
                    $extension = $file->getClientOriginalExtension();
                    $filename = str_replace(' ', '_', strtolower($student->last_name . '_' . $student->first_name)) . '_' . $inputName . '_' . time() . '.' . $extension;
                    
                    // Store in local public disk: storage/app/public/nas_requirements
                    $path = $file->storeAs('nas_requirements', $filename, 'public');
                    
                    // Generate absolute URL for the frontend
                    $fileUrls[$key] = asset('storage/' . $path);
                    $updatedStatuses[$key] = 'pending';
                    $isNewSubmission = true;
                }
            }

            // Update Student Record
            $student->update([
                'status' => 'Pending Renewal',
                'enrollment_remarks' => 'Renewal requested for ' . $request->grade_level
            ]);

            // Create/Update Applicant Record
            if (!$applicant) {
                $applicant = Applicant::create([
                    'user_id' => $user->id,
                    'lrn' => $student->lrn,
                    'last_name' => $student->last_name,
                    'first_name' => $student->first_name,
                    'middle_name' => $student->middle_name,
                    'gender' => $student->sex,
                    'sex' => $student->sex,
                    'date_of_birth' => $student->birthdate,
                    'email_address' => $student->email_address,
                    'status' => 'Pending Renewal',
                ]);
            }

            // Merge Files
            foreach ($fileUrls as $key => $url) {
                $currentFiles[$key] = $url;
                if ($key == 'renewal_sa_info_form') $currentFiles['scholarship_form'] = $url;
                if ($key == 'renewal_health_assessment') $currentFiles['medical_clearance'] = $url;
            }

            $applicant->update([
                'status' => 'Pending Renewal',
                'uploaded_files' => $currentFiles,
                'document_statuses' => $updatedStatuses,
                'document_remarks' => array_merge(is_string($applicant->document_remarks) ? json_decode($applicant->document_remarks, true) : ($applicant->document_remarks ?? []), [
                    'renewal_grade_level' => $request->grade_level,
                    'renewal_date' => now()->toDateTimeString(),
                    'is_renewal' => true
                ])
            ]);

            // Notifications
            if ($isNewSubmission) {
                $admins = User::whereIn('role', ['admin', 'registrar'])->get();
                $message = "Continuing Enrollment renewal submitted by: {$applicant->first_name} {$applicant->last_name} ({$request->grade_level})";
                Notification::send($admins, new NewApplicantNotification($applicant, $message));
            }

            return redirect()->route('student.dashboard')->with('success', 'Renewal documents updated and submitted for review!');

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}