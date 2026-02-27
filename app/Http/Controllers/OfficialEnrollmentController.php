<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Student;
use App\Models\User;
use App\Models\EnrollmentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OfficialEnrollmentController extends Controller
{
    /**
     * Display a listing of applicants pending enrollment verification.
     */
    public function index()
    {
        $enrollees = Applicant::whereIn('status', ['Officially Enrolled', 'Pending Renewal'])
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
        if ($request->has('read')) {
            $notificationId = $request->query('read');
            $notification = Auth::user()->notifications()->find($notificationId);
            
            if ($notification) {
                $notification->markAsRead();
            }
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
        if (!in_array($applicant->status, ['Officially Enrolled', 'Pending Renewal'])) {
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
                    'guardian_email'        => $applicant->guardian_email,
                    'guardian_address'      => $details->guardian_address ?? 'Same as Student',
                ]);
            }

            // C. UPDATE USER ROLE (Ensure they are a student)
            $user = User::find($applicant->user_id);
            if($user) {
                // For renewals, the role might already be 'student'. Only update if not already.
                if (!$isRenewal || $user->role !== 'student') {
                    $user->update(['role' => 'student']);
                }
            }

            // D. UPDATE APPLICANT STATUS
            $applicant->update([
                'status' => 'Admitted'
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

        $remarks = is_string($applicant->document_remarks) ? json_decode($applicant->document_remarks, true) : ($applicant->document_remarks ?? []);
        $isRenewal = $applicant->status === 'Pending Renewal' || ($remarks['is_renewal'] ?? false);

        // 🛡️ SECURITY CHECK FOR RETURN
        if ($applicant->status !== 'Officially Enrolled' && $applicant->status !== 'Pending Renewal') {
            return redirect()->route('official-enrollment.index')
                ->with('error', 'Action denied. You can only return active applications.');
        }

        // Update status para makapag-edit ulit si Student.
        $newStatus = $isRenewal ? 'Renewal (Returned)' : 'Qualified (Returned)';
        $applicant->update([
            'status' => $newStatus,
        ]);

        return redirect()->route('official-enrollment.index')
            ->with('success', 'Application successfully returned to the student for revision.');
    }
}