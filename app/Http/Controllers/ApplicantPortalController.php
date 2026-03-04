<?php

namespace App\Http\Controllers;

use App\Models\Applicant; 
use App\Models\Team;
use App\Models\User; 
use App\Models\EnrollmentDetail; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewApplicantNotification;
use App\Notifications\EnrollmentSubmittedNotification;

class ApplicantPortalController extends Controller
{
    public function __construct()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => 'dqkzofruk', 
                'api_key'    => '452544782214523', 
                'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo'
            ],
            'url' => ['secure' => true]
        ]);
    }

    // ==========================================
    // PHASE 1: DASHBOARD & REGISTRATION
    // ==========================================

    public function index(): View|RedirectResponse
    {
        // Safety Check: Kung student na siya, ibato sa student portal para iwas loop
        if (Auth::user()->role === 'student') {
            return redirect()->route('student.dashboard');
        }

        $application = Applicant::where('user_id', Auth::id())->first();
        
        if ($application) {
            $application->displayStatus = $application->status;
        }
        
        return view('applicant.dashboard', compact('application'));
    }

    public function create(): View|RedirectResponse
    {
        $existing = Applicant::where('user_id', Auth::id())->first();
        if ($existing) return redirect()->route('applicant.dashboard');

        $teams = Team::orderBy('team_name')->get();
        
        return view('applicant.create', compact('teams'));
    }

    public function store(Request $request): RedirectResponse
    {
        set_time_limit(300);
        
        // 1. Validate Input
        $validated = $request->validate([
            'lrn' => 'required|string|size:12',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date',
            'age' => 'required|numeric',
            'gender' => 'required|string',
            'region' => 'required|string',
            'province' => 'required|string',
            'municipality_city' => 'required|string',
            'barangay' => 'required|string',
            'street_address' => 'nullable|string',
            'zip_code' => 'required|string',
            'learn_about_nas' => 'required|string',
            'referrer_name' => 'nullable|string',
            'attended_campaign' => 'required|string',
            'is_ip' => 'required|string',
            'ip_group_name' => 'nullable|string',
            'is_pwd' => 'required|string',
            'pwd_disability' => 'nullable|string',
            'is_4ps' => 'required|string',
            'sport' => 'required|string',
            'sport_specification' => 'nullable|string',
            'palaro_finisher' => 'required|string',
            'batang_pinoy_finisher' => 'required|string',
            'school_type' => 'required|string',
            'school_last_grade_level' => 'nullable|string',
            'school_last_year_completed' => 'nullable|string',
            'school_id' => 'nullable|string',
            'guardian_name' => 'required|string',
            'guardian_relationship' => 'required|string',
            'guardian_email' => 'required|email',
            'guardian_contact' => 'required|string|max:11',
        ]);

        // 2. Validate ID Picture
        if (!$request->hasFile('id_picture')) {
            return back()->withErrors(['id_picture' => 'The 2x2 ID Picture is required.'])->withInput();
        }

        // 3. Map Data & Save
        $data = $this->mapInputData($request); 
        $data['user_id'] = Auth::id();
        $data['status'] = 'Submitted for 1st Level Assessment'; 

        $applicant = Applicant::create($data);

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        $message = "New application from: {$applicant->first_name} {$applicant->last_name}";
        Notification::send($admins, new NewApplicantNotification($applicant, $message));

        // 4. Upload
        $this->handleInitialFileUploads($request, $applicant);

        return redirect()->route('applicant.dashboard')->with('success', 'Application submitted! Please wait for the initial assessment.');
    }

    // ==========================================
    // PHASE 2: REQUIREMENTS (UPLOAD & RE-UPLOAD)
    // ==========================================

    public function showRequirements(): View|RedirectResponse
    {
        $applicant = Applicant::where('user_id', Auth::id())->firstOrFail();

        $allowedStatuses = ['2nd Level', 'Returned', 'With Pending Requirements'];
        $canAccess = false;
        foreach ($allowedStatuses as $status) {
            if (str_contains(strtoupper($applicant->status), strtoupper($status))) {
                $canAccess = true;
                break;
            }
        }

        if (!$canAccess) {
            return redirect()->route('applicant.dashboard')
                ->with('error', 'You are not yet eligible to submit Phase 2 requirements.');
        }

        return view('applicant.requirements', compact('applicant'));
    }

    public function storeRequirements(Request $request): RedirectResponse
    {
        set_time_limit(600);
        $applicant = Applicant::where('user_id', Auth::id())->firstOrFail();

        $currentFiles = is_string($applicant->uploaded_files) ? json_decode($applicant->uploaded_files, true) : ($applicant->uploaded_files ?? []);
        $docStatuses = is_string($applicant->document_statuses) ? json_decode($applicant->document_statuses, true) : ($applicant->document_statuses ?? []);

        $allDocs = [
            'scholarship_form', 'student_profile', 'medical_clearance', 
            'psa_birth_cert', 'report_card', 'guardian_id',
            'kukkiwon_cert', 'ip_cert', 'pwd_id', '4ps_id'
        ];

        $rules = [];
        foreach ($allDocs as $doc) {
            $isRelevant = true;
            if ($doc == 'kukkiwon_cert' && $applicant->sport !== 'Taekwondo') $isRelevant = false;
            if ($doc == 'ip_cert' && !$applicant->is_ip) $isRelevant = false;
            if ($doc == 'pwd_id' && !$applicant->is_pwd) $isRelevant = false;
            if ($doc == '4ps_id' && !$applicant->is_4ps) $isRelevant = false;

            if ($isRelevant) {
                if (!isset($currentFiles[$doc]) || (isset($docStatuses[$doc]) && ($docStatuses[$doc] === 'declined' || $docStatuses[$doc] === 'Needs resubmission'))) {
                    $rules["files.$doc"] = 'required|file|mimes:pdf,jpg,jpeg,png,heic,heif|max:10240';
                } else {
                    $rules["files.$doc"] = 'nullable|file|mimes:pdf,jpg,jpeg,png,heic,heif|max:10240';
                }
            }
        }

        $request->validate($rules);
        
        $uploadedDocNames = []; 
        $isResubmission = false;

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $key => $file) {
                try {
                    $upload = (new UploadApi())->upload($file->getRealPath(), [
                        'folder' => 'nas_student_portal/phase2_requirements',
                        'resource_type' => 'auto'
                    ]);
                    $currentFiles[$key] = $upload['secure_url'];
                    
                    if (isset($docStatuses[$key]) && ($docStatuses[$key] === 'declined' || $docStatuses[$key] === 'Needs resubmission')) {
                        $isResubmission = true;
                    }
                    $docStatuses[$key] = 'pending'; 
                    $uploadedDocNames[] = $this->formatFileName($key);

                } catch (\Exception $e) {
                    return back()->withErrors(['files' => 'Error uploading ' . $key . '. Please try again.']);
                }
            }
        }

        $applicant->update([
            'uploaded_files' => $currentFiles,
            'document_statuses' => $docStatuses,
            'status' => 'Requirements Submitted & For Review'
        ]);

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        $message = "Requirements uploaded by: {$applicant->first_name} {$applicant->last_name}";
        Notification::send($admins, new NewApplicantNotification($applicant, $message));

        return redirect()->route('applicant.dashboard')->with('success', 'Requirements submitted successfully!');
    }

    // ==========================================
    // PHASE 3: OFFICIAL ENROLLMENT (FIXED REDIRECT)
    // ==========================================

    public function showEnrollmentForm(): View|RedirectResponse
    {
        $applicant = Applicant::where('user_id', Auth::id())->with('enrollmentDetail')->firstOrFail();

        if (!str_contains($applicant->status, 'Qualified') && !str_contains($applicant->status, 'Returned')) {
            return redirect()->route('applicant.dashboard')
                ->with('error', 'You are not yet eligible for enrollment.');
        }

        $details = $applicant->enrollmentDetail;

        return view('applicant.enrollment', compact('applicant', 'details'));
    }

    public function submitEnrollmentForm(Request $request): RedirectResponse
    {
        set_time_limit(600); 
        $applicant = Applicant::where('user_id', Auth::id())->firstOrFail();

        // 1. VALIDATION
        $request->validate([
            'lrn' => 'required|digits:12',
            'last_name' => 'required',
            'first_name' => 'required',
            'middle_name' => 'required',
            'extension_name' => 'required',
            'date_of_birth' => 'required|date',
            'sex' => 'required',
            'age' => 'required|numeric',
            'birthplace' => 'required|string|max:255',
            'religion' => 'nullable|string|max:255',
            'email' => 'required|email',
            'region' => 'required',
            'province' => 'required',
            'municipality_city' => 'required',
            'barangay' => 'required',
            'street_house_no' => 'required',
            'zip_code' => 'required|digits:4',
            'guardian_name' => 'required',
            'guardian_relationship' => 'required',
            'guardian_contact' => 'required',
            'guardian_email' => 'required|email',
            'last_grade_level' => 'required|string',
            'last_school_year' => 'required|string',
            'school_name' => 'required|string',
            'school_id' => 'required|string',
            'school_address' => 'required|string',
            'school_type' => 'required|string',
            'files.sa_info_form' => ($applicant->uploaded_files['sa_info_form'] ?? false) ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240' : 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'files.basic_ed_form' => ($applicant->uploaded_files['basic_ed_form'] ?? false) ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240' : 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'files.scholarship_agreement' => ($applicant->uploaded_files['scholarship_agreement'] ?? false) ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240' : 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'files.uniform_measurement' => ($applicant->uploaded_files['uniform_measurement'] ?? false) ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240' : 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'files.ppe_clearance' => ($applicant->uploaded_files['ppe_clearance'] ?? false) ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240' : 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'files.report_card' => ($applicant->uploaded_files['report_card'] ?? false) ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240' : 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'files.psa_birth_cert' => ($applicant->uploaded_files['psa_birth_cert'] ?? false) ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240' : 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'files.passport' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'files.mother_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'files.father_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'files.guardian_id' => ($applicant->uploaded_files['guardian_id'] ?? false) ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240' : 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'files.ip_cert' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'files.pwd_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'files.4ps_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // 3. CREATE/UPDATE ENROLLMENT RECORD
        EnrollmentDetail::updateOrCreate(
            ['applicant_id' => $applicant->id],
            [
                'lrn' => $request->lrn,
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'extension_name' => $request->extension_name,
                'date_of_birth' => $request->date_of_birth,
                'age' => $request->age,
                'sex' => $request->sex,
                'birthplace' => $request->birthplace,
                'religion' => $request->religion ?? 'N/A',
                'email' => $request->email,
                'region' => $request->region,
                'province' => $request->province,
                'municipality_city' => $request->municipality_city,
                'barangay' => $request->barangay,
                'street_house_no' => $request->street_house_no,
                'zip_code' => $request->zip_code,
                'is_ip' => $request->is_ip === 'Yes',
                'ip_group' => $request->is_ip === 'Yes' ? $request->ip_group : null,
                'is_pwd' => $request->is_pwd === 'Yes',
                'pwd_disability' => $request->is_pwd === 'Yes' ? $request->pwd_disability : null,
                'is_4ps' => $request->is_4ps === 'Yes',
                'sport' => $request->sport,
                'father_name' => $request->father_name ?? 'N/A',
                'father_address' => $request->father_address ?? 'N/A',
                'father_contact' => $request->father_contact ?? 'N/A',
                'father_email' => $request->father_email ?? 'N/A',
                'mother_maiden_name' => $request->mother_maiden_name ?? 'N/A',
                'mother_address' => $request->mother_address ?? 'N/A',
                'mother_contact' => $request->mother_contact ?? 'N/A',
                'mother_email' => $request->mother_email ?? 'N/A',
                'guardian_name' => $request->guardian_name,
                'guardian_relationship' => $request->guardian_relationship,
                'guardian_address' => $request->guardian_address,
                'guardian_contact' => $request->guardian_contact,
                'guardian_email' => $request->guardian_email,
                'last_grade_level' => $request->last_grade_level,
                'last_school_year' => $request->last_school_year,
                'school_name' => $request->school_name,
                'school_id' => $request->school_id,
                'school_address' => $request->school_address,
                'school_type' => $request->school_type,
            ]
        );

        // 4. PROCESS FILE UPLOADS
        $currentFiles = is_string($applicant->uploaded_files) ? json_decode($applicant->uploaded_files, true) : ($applicant->uploaded_files ?? []);
        $uploadedDocNames = []; 

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $key => $file) {
                try {
                    $upload = (new UploadApi())->upload($file->getRealPath(), [
                        'folder' => 'nas_student_portal/official_enrollment',
                        'resource_type' => 'auto'
                    ]);
                    $currentFiles[$key] = $upload['secure_url'];
                    $uploadedDocNames[] = $this->formatFileName($key);
                } catch (\Exception $e) {
                    continue; 
                }
            }
        }

        // 5. UPDATE APPLICANT STATUS ONLY
        // ⚡ DO NOT CHANGE ROLE TO 'STUDENT' HERE YET ⚡
        $isResubmission = !empty($applicant->document_remarks);
        
        $applicant->update([
            'uploaded_files' => $currentFiles,
            'status' => 'For Enrollment Verification',
            'document_remarks' => null // Clear remarks after revision
        ]);

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        $message = "Enrollment form submitted by: {$applicant->first_name} {$applicant->last_name}";
        Notification::send($admins, new NewApplicantNotification($applicant, $message));

        // Notify applicant
        Notification::send(Auth::user(), new EnrollmentSubmittedNotification($applicant, $isResubmission));

        // ✅ REDIRECT BACK TO APPLICANT DASHBOARD
        // Mananatili siyang applicant hanggang i-approve ng admin.
        return redirect()->route('applicant.dashboard')->with('success', 'Enrollment Form Submitted! Please wait for Admin confirmation.');
    }

    // ==========================================
    // HELPERS
    // ==========================================

    private function mapInputData(Request $request)
    {
        $data = $request->only([
            'lrn', 'last_name', 'first_name', 'middle_name', 
            'date_of_birth', 'age', 'gender', 
            'region', 'province', 'municipality_city', 'barangay', 
            'street_address', 'zip_code', 'learn_about_nas', 'attended_campaign',
            'sport', 'sport_specification', 'palaro_finisher', 'batang_pinoy_finisher', 
            'school_type', 'school_last_grade_level', 'school_last_year_completed', 'school_id',
            'guardian_name', 'guardian_relationship', 'guardian_email', 'guardian_contact'
        ]);
        $data['middle_name'] = $request->middle_name ?? 'N/A';
        $data['referrer_name'] = ($request->learn_about_nas === 'NAS Personnel / Student-Athlete Referral') 
            ? $request->referrer_name 
            : null;
        $data['is_ip'] = ($request->is_ip === 'Yes');
        $data['ip_group_name'] = $data['is_ip'] ? $request->ip_group_name : null;
        $data['is_pwd'] = ($request->is_pwd === 'Yes');
        $data['pwd_disability'] = $data['is_pwd'] ? $request->pwd_disability : null;
        $data['is_4ps'] = ($request->is_4ps === 'Yes');
        return $data;
    }

    private function handleInitialFileUploads($request, $applicant)
    {
        $currentFiles = is_string($applicant->uploaded_files) ? json_decode($applicant->uploaded_files, true) : ($applicant->uploaded_files ?? []);
        if ($request->hasFile('id_picture')) {
            try {
                $upload = (new UploadApi())->upload($request->file('id_picture')->getRealPath(), ['folder' => 'nas_student_portal/ids']);
                $currentFiles['id_picture'] = $upload['secure_url'];
                $applicant->update([
                    'uploaded_files' => $currentFiles,
                    'photo' => $upload['secure_url']
                ]);
            } catch (\Exception $e) {}
        } else {
             $applicant->update(['uploaded_files' => $currentFiles]);
        }
    }

    private function formatFileName($key) {
        return ucwords(str_replace(['_', 'id'], [' ', 'ID'], $key));
    }
}