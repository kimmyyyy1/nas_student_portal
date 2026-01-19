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
use Illuminate\Support\Facades\Validator; 

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class StudentController extends Controller
{
    private function configureCloudinary()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => 'dqkzofruk', 
                'api_key'    => '452544782214523', 
                'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo',
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    }

    // 👇 UPDATED INDEX: With Advanced Filtering Logic
    public function index(Request $request): View
    {
        // 1. Kunin ang Sections para sa Filter Dropdown
        $sections = Section::orderBy('grade_level')->orderBy('section_name')->get();

        // 2. Start Query
        $query = Student::with(['section', 'team']);

        // 3. Text Search (Name, LRN, ID)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%")
                  ->orWhere('nas_student_id', 'like', "%{$search}%");
            });
        }

        // 4. Dropdown Filters
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 5. Execute & Paginate
        $students = $query->orderBy('last_name')->paginate(15); 
        
        // Ipasa ang $sections sa view para gumana ang dropdown filter
        return view('students.index', compact('students', 'sections'));
    }

    public function create(): View
    {
        // Ipasa lahat ng section para sa JavaScript filtering
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
        
        // MANUAL CLOUDINARY UPLOAD
        $photoUrl = null;
        if ($request->hasFile('photo')) {
            try {
                $this->configureCloudinary(); 
                $uploadApi = new UploadApi();
                $result = $uploadApi->upload($request->file('photo')->getRealPath(), [
                    'folder' => 'students/photos'
                ]);
                $photoUrl = $result['secure_url']; 
            } catch (\Exception $e) { }
        }

        $studentData = collect($validatedData)->except(['photo'])->toArray();
        if ($photoUrl) {
            $studentData['id_picture'] = $photoUrl;
        }
        
        $student = Student::create($studentData);

        $tempPassword = 'NAS-' . date('Y') . '-' . Str::upper(Str::random(6));
        User::create([
            'name' => $student->first_name . ' ' . $student->last_name,
            'email' => $student->email_address,
            'password' => Hash::make($tempPassword),
            'role' => 'student',
            'student_id' => $student->id,
        ]);

        // LOGGING
        $user = Auth::user();
        $role = ucfirst($user->role);
        
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Registration',
            'description' => "<strong>{$role}</strong> {$user->name} registered new student: <strong>{$student->last_name}, {$student->first_name}</strong>.",
        ]);

        if ($student->email_address) {
            try {
                Mail::to($student->email_address)->send(new AdmissionAccepted($student, $tempPassword));
            } catch (\Exception $e) {}
        }

        return redirect()->route('students.index')->with('success', "Student record created! Password: {$tempPassword}");
    }

    public function edit(Request $request, Student $student): View
    {
        $sections = Section::orderBy('grade_level')->orderBy('section_name')->get();
        $teams = Team::orderBy('team_name')->get();
        
        $queryParams = $request->query();

        return view('students.edit', compact('student', 'sections', 'teams', 'queryParams'));
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

        // MANUAL CLOUDINARY UPLOAD
        $photoUrl = null;
        if ($request->hasFile('photo')) {
            try {
                $this->configureCloudinary(); 
                $uploadApi = new UploadApi();
                $result = $uploadApi->upload($request->file('photo')->getRealPath(), [
                    'folder' => 'students/photos'
                ]);
                $photoUrl = $result['secure_url'];
            } catch (\Exception $e) { }
        }

        $studentData = collect($validatedData)->except(['photo'])->toArray();
        if ($photoUrl) {
            $studentData['id_picture'] = $photoUrl;
        }

        $student->update($studentData);

        if($student->user) {
            $student->user->update(['email' => $student->email_address]);
        }

        // LOGGING
        $user = Auth::user();
        $role = ucfirst($user->role);
        
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Update Student',
            'description' => "<strong>{$role}</strong> {$user->name} updated info of <strong>{$student->last_name}, {$student->first_name}</strong>.",
        ]);

        return redirect()->route('students.index')->with('success', 'Student record updated successfully.');
    }
    
    public function destroy(Student $student): RedirectResponse
    {
        if($student->user) $student->user->delete();
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student record deleted.');
    }

    public function show(Request $request, Student $student) 
    { 
        $student->load(['section.adviser', 'team']);
        $queryParams = $request->query();
        return view('students.show', compact('student', 'queryParams')); 
    }

    // ==========================================
    // BULK UPLOAD FEATURE
    // ==========================================

    public function bulkUploadForm(): View
    {
        return view('students.bulk-upload');
    }

    public function processBulkUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photos' => 'required',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:4500', 
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
            }
            return back()->with('error', $validator->errors()->first());
        }

        $files = $request->file('photos');
        $successCount = 0;
        $failCount = 0;
        $errors = [];

        $this->configureCloudinary();
        $uploadApi = new UploadApi();

        foreach ($files as $file) {
            $filenameWithExt = $file->getClientOriginalName();
            $studentId = pathinfo($filenameWithExt, PATHINFO_FILENAME); 

            $student = Student::where('nas_student_id', $studentId)->first();

            if ($student) {
                try {
                    $result = $uploadApi->upload($file->getRealPath(), [
                        'folder' => 'students/photos'
                    ]);
                    $photoUrl = $result['secure_url'];

                    $student->update(['id_picture' => $photoUrl]);
                    $successCount++;
                } catch (\Exception $e) {
                    $failCount++;
                    $errors[] = "Error ID $studentId: " . $e->getMessage();
                }
            } else {
                $failCount++;
                $errors[] = "ID not found: $studentId";
            }
        }

        if ($request->wantsJson()) {
            if ($failCount > 0) {
                return response()->json([
                    'status' => 'partial_error',
                    'message' => $errors[0] 
                ], 422);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Uploaded successfully'
            ]);
        }

        $message = "Process Complete. Success: $successCount. Failed: $failCount.";
        
        if ($failCount > 0) {
            return redirect()->route('students.index')
                             ->with('warning', $message . " Some photos were not matched.")
                             ->withErrors($errors);
        }

        return redirect()->route('students.index')->with('success', $message);
    }

    public function myAdvisoryClass()
    {
        $user = Auth::user();
        $staff = Staff::where('email', $user->email)->first();
        if (!$staff) return redirect()->route('dashboard')->with('error', 'No staff record found.');

        $fullName = $staff->first_name . ' ' . $staff->last_name;
        $section = Section::where('adviser_name', 'LIKE', "%$fullName%")->first();
        if (!$section) return redirect()->route('dashboard')->with('error', 'No advisory class.');

        $students = Student::where('section_id', $section->id)->orderBy('sex', 'desc')->orderBy('last_name')->get();
        return view('teacher.advisory-list', compact('section', 'students'));
    }

    public function updateAdvisoryGrade(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update($request->validate([
            'general_average' => 'nullable|numeric|min:60|max:100',
            'promotion_status' => 'nullable|string',
        ]));
        return back()->with('success', 'Student grade updated.');
    }

    public function enrollmentList()
    {
        $qualifiedApplicants = EnrollmentApplication::where('status', 'Qualified')->orderBy('last_name')->get();
        return view('students.enrollment', compact('qualifiedApplicants'));
    }

    public function enrollmentManager(): View
    {
        $pendingEnrollees = EnrollmentApplication::where('status', 'Qualified')->where('enrollment_status', 'Submitted')->get();
        $students = Student::whereIn('status', ['Enrolled', 'New', 'Continuing'])->orderBy('last_name')->get();
        return view('students.enrollment-manager', compact('students', 'pendingEnrollees'));
    }

    public function updateEnrollment(Request $request, $id): RedirectResponse
    {
        $student = Student::findOrFail($id);
        $student->update($request->validate([
            'status' => 'required',
            'enrollment_date' => 'nullable|date',
            'lis_status' => 'nullable',
            'enrollment_remarks' => 'nullable',
        ]));
        return back()->with('success', 'Enrollment details updated.');
    }

    public function showEnrollmentProcess($id): View
    {
        $application = EnrollmentApplication::findOrFail($id);
        $sections = Section::where('grade_level', $application->grade_level_applied)->orderBy('section_name')->get();
        return view('students.process-enrollment', compact('application', 'sections'));
    }
}