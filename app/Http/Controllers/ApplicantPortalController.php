<?php

namespace App\Http\Controllers;

use App\Models\Applicant; 
use App\Models\Team;
use App\Models\EnrollmentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $data = $this->mapInputData($validated, $request);
        $data['user_id'] = Auth::id();
        $data['status'] = 'Submitted for 1st Level Assessment'; 

        $applicant = Applicant::create($data);

        // 4. Upload 2x2 Photo
        $this->handleInitialFileUploads($request, $applicant);

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

        // 1. Load Current Data
        $currentFiles = is_string($applicant->uploaded_files) ? json_decode($applicant->uploaded_files, true) : ($applicant->uploaded_files ?? []);
        $docStatuses = is_string($applicant->document_statuses) ? json_decode($applicant->document_statuses, true) : ($applicant->document_statuses ?? []);

        // 2. Define All Possible Documents
        $allDocs = [
            'scholarship_form', 'student_profile', 'medical_clearance', 
            'psa_birth_cert', 'report_card', 'guardian_id',
            'kukkiwon_cert', 'ip_cert', 'pwd_id', '4ps_id'
        ];

        // 3. Dynamic Validation Rules (Re-upload Logic)
        $rules = [];
        foreach ($allDocs as $doc) {
            // Check relevance
            $isRelevant = true;
            if ($doc == 'kukkiwon_cert' && $applicant->sport !== 'Taekwondo') $isRelevant = false;
            if ($doc == 'ip_cert' && !$applicant->is_ip) $isRelevant = false;
            if ($doc == 'pwd_id' && !$applicant->is_pwd) $isRelevant = false;
            if ($doc == '4ps_id' && !$applicant->is_4ps) $isRelevant = false;

            if ($isRelevant) {
                // REQUIRED if: No file yet OR Status is 'declined'
                if (!isset($currentFiles[$doc]) || (isset($docStatuses[$doc]) && $docStatuses[$doc] === 'declined')) {
                    $rules["files.$doc"] = 'required|file|mimes:pdf,jpg,jpeg,png,heic,heif|max:10240';
                } else {
                    // OPTIONAL if already valid/pending
                    $rules["files.$doc"] = 'nullable|file|mimes:pdf,jpg,jpeg,png,heic,heif|max:10240';
                }
            }
        }

        $request->validate($rules);

        // 4. Process Uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $key => $file) {
                try {
                    $upload = (new UploadApi())->upload($file->getRealPath(), [
                        'folder' => 'nas_student_portal/phase2_requirements',
                        'resource_type' => 'auto'
                    ]);
                    
                    $currentFiles[$key] = $upload['secure_url'];
                    
                    // RESET STATUS to 'pending' so Admin reviews it again
                    $docStatuses[$key] = 'pending'; 

                } catch (\Exception $e) {
                    return back()->withErrors(['files' => 'Error uploading ' . $key . '. Please try again.']);
                }
            }
        }

        // 5. Update Record & Return Status to Review
        $applicant->update([
            'uploaded_files' => $currentFiles,
            'document_statuses' => $docStatuses,
            'status' => 'Requirements Submitted & For Review'
        ]);

        return redirect()->route('applicant.dashboard')->with('success', 'Requirements submitted successfully!');
    }

    // ==========================================
    // PHASE 3: OFFICIAL ENROLLMENT (ADDED THIS)
    // ==========================================

    public function showEnrollmentForm(): View|RedirectResponse
    {
        $applicant = Applicant::where('user_id', Auth::id())->firstOrFail();

        // Strict Check: Dapat Qualified lang ang makakapasok
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

        // Validation for the Signed Form
        $request->validate([
            'signed_enrollment_form' => 'required|file|mimes:pdf,jpg,jpeg,png,heic,heif|max:10240',
        ]);

        // Upload Logic for Enrollment Form
        $currentFiles = is_string($applicant->uploaded_files) ? json_decode($applicant->uploaded_files, true) : ($applicant->uploaded_files ?? []);

        if ($request->hasFile('signed_enrollment_form')) {
            try {
                $upload = (new UploadApi())->upload($request->file('signed_enrollment_form')->getRealPath(), [
                    'folder' => 'nas_student_portal/enrollment_forms',
                    'resource_type' => 'auto'
                ]);
                $currentFiles['signed_enrollment_form'] = $upload['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors(['signed_enrollment_form' => 'Upload failed. Please try again.']);
            }
        }

        // Final Update - Create Enrollment Record
        EnrollmentRecord::updateOrCreate(
            ['applicant_id' => $applicant->id],
            [
                'signed_enrollment_form' => $currentFiles['signed_enrollment_form'],
                'enrollment_status' => 'Pending Verification'
            ]
        );

        $applicant->update(['status' => 'Enrolled - Pending Verification']);

        return redirect()->route('applicant.dashboard')->with('success', 'Official Enrollment Documents Submitted Successfully.');
    }

    // ==========================================
    // HELPERS
    // ==========================================

    private function mapInputData($validated, $request)
    {
        $data = $validated;
        
        $data['middle_name'] = $request->middle_name ?? 'N/A';
        $data['referrer_name'] = $request->referrer_name;
        $data['ip_group_name'] = $request->ip_group_name;
        $data['pwd_disability'] = $request->pwd_disability;
        $data['sport_specification'] = $request->sport_specification;

        $data['palaro_finisher'] = $request->palaro_finisher;
        $data['batang_pinoy_finisher'] = $request->batang_pinoy_finisher;
        $data['school_type'] = $request->school_type;

        $data['is_ip'] = ($request->is_ip === 'Yes');
        $data['is_pwd'] = ($request->is_pwd === 'Yes');
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
}