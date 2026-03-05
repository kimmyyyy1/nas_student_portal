<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Student;
use App\Models\User;
use App\Models\EnrollmentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EnrollmentStatusUpdatedNotification;
use App\Models\AuditLog;

class OfficialEnrollmentController extends Controller
{
    /**
     * Display a listing of applicants pending enrollment verification.
     */
    public function index()
    {
        $enrollees = Applicant::whereIn('status', ['For Enrollment Verification', 'Pending Renewal'])
                              ->with('enrollmentDetail')
                              ->orderBy('updated_at', 'desc')
                              ->paginate(10);

        return view('official_enrollment.index', compact('enrollees'));
    }

    /**
     * Show the enrollment verification page.
     * UPDATED: Now handles marking notifications as read.
     */
    public function show(Request $request, $id) 
    {
        // 👇👇👇 NOTIFICATION LOGIC 👇👇👇
        // 1. Mark Notification as Read globally for all admins viewing this application
        if ($request->has('read')) {
            \Illuminate\Support\Facades\DB::table('notifications')
                ->whereNull('read_at')
                ->where(function($q) use ($id) {
                    $q->where('data', 'like', '%"applicant_id":' . $id . '%')
                      ->orWhere('data', 'like', '%"applicant_id":"' . $id . '"%');
                })
                ->update(['read_at' => now()]);
        }
        // 👆👆👆 END LOGIC 👆👆👆

        $applicant = Applicant::with('enrollmentDetail')->findOrFail($id);
        return view('official_enrollment.show', compact('applicant'));
    }

    /**
     * Finalize Enrollment Process (Moves data to Students table).
     */
    public function store(Request $request, $id)
    {
        $applicant = Applicant::with('enrollmentDetail')->findOrFail($id);

        // 🛡️ SECURITY CHECK
        if (!in_array($applicant->status, ['For Enrollment Verification', 'Pending Renewal'])) {
            return redirect()->route('official-enrollment.index')
                ->with('error', 'Security Alert: Unauthorized action. This applicant is not ready for enrollment.');
        }

        DB::transaction(function () use ($applicant, $request) {
            $details = $applicant->enrollmentDetail;
            $files = is_string($applicant->uploaded_files) ? json_decode($applicant->uploaded_files, true) : ($applicant->uploaded_files ?? []);
            $remarks = is_string($applicant->document_remarks) ? json_decode($applicant->document_remarks, true) : ($applicant->document_remarks ?? []);
            
            // Check if it's a renewal
            $isRenewal = ($applicant->status === 'Pending Renewal') || ($remarks['is_renewal'] ?? false);
            
            // Find existing student by LRN
            $existingStudent = Student::where('lrn', $applicant->lrn)->first();

            if ($existingStudent) {
                // RENEWAL LOGIC: Update existing record
                $updateData = [
                    'status' => 'Enrolled',
                    'enrollment_date' => now(),
                    'promotion_status' => null, // Clear promotion status once officially enrolled
                ];

                // Update Student ID if provided by Registrar
                if ($request->student_id) {
                    $updateData['nas_student_id'] = $request->student_id;
                }

                // Update Grade Level if specified in renewal
                if (isset($remarks['renewal_grade_level'])) {
                    $updateData['grade_level'] = $remarks['renewal_grade_level'];
                }

                // Update Photo if provided
                if (isset($files['id_picture'])) {
                    $updateData['id_picture'] = $files['id_picture'];
                }

                $existingStudent->update($updateData);
                
            } else {
                // NEW STUDENT LOGIC: Create record
                // Use Registrar-entered Student No.
                $studentId = $request->student_id;

                Student::create([
                    'nas_student_id'   => $studentId, 
                    'lrn'              => $applicant->lrn,
                    'id_picture'       => $files['id_picture'] ?? null, 

                    // Personal Info
                    'first_name'       => $applicant->first_name,
                    'last_name'        => $applicant->last_name,
                    'middle_name'      => $applicant->middle_name,
                    'sex'              => $applicant->gender,
                    'birthdate'        => $applicant->date_of_birth, 
                    'age'              => $applicant->age,
                    
                    // Special Categories
                    'is_ip'            => $applicant->is_ip,
                    'is_pwd'           => $applicant->is_pwd,
                    'is_4ps'           => $applicant->is_4ps,

                    // Academic & Sports
                    'grade_level'      => '7', // Default for new
                    'status'           => 'Enrolled',
                    'enrollment_date'  => now(),
                    
                    // Address & Contact
                    'region'           => $applicant->region,
                    'province'         => $applicant->province,
                    'municipality_city'=> $applicant->municipality_city, 
                    'barangay'         => $applicant->barangay,
                    'street_address'   => $details->street_house_no ?? $applicant->street_address,
                    'zip_code'         => $applicant->zip_code,
                    'contact_number'   => $applicant->guardian_contact,
                    
                    // Email
                    'email_address'    => $details->email ?? ($applicant->user->email ?? 'N/A'), 

                    // Guardian Info
                    'guardian_name'         => $applicant->guardian_name,
                    'guardian_relationship' => $applicant->guardian_relationship,
                    'guardian_contact'      => $applicant->guardian_contact,
                    
                    // NASCENT SAS Specific
                    'sport'                 => $applicant->sport,
                    'sport_specification'   => $applicant->sport_specification,
                    'ip_group_name'         => $applicant->ip_group_name,
                    'pwd_disability'        => $applicant->pwd_disability,
                    'heard_about_nas'       => $applicant->heard_about_nas,
                    'referrer_name'         => $applicant->referrer_name,
                    'attended_articulation' => $applicant->attended_articulation ?? 'No',
                    'school_name'           => $applicant->school_name,
                    'school_type'           => $applicant->school_type,
                    'last_grade_level'      => $applicant->school_last_grade_level,
                    'last_school_year'      => $applicant->school_last_year_completed,
                    'school_id'             => $applicant->school_id,
                    'school_address'        => $applicant->school_address,
                    'guardian_email'        => $applicant->guardian_email,
                    'guardian_address'      => $details->guardian_address ?? 'Same as Student',
                ]);
            }

            // C. UPDATE USER ROLE & STUDENT ID (Ensure they are a student and ID matches)
            $user = User::find($applicant->user_id);
            if($user) {
                $userUpdates = [];
                if (!$isRenewal || $user->role !== 'student') {
                    $userUpdates['role'] = 'student';
                }
                
                // Sync the user's student_id with the one provided by Registrar
                $finalStudentId = $isRenewal 
                    ? ($request->student_id ?? $existingStudent->nas_student_id)
                    : $studentId;

                if ($finalStudentId && $user->student_id !== $finalStudentId) {
                    $userUpdates['student_id'] = $finalStudentId;
                }

                if (!empty($userUpdates)) {
                    $user->update($userUpdates);
                }
            }

            // D. UPDATE APPLICANT STATUS
            $applicant->update([
                'status' => 'Officially Enrolled'
            ]);

            // Notify Applicant
            if ($user) {
                Notification::send($user, new EnrollmentStatusUpdatedNotification($applicant, 'Officially Enrolled'));
            }

            // LOG AUDIT TRAIL
            AuditLog::create([
                'user_id' => Auth::id(),
                'applicant_id' => $applicant->id,
                'action' => 'Finalized Enrollment',
                'details' => json_encode(['to' => 'Officially Enrolled', 'remarks' => 'Student officially enrolled by Registrar'])
            ]);
        });

        return redirect()->route('official-enrollment.index')
            ->with('success', 'Enrollment finalized successfully!');
    }

    /**
     * Return application to student for corrections.
     * ✅ FIX: Added Security Check to prevent returning Admitted students and handling Renewals.
     */
    public function returnToApplicant(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);

        $currentRemarks = is_string($applicant->document_remarks) ? json_decode($applicant->document_remarks, true) : ($applicant->document_remarks ?? []);
        $isRenewal = $applicant->status === 'Pending Renewal' || ($currentRemarks['is_renewal'] ?? false);

        // 🛡️ SECURITY CHECK FOR RETURN
        if ($applicant->status !== 'For Enrollment Verification' && $applicant->status !== 'Pending Renewal') {
            return redirect()->route('official-enrollment.index')
                ->with('error', 'Action denied. You can only return active applications.');
        }

        // Capture document-specific remarks and overall remarks
        $documentRemarks = $request->input('document_remarks', []);
        $overallRemarks = $request->input('overall_remarks', '');

        // Merge into the document_remarks column
        $newRemarks = array_merge($currentRemarks, [
            'documents' => $documentRemarks,
            'overall' => $overallRemarks,
            'returned_at' => now()->toDateTimeString(),
        ]);

        // Update status para makapag-edit ulit si Student.
        $newStatus = $isRenewal ? 'Renewal (Returned)' : 'Qualified (Returned)';
        $oldStatus = $applicant->status;
        $applicant->update([
            'status' => $newStatus,
            'document_remarks' => $newRemarks
        ]);

        // LOG AUDIT TRAIL
        AuditLog::create([
            'user_id' => Auth::id(),
            'applicant_id' => $applicant->id,
            'action' => 'Returned Enrollment Documents',
            'details' => json_encode(['from' => $oldStatus, 'to' => $newStatus, 'remarks' => $overallRemarks])
        ]);

        // Notify Applicant
        $user = User::find($applicant->user_id);
        if ($user) {
            Notification::send($user, new EnrollmentStatusUpdatedNotification($applicant, $newStatus, $overallRemarks));
        }

        return redirect()->route('official-enrollment.index')
            ->with('success', 'Application successfully returned to the student for revision.');
    }

    /**
     * Export Enrollment Records to CSV
     */
    public function export(Request $request)
    {
        $search = $request->input('search');
        $filterSport = $request->input('sport');
        $filterRegion = $request->input('region');
        $filterStatus = $request->input('status');

        $query = Applicant::whereIn('status', [
            'For Enrollment Verification', 
            'Qualified (Returned)',
            'Renewal (Returned)',
            'Officially Enrolled', 
            'Pending Renewal',     
            'Admitted',            
            'Enrolled'             
        ]);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('lrn', 'like', '%' . $search . '%')
                  ->orWhere('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%');
            });
        }

        if (!empty($filterSport)) {
            $query->where('sport', $filterSport);
        }

        if (!empty($filterRegion)) {
            $query->where('region', $filterRegion);
        }

        if (!empty($filterStatus)) {
            $query->where('status', $filterStatus);
        }

        $enrollees = $query->latest()->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=nascentsas_enrollment_records.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'App ID', 'LRN', 'Last Name', 'First Name', 'Middle Name', 
            'Sex', 'Region', 'Province', 'Sport', 'Status', 'Record Type'
        ];

        $callback = function() use($enrollees, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($enrollees as $enrollee) {
                $remarks = is_string($enrollee->document_remarks) ? json_decode($enrollee->document_remarks, true) : ($enrollee->document_remarks ?? []);
                $recordType = ($enrollee->status === 'Pending Renewal') || ($remarks['is_renewal'] ?? false) ? 'Old (Renewal)' : 'New';

                fputcsv($file, [
                    $enrollee->id,
                    $enrollee->lrn ?? 'N/A',
                    $enrollee->last_name,
                    $enrollee->first_name,
                    $enrollee->middle_name ?? '',
                    $enrollee->gender,
                    $enrollee->region,
                    $enrollee->province,
                    $enrollee->sport ?? 'N/A',
                    $enrollee->status,
                    $recordType
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}