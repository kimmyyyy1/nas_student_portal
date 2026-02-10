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
        $enrollees = Applicant::where('status', 'Officially Enrolled')
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

        // 🛡️ SECURITY CHECK (ADDED THIS)
        // Haharangin nito kung hindi pa "Officially Enrolled" ang status.
        // Iwas ito sa mga nagmamarunong na palitan ang URL/ID manually.
        if ($applicant->status !== 'Officially Enrolled') {
            return redirect()->route('official-enrollment.index')
                ->with('error', 'Security Alert: Unauthorized action. This applicant is not ready for enrollment.');
        }

        // Check if student already exists in student table via LRN
        if (Student::where('lrn', $applicant->lrn)->exists()) {
            return redirect()->route('official-enrollment.index')
                ->with('error', 'This student is already recorded in the Student Directory.');
        }

        DB::transaction(function () use ($applicant) {
            
            // A. GENERATE STUDENT ID (Format: 2026-30001)
            $year = date('Y');
            $studentId = $year . '-' . str_pad($applicant->id, 5, '0', STR_PAD_LEFT);

            $details = $applicant->enrollmentDetail;
            $files = is_string($applicant->uploaded_files) ? json_decode($applicant->uploaded_files, true) : ($applicant->uploaded_files ?? []);

            // B. CREATE STUDENT RECORD
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
                'grade_level'      => '7', 
                'status'           => 'Enrolled',
                
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

            // C. UPDATE USER ROLE
            $user = User::find($applicant->user_id);
            if($user) {
                $user->update(['role' => 'student']);
            }

            // D. UPDATE APPLICANT STATUS
            $applicant->update([
                'status' => 'Admitted'
            ]);
        });

        return redirect()->route('official-enrollment.index')
            ->with('success', 'Applicant successfully enrolled! Student record created and user role updated.');
    }

    /**
     * Return application to student for corrections.
     * ✅ FIX: Updates status to allow re-submission.
     */
    public function returnToApplicant(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);

        // Update status para makapag-edit ulit si Student.
        // Ang 'Qualified (Returned)' ay tatanggapin ng ApplicantPortalController.
        $applicant->update([
            'status' => 'Qualified (Returned)',
        ]);

        return redirect()->route('official-enrollment.index')
            ->with('success', 'Application successfully returned to the student for revision.');
    }
}