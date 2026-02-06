<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\EnrollmentRecord;
use App\Models\Student;
use App\Models\User;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OfficialEnrollmentController extends Controller
{
    /**
     * Display a listing of applicants endorsed for enrollment.
     */
    public function index()
    {
        // Kunin ang mga applicants na may status na 'Endorsed for Enrollment' 
        // kasama ang kanilang enrollment records.
        $qualifiedApplicants = Applicant::with('enrollmentRecord')
            ->where('status', 'Endorsed for Enrollment')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('official_enrollment.index', compact('qualifiedApplicants'));
    }

    /**
     * Show the enrollment verification page.
     */
    public function show($id)
    {
        // Load applicant with the separate enrollment record
        $application = Applicant::with('enrollmentRecord')->findOrFail($id);
        
        // Load available sections for the specific grade level applied
        $sections = Section::where('grade_level', $application->grade_level_applied)->get();

        return view('official_enrollment.show', compact('application', 'sections'));
    }

    /**
     * Finalize Enrollment Process (Moves data to Students table).
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
        ]);

        $applicant = Applicant::with('enrollmentRecord')->findOrFail($id);

        if ($applicant->status === 'Enrolled') {
            return redirect()->back()->with('error', 'Student is already enrolled.');
        }

        DB::beginTransaction();

        try {
            // 1. Generate Official Student ID
            $studentId = date('Y') . '-' . str_pad($applicant->id, 4, '0', STR_PAD_LEFT);

            // 2. Map User Account
            $user = User::where('id', $applicant->user_id)->first();
            if ($user) {
                $user->update(['role' => 'student']);
            }

            // 3. Create Student Record (Data Migration from Applicant & EnrollmentRecord)
            $student = Student::create([
                'user_id'           => $applicant->user_id,
                'section_id'        => $request->section_id,
                'student_id_number' => $studentId,
                'lrn'               => $applicant->lrn,
                'first_name'        => $applicant->first_name,
                'last_name'         => $applicant->last_name,
                'middle_name'       => $applicant->middle_name,
                'extension_name'    => $applicant->extension_name,
                'sex'               => $applicant->gender,
                'date_of_birth'     => $applicant->date_of_birth,
                'age'               => $applicant->age,
                'email'             => $applicant->email_address,
                'sport'             => $applicant->sport,
                
                // Address Mapping
                'region'            => $applicant->region,
                'province'          => $applicant->province,
                'municipality'      => $applicant->municipality_city,
                'barangay'          => $applicant->barangay,
                'street_address'    => $applicant->street_address,
                
                // Enrollment Status
                'enrollment_status' => 'Enrolled',
                'enrolled_at'       => now(),
            ]);

            // 4. Update Applicant & EnrollmentRecord Status
            $applicant->update(['status' => 'Enrolled']);
            
            if ($applicant->enrollmentRecord) {
                $applicant->enrollmentRecord->update(['enrollment_status' => 'Enrolled']);
            }

            DB::commit();

            return redirect()->route('official-enrollment.index')
                ->with('success', "Student {$student->first_name} successfully enrolled! ID: {$studentId}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Enrollment failed: ' . $e->getMessage());
        }
    }

    /**
     * Return application to student for corrections if documents are invalid.
     */
    public function returnToApplicant(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);
        $enrollment = EnrollmentRecord::where('applicant_id', $id)->firstOrFail();

        // Update remarks in the dedicated EnrollmentRecord table
        $enrollment->update([
            'document_remarks' => $request->remarks, // Inaasahan na array ito mula sa view
            'enrollment_status' => 'Pending'
        ]);

        // Ibalik ang status ng applicant para ma-edit nila
        $applicant->update(['status' => 'With Pending Requirements']);

        return redirect()->route('official-enrollment.index')
            ->with('success', 'Application sent back to student for corrections.');
    }
}