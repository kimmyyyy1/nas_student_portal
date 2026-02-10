<?php

namespace App\Http\Controllers;

use App\Models\Applicant; 
use App\Models\Team;
use App\Models\User; // Added for Notification recipients
use App\Models\EnrollmentDetail; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification; // Added for Notification facade
use App\Notifications\ApplicantDocumentsSubmitted; // Your Notification Class
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

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

    public function index(): View
    {
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
        
        // 1. Validate Input (Fields only)
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
            'guardian_name' => 'required|string',
            'guardian_relationship' => 'required|string',
            'guardian_email' => 'required|email',
            'guardian_contact' => 'required|string|max:11',
        ]);

        // 2. Validate ONLY 2x2 Photo
        if (!$request->hasFile('id_picture')) {
            return back()->withErrors(['id_picture' => 'The 2x2 ID Picture is required.'])->withInput();
        }

        // 3. Map Data & Save
        $data = $this->mapInputData($request); 
        $data['user_id'] = Auth::id();
        $data['status'] = 'Submitted for 1st Level Assessment'; 

        $applicant = Applicant::create($data);

        // 4. Upload 2x2 Photo
        $this->handleInitialFileUploads($request, $applicant);

        // --- NOTIFICATION: New Application Submitted ---
        $admins = User::whereIn('role', ['admin', 'registrar'])->get();
        if ($admins->count() > 0) {
            Notification::send($admins, new ApplicantDocumentsSubmitted(
                $applicant, 
                'new', 
                'Application Form', 
                'Initial Registration'
            ));
        }

        return redirect()->route('applicant.dashboard')->with('success', 'Application submitted! Please wait for the initial assessment.');
    }

    // ==========================================
    // PHASE 2: REQUIREMENTS (UPLOAD & RE-UPLOAD)
    // ==========================================

    public function showRequirements(): View|RedirectResponse
    {
        $applicant = Applicant::where('user_id', Auth::id())->firstOrFail();

        // Allow access if "For 2nd Level" OR "Returned for Re-upload"
        if (!str_contains($applicant->status, '2nd Level') && !str_contains($applicant->status, 'Returned')) {
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
                if (!isset($currentFiles[$doc]) || (isset($docStatuses[$doc]) && $docStatuses[$doc] === 'declined')) {
                    $rules["files.$doc"] = 'required|file|mimes:pdf,jpg,jpeg,png,heic,heif|max:10240';
                } else {
                    $rules["files.$doc"] = 'nullable|file|mimes:pdf,jpg,jpeg,png,heic,heif|max:10240';
                }
            }
        }

        $request->validate($rules);
        
        // --- 1. COLLECT FILES & NAMES ---
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
                    
                    // Check if this specific file was previously declined (Resubmission logic)
                    if (isset($docStatuses[$key]) && $docStatuses[$key] === 'declined') {
                        $isResubmission = true;
                    }
                    
                    // Reset status to pending
                    $docStatuses[$key] = 'pending'; 

                    // Add to list for notification
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

        // --- 2. SEND SINGLE GROUPED NOTIFICATION ---
        if (count($uploadedDocNames) > 0) {
            $admins = User::whereIn('role', ['admin', 'registrar'])->get();
            
            if ($admins->count() > 0) {
                // A. CLEANUP: Delete OLD UNREAD notifications from this applicant for Admission Requirements
                foreach ($admins as $admin) {
                    $admin->notifications()
                          ->where('data->applicant_id', $applicant->id)
                          ->where('data->link', 'like', '%admission%') // Target Phase 2 only
                          ->whereNull('read_at')
                          ->delete();
                }

                // B. FORMAT MESSAGE
                // Example: "PSA Birth Cert, Report Card" OR "5 Documents"
                $count = count($uploadedDocNames);
                $fileList = $count <= 2 
                    ? implode(' and ', $uploadedDocNames) 
                    : $count . ' Documents (' . $uploadedDocNames[0] . ' and others)';

                // C. SEND
                Notification::send($admins, new ApplicantDocumentsSubmitted(
                    $applicant, 
                    $isResubmission ? 'resubmission' : 'new',
                    $fileList, 
                    'Admission Requirements'
                ));
            }
        }

        return redirect()->route('applicant.dashboard')->with('success', 'Requirements submitted successfully!');
    }

    // ==========================================
    // PHASE 3: OFFICIAL ENROLLMENT (UPDATED LOGIC)
    // ==========================================

    public function showEnrollmentForm(): View|RedirectResponse
    {
        $applicant = Applicant::where('user_id', Auth::id())->firstOrFail();

        if (!str_contains($applicant->status, 'Qualified')) {
            return redirect()->route('applicant.dashboard')
                ->with('error', 'You are not yet eligible for enrollment.');
        }

        return view('applicant.enrollment', compact('applicant'));
    }

    public function submitEnrollmentForm(Request $request): RedirectResponse
    {
        set_time_limit(600); 
        $applicant = Applicant::where('user_id', Auth::id())->firstOrFail();

        // 1. VALIDATION
        $request->validate([
            // Basic Info
            'lrn' => 'required|digits:12',
            'last_name' => 'required',
            'first_name' => 'required',
            'date_of_birth' => 'required|date',
            'sex' => 'required',
            'age' => 'required|numeric',
            'email' => 'required|email',
            
            // Address
            'region' => 'required',
            'province' => 'required',
            'municipality_city' => 'required',
            'barangay' => 'required',
            'street_house_no' => 'required',
            'zip_code' => 'required|digits:4',

            // Parents
            'guardian_name' => 'required',
            'guardian_relationship' => 'required',
            'guardian_contact' => 'required',
            'guardian_email' => 'required|email',

            // Files (Mandatory)
            'files.sa_info_form' => 'required|file|max:10240',
            'files.scholarship_app_form' => 'required|file|max:10240',
            'files.sa_profile_form' => 'required|file|max:10240',
            'files.ppe_clearance' => 'required|file|max:10240',
            'files.psa_birth_cert' => 'required|file|max:10240',
            'files.report_card' => 'required|file|max:10240',
            'files.guardian_id' => 'required|file|max:10240',
        ]);

        // 2. CHECK TRANSFEREE STATUS & DEFAULTS
        $schoolDefault = 'N/A';
        $isTransferee = !empty($request->school_name); 

        // 3. CREATE/UPDATE ENROLLMENT RECORD
        EnrollmentDetail::updateOrCreate(
            ['applicant_id' => $applicant->id],
            [
                // Student Info
                'lrn' => $request->lrn,
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name ?? 'N/A',
                'extension_name' => $request->extension_name ?? 'N/A',
                'date_of_birth' => $request->date_of_birth,
                'age' => $request->age,
                'sex' => $request->sex,
                'email' => $request->email,

                // Address
                'region' => $request->region,
                'province' => $request->province,
                'municipality_city' => $request->municipality_city,
                'barangay' => $request->barangay,
                'street_house_no' => $request->street_house_no,
                'zip_code' => $request->zip_code,

                // Groups logic
                'is_ip' => $request->is_ip === 'Yes',
                'ip_group' => $request->is_ip === 'Yes' ? $request->ip_group : null,
                'is_pwd' => $request->is_pwd === 'Yes',
                'pwd_disability' => $request->is_pwd === 'Yes' ? $request->pwd_disability : null,
                'is_4ps' => $request->is_4ps === 'Yes',

                // Sport
                'sport' => $request->sport,

                // Parents Info
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

                // School Info
                'last_grade_level' => $isTransferee ? $request->last_grade_level : $schoolDefault,
                'last_school_year' => $isTransferee ? $request->last_school_year : $schoolDefault,
                'school_name' => $isTransferee ? $request->school_name : $schoolDefault,
                'school_id' => $isTransferee ? $request->school_id : $schoolDefault,
                'school_address' => $isTransferee ? $request->school_address : $schoolDefault,
                'school_type' => $isTransferee ? $request->school_type : $schoolDefault,
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

                    // Add to list
                    $uploadedDocNames[] = $this->formatFileName($key);

                } catch (\Exception $e) {
                    continue; 
                }
            }
        }

        // 5. UPDATE APPLICANT STATUS
        $applicant->update([
            'uploaded_files' => $currentFiles,
            'status' => 'Officially Enrolled' // Final Status
        ]);

        // --- GROUPED NOTIFICATION FOR ENROLLMENT ---
        $admins = User::whereIn('role', ['admin', 'registrar'])->get();
        if ($admins->count() > 0) {
            
            // Cleanup Old Unread Notifications
            foreach ($admins as $admin) {
                $admin->notifications()
                      ->where('data->applicant_id', $applicant->id)
                      ->where('data->link', 'like', '%official-enrollment%') // Target Phase 3 only
                      ->whereNull('read_at')
                      ->delete();
            }

            // Summary Message
            $message = count($uploadedDocNames) > 0 
                ? "Enrollment Form and " . count($uploadedDocNames) . " Requirements" 
                : "Official Enrollment Form";

            Notification::send($admins, new ApplicantDocumentsSubmitted(
                $applicant, 
                'new',
                $message, 
                'Official Enrollment'
            ));
        }

        return redirect()->route('applicant.dashboard')->with('success', 'Enrollment Form & Requirements Submitted Successfully! Welcome to NAS!');
    }

    // ==========================================
    // HELPERS
    // ==========================================

    private function mapInputData(Request $request)
    {
        // 1. Basic Fields
        $data = $request->only([
            'lrn', 'last_name', 'first_name', 'middle_name', 
            'date_of_birth', 'age', 'gender', 
            'region', 'province', 'municipality_city', 'barangay', 
            'street_address', 'zip_code', 'learn_about_nas', 'attended_campaign',
            'sport', 'sport_specification', 'palaro_finisher', 'batang_pinoy_finisher', 
            'school_type', 'guardian_name', 'guardian_relationship', 'guardian_email', 'guardian_contact'
        ]);

        // 2. Handle Defaults
        $data['middle_name'] = $request->middle_name ?? 'N/A';
        $data['referrer_name'] = ($request->learn_about_nas === 'NAS Personnel / Student-Athlete Referral') 
            ? $request->referrer_name 
            : null;

        // 3. Handle Group Conditionals
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
            } catch (\Exception $e) {}
        }

        $applicant->update(['uploaded_files' => $currentFiles]);
    }

    // Helper to format file keys (e.g., psa_birth_cert -> PSA Birth Certificate)
    private function formatFileName($key) {
        return ucwords(str_replace(['_', 'id'], [' ', 'ID'], $key));
    }
}