<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Section;
use App\Models\Team;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\EnrollmentApplication;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\AdmissionAccepted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display Student Directory (Admin Masterlist).
     * Route: /students
     */
    public function index(Request $request): View
    {
        $query = Student::with('section', 'team');

        // Optional: Search Logic
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%")
                  ->orWhere('student_id_number', 'like', "%{$search}%");
            });
        }

        // Use pagination instead of get() for better performance
        $students = $query->orderBy('last_name')->paginate(15); 

        return view('students.index', compact('students'));
    }

    // ==========================================
    // TEACHER MODULES
    // ==========================================

    /**
     * TEACHER VIEW: My Advisory Class (LIS Style)
     */
    public function myAdvisoryClass()
    {
        $user = Auth::user();
        
        // 1. Find Staff Record
        $staff = Staff::where('email', $user->email)->first();

        if (!$staff) {
            return redirect()->route('dashboard')->with('error', 'No staff record found linked to your account.');
        }

        // 2. Find Advisory Section
        $fullName = $staff->first_name . ' ' . $staff->last_name;
        $section = Section::where('adviser_name', 'LIKE', "%$fullName%")->first();

        if (!$section) {
            return redirect()->route('dashboard')->with('error', 'You do not have an assigned advisory class.');
        }

        // 3. Get Students (Sorted by Sex then Lastname)
        $students = Student::where('section_id', $section->id)
            ->orderBy('sex', 'desc') 
            ->orderBy('last_name')
            ->get();

        return view('teacher.advisory-list', compact('section', 'students'));
    }

    /**
     * TEACHER ACTION: Update Grade & Status (Modal)
     */
    public function updateAdvisoryGrade(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'general_average' => 'nullable|numeric|min:60|max:100',
            'promotion_status' => 'nullable|string|in:Promoted,Conditional,Retained,Promoted with Honors,Promoted with High Honors,Promoted with Highest Honors',
        ]);

        $student->update([
            'general_average' => $validated['general_average'],
            'promotion_status' => $validated['promotion_status'],
        ]);

        return back()->with('success', 'Student grade and status updated successfully.');
    }

    // ==========================================
    // REGISTRAR MODULES (Enrollment)
    // ==========================================

    /**
     * [FIXED] Enrollment List Page
     * Shows Qualified Applicants ready for Official Enrollment
     * Route: /students/enrollment
     */
    public function enrollmentList()
    {
        // Kunin ang mga applicants na 'Qualified' pero HINDI pa 'Enrolled'
        $qualifiedApplicants = EnrollmentApplication::where('status', 'Qualified')
                                ->orderBy('last_name', 'asc')
                                ->get();

        return view('students.enrollment', compact('qualifiedApplicants'));
    }

    /**
     * ENROLLMENT SYSTEM MANAGEMENT VIEW (General Manager)
     */
    public function enrollmentManager(): View
    {
        // 1. INCOMING ENROLLEES (Table 1)
        $pendingEnrollees = EnrollmentApplication::where('status', 'Qualified')
                            ->where('enrollment_status', 'Submitted')
                            ->orderBy('updated_at', 'desc')
                            ->get();

        // 2. OFFICIAL STUDENTS (Table 2)
        $students = Student::whereIn('status', ['Enrolled', 'New', 'Continuing'])
            ->orderBy('last_name')
            ->get();

        return view('students.enrollment-manager', compact('students', 'pendingEnrollees'));
    }

    /**
     * REGISTRAR: Update Enrollment Fields (LIS, Date, Remarks)
     */
    public function updateEnrollment(Request $request, $id): RedirectResponse
    {
        $student = Student::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:New,Continuing,Transfer out,Graduate,Enrolled',
            'enrollment_date' => 'nullable|date',
            'lis_status' => 'nullable|string|in:Enrolled,Pending,For Follow-up',
            'enrollment_remarks' => 'nullable|string|max:500',
        ]);

        $student->update($validated);

        return back()->with('success', 'Enrollment details updated successfully.');
    }

    /**
     * REGISTRAR: Show Final Enrollment Process Page
     */
    public function showEnrollmentProcess($id): View
    {
        $application = EnrollmentApplication::findOrFail($id);
        $sections = Section::where('grade_level', $application->grade_level_applied)->orderBy('section_name')->get();
        return view('students.process-enrollment', compact('application', 'sections'));
    }

    // ==========================================
    // ADMIN CRUD OPERATIONS (Directory)
    // ==========================================

    public function create(): View
    {
        $sections = Section::orderBy('grade_level')->orderBy('section_name')->get();
        $teams = Team::orderBy('team_name')->get();
        return view('students.create', compact('sections', 'teams'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'nas_student_id' => 'required|string|unique:students|max:255',
            'lrn' => 'required|string|unique:students|max:20',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            
            'sex' => 'required|in:Male,Female',
            'birthdate' => 'required|date',
            'age' => 'nullable|integer',
            'birthplace' => 'required|string|max:255',
            'religion' => 'nullable|string|max:255',

            'entry_year' => 'nullable|digits:4',
            'grade_level' => 'required|string',
            'section_id' => 'nullable|exists:sections,id',
            'team_id' => 'nullable|exists:teams,id',
            'status' => 'required|in:New,Continuing,Transfer out,Graduate',
            
            'region' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'municipality_city' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'street_address' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'contact_number' => 'nullable|string|max:20',
            'email_address' => 'required|email|max:255|unique:students,email_address',

            'guardian_name' => 'required|string|max:255',
            'guardian_relationship' => 'required|string|max:255',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_contact' => 'required|string|max:20',
            'guardian_address' => 'nullable|string|max:255',

            'enrollment_date' => 'nullable|date',
            'lis_status' => 'nullable|string',
            'enrollment_remarks' => 'nullable|string',
        ]);

        $validatedData['is_ip'] = $request->has('is_ip');
        $validatedData['is_pwd'] = $request->has('is_pwd');
        $validatedData['is_4ps'] = $request->has('is_4ps');
        
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('uploads/id_pictures', 'public');
            $validatedData['photo'] = $path;
        }

        $student = Student::create($validatedData);

        // Auto-Create User Account
        $tempPassword = 'NAS-' . date('Y') . '-' . Str::upper(Str::random(6));
        User::create([
            'name' => $student->first_name . ' ' . $student->last_name,
            'email' => $student->email_address,
            'password' => Hash::make($tempPassword),
            'role' => 'student',
            'student_id' => $student->id,
        ]);

        ActivityLog::create([
            'description' => "Student manually added: <strong>{$student->last_name}</strong>.",
            'model_type' => 'Student',
            'model_id' => $student->id
        ]);

        // Send Email if applicable
        if ($student->email_address) {
            try {
                Mail::to($student->email_address)->send(new AdmissionAccepted($student, $tempPassword));
            } catch (\Exception $e) {}
        }

        return redirect()->route('students.index')->with('success', "Student record created! Password: {$tempPassword}");
    }

    public function edit(Student $student): View
    {
        $sections = Section::orderBy('grade_level')->orderBy('section_name')->get();
        $teams = Team::orderBy('team_name')->get();
        return view('students.edit', compact('student', 'sections', 'teams'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validatedData = $request->validate([
            'nas_student_id' => ['required', 'string', 'max:255', Rule::unique('students')->ignore($student->id)],
            'lrn' => ['required', 'string', 'max:255', Rule::unique('students')->ignore($student->id)],
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'sex' => 'required|in:Male,Female',
            'birthdate' => 'required|date',
            'age' => 'nullable|integer',
            'birthplace' => 'required|string|max:255',
            'religion' => 'nullable|string|max:255',
            'entry_year' => 'nullable|digits:4',
            'grade_level' => 'required|string',
            'section_id' => 'nullable|exists:sections,id',
            'team_id' => 'nullable|exists:teams,id',
            'status' => 'required|in:New,Continuing,Transfer out,Graduate',
            'promotion_status' => 'nullable|in:Promoted,Conditional,Retained,Promoted with Honors,Promoted with High Honors,Promoted with Highest Honors',
            'region' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'municipality_city' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'street_address' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'contact_number' => 'nullable|string|max:20',
            'email_address' => ['required', 'email', 'max:255', Rule::unique('students')->ignore($student->id)],
            'guardian_name' => 'required|string|max:255',
            'guardian_relationship' => 'required|string|max:255',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_contact' => 'required|string|max:20',
            'guardian_address' => 'nullable|string|max:255',
            'enrollment_date' => 'nullable|date',
            'lis_status' => 'nullable|string',
            'enrollment_remarks' => 'nullable|string',
        ]);

        $validatedData['is_ip'] = $request->has('is_ip');
        $validatedData['is_pwd'] = $request->has('is_pwd');
        $validatedData['is_4ps'] = $request->has('is_4ps');

        if ($request->hasFile('photo')) {
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }
            $path = $request->file('photo')->store('uploads/id_pictures', 'public');
            $validatedData['photo'] = $path;
        } else {
             unset($validatedData['photo']);
        }

        $student->update($validatedData);
        
        if($student->user) {
            $student->user->update(['email' => $student->email_address]);
        }

        return redirect()->route('students.index')->with('success', 'Student record updated successfully.');
    }
    
    public function destroy(Student $student): RedirectResponse
    {
        if($student->user) $student->user->delete();
        if($student->photo) Storage::disk('public')->delete($student->photo);
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student record deleted.');
    }

    public function show(Student $student) { 
        return view('students.edit', compact('student'));
    }
}