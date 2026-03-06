<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Section;
use App\Models\Team;
use App\Models\User;
use App\Models\ActivityLog; 
use App\Models\Applicant; 
use App\Models\Staff;
use App\Models\EnrollmentDetail; 
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Notification;
use App\Notifications\RecordFinalizedNotification;

use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    // Cloudinary configuration removed to favor local storage via FILESYSTEM_DISK

    /**
     * Builds the student query combining all search and filter params.
     */
    private function buildStudentQuery(Request $request)
    {
        $query = Student::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%")
                  ->orWhere('nas_student_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sport')) {
            $sport = $request->sport;
            $searchSport = explode(' ', $sport)[0];
            $searchSport = rtrim($searchSport, 's'); 

            $applicantLrns = Applicant::where('sport', 'LIKE', "%{$searchSport}%")->pluck('lrn')->toArray();
            $detailLrns = EnrollmentDetail::where('sport', 'LIKE', "%{$searchSport}%")->pluck('lrn')->toArray();

            $query->where(function($q) use ($searchSport, $applicantLrns, $detailLrns) {
                $q->whereHas('team', function($t) use ($searchSport) {
                      $t->where('sport', 'LIKE', "%{$searchSport}%")
                        ->orWhere('team_name', 'LIKE', "%{$searchSport}%");
                  })
                  ->orWhereIn('lrn', $applicantLrns)
                  ->orWhereIn('lrn', $detailLrns);
            });
        }

        return $query;
    }

    /**
     * Display the Student Directory with filters.
     */
    public function index(Request $request): View
    {
        $sections = Section::orderBy('grade_level')->orderBy('section_name')->get();
        
        $query = $this->buildStudentQuery($request)->with(['section', 'team']);

        $students = $query->orderBy('last_name')->paginate(15); 
        return view('students.index', compact('students', 'sections'));
    }

    /**
     * Bulk update student statuses.
     */
    public function bulkUpdateStatus(Request $request): RedirectResponse
    {
        $request->validate([
            'bulk_status' => 'required|in:New,Continuing,Transfer out,Graduate,Enrolled',
        ]);
        
        if (!$request->boolean('select_all_matching')) {
            $request->validate([
                'student_ids' => 'required|array',
                'student_ids.*' => 'exists:students,id',
            ]);
            $query = Student::whereIn('id', $request->student_ids);
        } else {
            $query = $this->buildStudentQuery($request);
        }

        $status = $request->bulk_status;
        
        // Prevent changing status of locked records
        $query->where('is_locked', false);
        
        $count = $query->count();
        $query->update(['status' => $status]);

        $user = Auth::user();
        if ($user) {
            $role = ucfirst($user->role);
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'Bulk Update Status',
                'description' => "<strong>{$role}</strong> {$user->name} updated the status of <strong>{$count} student(s)</strong> to <strong>{$status}</strong>.",
            ]);
        }

        return back()->with('success', "Successfully updated the status of {$count} student(s) to {$status}.");
    }

    /**
     * Bulk finalize (lock) selected student records.
     */
    public function bulkFinalize(Request $request): RedirectResponse
    {
        if (!$request->boolean('select_all_matching')) {
            $request->validate([
                'student_ids' => 'required|array',
                'student_ids.*' => 'exists:students,id',
            ]);
            $query = Student::whereIn('id', $request->student_ids);
        } else {
            $query = $this->buildStudentQuery($request);
        }

        // Prevent re-finalizing locked records
        $query->where('is_locked', false);
        
        $count = $query->count();
        $query->update(['is_locked' => true]);

        $user = Auth::user();
        if ($user) {
            $role = ucfirst($user->role);
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'Bulk Finalized Records',
                'description' => "<strong>{$role}</strong> {$user->name} finalized <strong>{$count} student record(s)</strong>.",
            ]);

            // Notify the OPPOSITE role
            if ($user->role === 'admin') {
                $recipients = User::where('role', 'registrar')->get();
            } else {
                $recipients = User::where('role', 'admin')->get();
            }
            $message = "{$role} {$user->name} finalized {$count} student record(s).";
            Notification::send($recipients, new RecordFinalizedNotification($message, null, 'bulk_finalized'));
        }

        return back()->with('success', "🔒 Successfully finalized {$count} student record(s).");
    }

    /**
     * Bulk unfinalize (unlock) selected student records. Admin only.
     */
    public function bulkUnfinalize(Request $request): RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Only PICT Support (Admin) can unfinalize records.');
        }

        if (!$request->boolean('select_all_matching')) {
            $request->validate([
                'student_ids' => 'required|array',
                'student_ids.*' => 'exists:students,id',
            ]);
            $query = Student::whereIn('id', $request->student_ids);
        } else {
            $query = $this->buildStudentQuery($request);
        }

        // Only unfinalize actually locked records
        $query->where('is_locked', true);
        
        $count = $query->count();
        $query->update(['is_locked' => false]);

        $user = Auth::user();
        $role = ucfirst($user->role);
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Bulk Unfinalized Records',
            'description' => "<strong>{$role}</strong> {$user->name} unfinalized <strong>{$count} student record(s)</strong>.",
        ]);

        // Notify registrars when admin unfinalizes a record
        $registrars = User::where('role', 'registrar')->get();
        $message = "{$role} {$user->name} unfinalized {$count} student record(s).";
        Notification::send($registrars, new RecordFinalizedNotification($message, null, 'bulk_unfinalized'));

        return back()->with('success', "🔓 Successfully unfinalized {$count} student record(s).");
    }

    /**
     * Show student profile.
     */
    public function show(Request $request, Student $student) 
    { 
        $student->load(['section.adviser', 'team']);
        $queryParams = $request->query();
        return view('students.show', compact('student', 'queryParams')); 
    }

    /**
     * Edit student information.
     */
    public function edit(Request $request, Student $student): View
    {
        $sections = Section::orderBy('grade_level')->orderBy('section_name')->get();
        $teams = Team::orderBy('team_name')->get();
        $queryParams = $request->query();
        return view('students.edit', compact('student', 'sections', 'teams', 'queryParams'));
    }

    /**
     * Update student record.
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        // Lock Guard: Prevent editing locked records
        if ($student->is_locked) {
            return redirect()->route('students.show', $student)->with('error', 'This record is locked and cannot be edited.');
        }

        $validatedData = $request->validate([
            'nas_student_id' => ['required', 'string', 'max:255', Rule::unique('students')->ignore($student->id)],
            'lrn' => ['required', 'string', 'max:255', Rule::unique('students')->ignore($student->id)],
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'sex' => 'required|in:Male,Female',
            'birthdate' => 'required|date',
            'age' => 'nullable|integer',
            'birthplace' => 'required|string|max:255',
            'religion' => 'nullable|string|max:255',
            'entry_year' => 'nullable|digits:4',
            'grade_level' => 'required|string',
            'section_id' => 'nullable|exists:sections,id',
            'team_id' => 'nullable|exists:teams,id',
            'sport' => 'nullable|string|max:255',
            'sport_specification' => 'nullable|string|max:255',
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
            'school_name' => 'nullable|string|max:255',
            'school_id' => 'nullable|string|max:255',
            'school_type' => 'nullable|string|max:255',
            'last_grade_level' => 'nullable|string|max:255',
            'last_school_year' => 'nullable|string|max:255',
            'school_address' => 'nullable|string|max:255',
            'enrollment_date' => 'nullable|date',
            'lis_status' => 'nullable|string',
            'enrollment_remarks' => 'nullable|string',
        ]);

        $validatedData['is_ip'] = $request->has('is_ip');
        $validatedData['is_pwd'] = $request->has('is_pwd');
        $validatedData['is_4ps'] = $request->has('is_4ps');

        if(empty($validatedData['sport_specification'])) {
            $validatedData['sport_specification'] = 'None';
        }

        $photoUrl = null;
        if ($request->hasFile('photo')) {
            try {
                $photoUrl = $request->file('photo')->store('id_pictures', 'public');
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

        $user = Auth::user();
        $role = ucfirst($user->role);
        
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Update Student',
            'description' => "<strong>{$role}</strong> {$user->name} updated info of <strong>{$student->last_name}, {$student->first_name}</strong>.",
        ]);

        return redirect()->route('students.index')->with('success', 'Student record updated successfully.');
    }

    /**
     * Delete student record and associated user.
     */
    public function destroy(Student $student): RedirectResponse
    {
        // Lock Guard
        if ($student->is_locked) {
            return redirect()->route('students.show', $student)->with('error', 'This record is locked and cannot be deleted.');
        }
        if($student->user) $student->user->delete();
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student record deleted.');
    }

    /**
     * Toggle lock status of a student record.
     */
    public function toggleLock(Student $student): RedirectResponse
    {
        // Only admins can unfinalize a record
        if ($student->is_locked && Auth::user()->role !== 'admin') {
            return redirect()->route('students.show', $student)
                ->with('error', 'Only PICT Support (Admin) can unfinalize a record.');
        }

        $student->is_locked = !$student->is_locked;
        $student->save();

        $user = Auth::user();
        $role = ucfirst($user->role);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $student->is_locked ? 'Finalized Record' : 'Unfinalized Record',
            'description' => "<strong>{$role}</strong> {$user->name} " . ($student->is_locked ? 'finalized' : 'unfinalized') . " record of <strong>{$student->last_name}, {$student->first_name}</strong>.",
        ]);

        // Notify the correct role based on the action
        $actionText = $student->is_locked ? 'finalized' : 'unfinalized';
        $message = "{$role} {$user->name} {$actionText} the record of {$student->first_name} {$student->last_name}.";

        if ($student->is_locked) {
            // Finalized: notify the OPPOSITE role
            if ($user->role === 'admin') {
                $recipients = User::where('role', 'registrar')->get();
            } else {
                $recipients = User::where('role', 'admin')->get();
            }
        } else {
            // If Unfinalized (must be by Admin), notify Registrar
            $recipients = User::where('role', 'registrar')->get();
        }

        Notification::send($recipients, new RecordFinalizedNotification($message, $student->id, $student->is_locked ? 'finalized' : 'unfinalized'));

        return redirect()->route('students.show', $student)
            ->with('success', $student->is_locked ? '🔒 Record has been finalized and locked.' : '🔓 Record has been unfinalized and unlocked.');
    }

    /**
     * Form for bulk uploading photos matched by Student ID.
     */
    public function bulkUploadForm(): View
    {
        return view('students.bulk-upload');
    }

    /**
     * Process bulk photo upload.
     */
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

        foreach ($files as $file) {
            $filenameWithExt = $file->getClientOriginalName();
            $studentId = pathinfo($filenameWithExt, PATHINFO_FILENAME); 

            $student = Student::where('nas_student_id', $studentId)->first();

            if ($student) {
                try {
                    $photoUrl = $file->storeAs('id_pictures', $filenameWithExt, 'public');

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

    /**
     * List advisory class for teachers.
     */
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

    /**
     * Update grade/promotion for advisory class.
     */
    public function updateAdvisoryGrade(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update($request->validate([
            'general_average' => 'nullable|numeric|min:60|max:100',
            'promotion_status' => 'nullable|string',
        ]));
        return back()->with('success', 'Student grade updated.');
    }
}