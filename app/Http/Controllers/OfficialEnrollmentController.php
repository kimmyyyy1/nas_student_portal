<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnrollmentApplication;
use App\Models\Student;
use App\Models\Section;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OfficialEnrollmentController extends Controller
{
    /**
     * Show the confirmation form with application details and section selection.
     */
    public function show($id)
    {
        $application = EnrollmentApplication::findOrFail($id);

        // Kunin ang mga sections na akma sa Grade Level ng applicant
        $sections = Section::where('grade_level', $application->grade_level_applied)
                            ->orderBy('section_name')
                            ->get();

        return view('official_enrollment.show', compact('application', 'sections'));
    }

    /**
     * Process the enrollment: Save to Student table, Create/Update User, Return Password.
     */
    public function store(Request $request, $id)
    {
        // 1. Validation
        $request->validate([
            'section_id' => 'required|exists:sections,id',
        ]);

        $application = EnrollmentApplication::findOrFail($id);

        // Safety Check: Kung enrolled na, wag na ulitin
        if ($application->status === 'Enrolled') {
             return redirect()->route('students.index')->with('error', 'Student is already enrolled.');
        }

        // 2. Generate Student ID (Format: YYYY-AppID e.g., 2025-0020)
        $generatedStudentId = date('Y') . '-' . str_pad($application->id, 4, '0', STR_PAD_LEFT);
        
        // 3. Photo Logic: Kunin ang path ng uploaded ID picture
        // Mas pinalakas na logic: Check both array key and object property access style
        $photoPath = null;
        if (isset($application->uploaded_files['id_picture'])) {
            $photoPath = $application->uploaded_files['id_picture'];
        } elseif (isset($application->uploaded_files->id_picture)) {
            $photoPath = $application->uploaded_files->id_picture;
        }

        // 4. Save to Students Table
        $student = Student::firstOrCreate(
            ['nas_student_id' => $generatedStudentId],
            [
                // Personal Info
                'lrn' => $application->lrn,
                'last_name' => $application->last_name,
                'first_name' => $application->first_name,
                'middle_name' => $application->middle_name,
                'sex' => $application->gender,
                'birthdate' => $application->date_of_birth,
                'age' => $application->age,
                'birthplace' => $application->birthplace,
                'religion' => $application->religion,
                'email_address' => $application->email_address,
                
                // 👇 FIXED: Changed 'photo' to 'id_picture' to match database column
                'id_picture' => $photoPath, 

                // Address
                'region' => $application->region,
                'province' => $application->province,
                'municipality_city' => $application->municipality_city,
                'barangay' => $application->barangay,
                'street_address' => $application->street_address,
                'zip_code' => $application->zip_code,

                // Academic & Enrollment
                'section_id' => $request->section_id,
                'grade_level' => $application->grade_level_applied,
                'status' => 'Enrolled',
                'enrollment_date' => Carbon::now(),
                
                // Guardian
                'guardian_name' => $application->guardian_name,
                'guardian_relationship' => $application->guardian_relationship,
                'guardian_contact' => $application->guardian_contact,
                'guardian_email' => $application->guardian_email,
                
                // Special Categories
                'is_ip' => $application->is_ip,
                'is_pwd' => $application->is_pwd,
                'is_4ps' => $application->is_4ps,
            ]
        );

        // 5. Create or Update User Account (Login Credentials)
        $user = User::where('email', $student->email_address)
                    ->orWhere('id', $application->user_id)
                    ->first();

        $msg = '';

        if ($user) {
            // A. EXISTING ACCOUNT (Galing Applicant Portal)
            // I-update lang ang role at i-link ang student_id
            $user->role = 'student';
            $user->student_id = $student->id;
            $user->save();
            
            // Message formatting
            $msg = "
                <div class='font-bold text-lg'>✅ Enrollment Successful!</div>
                <div class='mt-2'>
                    Student: <strong>{$student->first_name} {$student->last_name}</strong><br>
                    Status: <strong>Existing Account Linked</strong><br>
                    <span class='text-gray-600 text-sm'>Student can login using their existing email and password.</span>
                </div>
            ";
        } else {
            // B. NEW ACCOUNT (Walk-in / Walang Portal Account)
            // Generate Random Password (e.g., NAS-2025)
            $tempPassword = 'NAS-' . date('Y'); 
            
            User::create([
                'name' => $student->first_name . ' ' . $student->last_name,
                'email' => $student->email_address,
                'password' => Hash::make($tempPassword),
                'role' => 'student',
                'student_id' => $student->id,
            ]);
            
            // Message na may Highlighted Password
            $msg = "
                <div class='font-bold text-lg'>✅ Enrollment Successful!</div>
                <div class='mt-2 border-t border-green-200 pt-2'>
                    <div>Student: <strong>{$student->first_name} {$student->last_name}</strong></div>
                    <div>Email: <strong>{$student->email_address}</strong></div>
                    <div class='mt-2 p-2 bg-white rounded border border-green-300 inline-block'>
                        Temporary Password: <span class='text-red-600 font-extrabold text-lg'>{$tempPassword}</span>
                    </div>
                    <div class='text-xs mt-1 text-gray-600'>*Please provide these credentials to the student immediately.</div>
                </div>
            ";
        }

        // 6. Update Application Status to 'Enrolled'
        $application->status = 'Enrolled';
        $application->save();

        // 7. Redirect to Student Directory
        return redirect()->route('students.index')->with('success', $msg);
    }
}