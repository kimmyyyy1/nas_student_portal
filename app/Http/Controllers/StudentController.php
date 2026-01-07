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

class StudentController extends Controller
{
    /**
     * Display Student Directory (Admin Masterlist).
     * Route: /students
     */
    public function index(Request $request): View
    {
        // Eager load 'media' para mabilis mag-load ang pictures sa listahan
        $query = Student::with(['section', 'team', 'media']);

        // Search Logic
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%")
                  ->orWhere('nas_student_id', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('last_name')->paginate(15); 

        return view('students.index', compact('students'));
    }

    // ==========================================
    // ADMIN CRUD OPERATIONS (Create, Store, Edit, Update, Destroy)
    // ==========================================

    public function create(): View
    {
        $sections = Section::orderBy('grade_level')->orderBy('section_name')->get();
        $teams = Team::orderBy('team_name')->get();
        return view('students.create', compact('sections', 'teams'));
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. VALIDATION
        $validatedData = $request->validate([
            'nas_student_id' => 'required|string|unique:students|max:255',
            'lrn' => 'required|string|unique:students|max:20',
            // Photo: Optional, Image file only, Max 5MB
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

        // Checkbox handling
        $validatedData['is_ip'] = $request->has('is_ip');
        $validatedData['is_pwd'] = $request->has('is_pwd');
        $validatedData['is_4ps'] = $request->has('is_4ps');
        
        // 2. CREATE STUDENT RECORD
        // Gumamit ng except(['photo']) para hindi isama ang image file sa database insert command.
        // Ang database table ay walang column na 'photo' na kayang humawak ng file object.
        $studentData = collect($validatedData)->except(['photo'])->toArray();
        
        $student = Student::create($studentData);

        // 3. SPATIE MEDIA LIBRARY: Save Photo
        if ($request->hasFile('photo')) {
            $student->addMediaFromRequest('photo')
                    ->toMediaCollection('avatar'); 
        }

        // 4. AUTO-CREATE USER ACCOUNT
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
        // 1. VALIDATION (Unique fields ignore current ID)
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
            'status' => 'required|in:New,Continuing,Transfer out,Graduate,Enrolled',
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

        // 2. UPDATE TEXT DATA
        $studentData = collect($validatedData)->except(['photo'])->toArray();
        $student->update($studentData);

        // 3. SPATIE MEDIA LIBRARY: Update Photo
        if ($request->hasFile('photo')) {
            // Burahin ang lumang picture sa collection na 'avatar'
            $student->clearMediaCollection('avatar'); 
            // I-upload ang bagong picture
            $student->addMediaFromRequest('photo')->toMediaCollection('avatar');
        }

        // Update User Email if changed
        if($student->user) {
            $student->user->update(['email' => $student->email_address]);
        }

        return redirect()->route('students.index')->with('success', 'Student record updated successfully.');
    }
    
    public function destroy(Student $student): RedirectResponse
    {
        if($student->user) $student->user->delete();
        
        // Spatie automatically deletes associated media files when model is deleted.
        $student->delete();
        
        return redirect()->route('students.index')->with('success', 'Student record deleted.');
    }

    public function show(Student $student) { 
        // Reuse edit view or create a separate show view
        return view('students.edit', compact('student')); 
    }

    // ==========================================
    // TEACHER MODULES
    // ==========================================

    public function myAdvisoryClass()
    {
        $user = Auth::user();
        $staff = Staff::where('email', $user->email)->first();

        if (!$staff) {
            return redirect()->route('dashboard')->with('error', 'No staff record found linked to your account.');
        }

        $fullName = $staff->first_name . ' ' . $staff->last_name;
        $section = Section::where('adviser_name', 'LIKE', "%$fullName%")->first();

        if (!$section) {
            return redirect()->route('dashboard')->with('error', 'You do not have an assigned advisory class.');
        }

        $students = Student::with('media')
            ->where('section_id', $section->id)
            ->orderBy('sex', 'desc') 
            ->orderBy('last_name')
            ->get();

        return view('teacher.advisory-list', compact('section', 'students'));
    }

    public function updateAdvisoryGrade(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $validated = $request->validate([
            'general_average' => 'nullable|numeric|min:60|max:100',
            'promotion_status' => 'nullable|string|in:Promoted,Conditional,Retained,Promoted with Honors,Promoted with High Honors,Promoted with Highest Honors',
        ]);

        $student->update($validated);
        return back()->with('success', 'Student grade and status updated successfully.');
    }

    // ==========================================
    // REGISTRAR MODULES (Enrollment)
    // ==========================================

    public function enrollmentList()
    {
        $qualifiedApplicants = EnrollmentApplication::where('status', 'Qualified')
                                ->orderBy('last_name', 'asc')
                                ->get();
        return view('students.enrollment', compact('qualifiedApplicants'));
    }

    public function enrollmentManager(): View
    {
        $pendingEnrollees = EnrollmentApplication::where('status', 'Qualified')
                            ->where('enrollment_status', 'Submitted')
                            ->orderBy('updated_at', 'desc')
                            ->get();

        $students = Student::with('media')
            ->whereIn('status', ['Enrolled', 'New', 'Continuing'])
            ->orderBy('last_name')
            ->get();

        return view('students.enrollment-manager', compact('students', 'pendingEnrollees'));
    }

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

    public function showEnrollmentProcess($id): View
    {
        $application = EnrollmentApplication::findOrFail($id);
        $sections = Section::where('grade_level', $application->grade_level_applied)->orderBy('section_name')->get();
        return view('students.process-enrollment', compact('application', 'sections'));
    }
}