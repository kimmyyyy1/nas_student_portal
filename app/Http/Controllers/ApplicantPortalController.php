<?php

namespace App\Http\Controllers;

use App\Models\Applicant; // ✅ FIXED: Use Applicant model
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Support\Str;

// Cloudinary Imports
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class ApplicantPortalController extends Controller
{
    // --- CONSTRUCTOR: INITIALIZE CLOUDINARY ---
    public function __construct()
    {
        // Siguraduhin na tama ang credentials dito
        Configuration::instance([
            'cloud' => [
                'cloud_name' => 'dqkzofruk', 
                'api_key'    => '452544782214523', 
                'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo'
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    }

    public function index(): View
    {
        $application = Applicant::where('user_id', Auth::id())->first();
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
        $validated = $this->validateApplication($request);
        
        // Map data using helper
        $data = $this->mapInputData($validated, $request);
        
        $data['user_id'] = Auth::id();
        $data['status'] = 'Pending'; // Set initial status

        // Create Record
        Applicant::create($data);

        return redirect()->route('applicant.dashboard')->with('success', 'Application submitted successfully!');
    }

    public function edit(): View|RedirectResponse
    {
        $application = Applicant::where('user_id', Auth::id())->firstOrFail();
        
        if (in_array($application->status, ['Enrolled', 'Qualified'])) { 
             return redirect()->route('applicant.dashboard')->with('error', 'Cannot edit application at this stage.');
        }

        $teams = Team::orderBy('team_name')->get();
        return view('applicant.edit', compact('application', 'teams'));
    }

    // --- UPDATED UPDATE METHOD (With Remarks Clearing) ---
    public function update(Request $request): RedirectResponse
    {
        // 1. Get Application
        $application = Applicant::where('user_id', Auth::id())->firstOrFail();
        
        // Check Lock Status
        if (in_array($application->status, ['Enrolled'])) {
            return redirect()->route('applicant.dashboard')->with('error', 'Application is locked.');
        }

        // 2. Validate Inputs
        $validated = $this->validateApplication($request, true);
        
        // 3. Prepare Data (Text Fields)
        // We use mapInputData but need to manually handle file merging & remarks clearing
        $data = $this->mapInputData($validated, $request, $application);

        // 4. Handle Remarks Clearing (Crucial Step)
        // We need to check if specific files were uploaded and remove their corresponding remarks
        $remarks = $application->document_remarks ?? [];
        $hasNewUploads = false;

        // Check ID Picture Upload
        if ($request->hasFile('id_picture')) {
            if (isset($remarks['id_picture'])) {
                unset($remarks['id_picture']); // Clear remark
                $hasNewUploads = true;
            }
        }

        // Check Document Uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $key => $file) {
                if (isset($remarks[$key])) {
                    unset($remarks[$key]); // Clear remark for specific file
                    $hasNewUploads = true;
                }
            }
        }

        // Update Remarks in Data array
        $data['document_remarks'] = $remarks;

        // 5. Update Application Status Logic
        // If there were remarks and we cleared some, and now remarks are empty, we can reset status
        // Or if status was "Not Qualified" / "For Assessment" due to docs, maybe move back to "Pending Review"
        if ($hasNewUploads) {
            // Optional: Reset status if all issues are resolved, or keep as is for Admin to re-check
            // $data['status'] = 'Pending Review'; 
        }

        // 6. Execute Update
        $application->update($data);

        return redirect()->route('applicant.dashboard')->with('success', 'Application updated successfully!');
    }

    public function submitRequirements(Request $request): RedirectResponse
    {
        $application = Applicant::where('user_id', Auth::id())->first();

        if (!$application) {
            return back()->withErrors(['msg' => 'Application record not found.']);
        }

        $currentFiles = $application->uploaded_files ?? [];
        $fields = ['sf10', 'good_moral', 'psa_birth_cert', 'medical_cert', 'coach_reco'];
        $hasChanges = false;

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $request->validate([
                    $field => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ]);

                try {
                    $upload = (new UploadApi())->upload($request->file($field)->getRealPath(), [
                        'folder' => 'nas_student_portal/requirements',
                        'resource_type' => 'auto'
                    ]);
                    
                    $currentFiles[$field] = $upload['secure_url'];
                    $hasChanges = true;
                } catch (\Exception $e) {
                    return back()->withErrors(['msg' => 'Upload failed for ' . $field . ': ' . $e->getMessage()]);
                }
            }
        }

        if ($hasChanges) {
            $application->uploaded_files = $currentFiles;
            $application->save();
            return back()->with('success', 'Upload Successful! Requirements have been updated.');
        }

        return back()->with('success', 'No new files were selected.');
    }

    // --- HELPER METHODS ---

    private function mapInputData($validated, $request, $existingApp = null)
    {
        // Start with validated data
        $data = $validated;
        
        // Calculate Age
        $data['age'] = Carbon::parse($validated['date_of_birth'])->age;
        
        // Handle Boolean Conversions (Yes/No -> 1/0)
        $data['is_ip'] = ($request->input('is_ip') === 'Yes');
        $data['is_pwd'] = ($request->input('is_pwd') === 'Yes');
        $data['is_4ps'] = ($request->input('is_4ps') === 'Yes');
        $data['has_palaro_participation'] = ($request->input('palaro_finisher') === 'Yes');
        
        // Handle Direct Strings
        $data['batang_pinoy_finisher'] = $request->input('batang_pinoy_finisher');
        $data['palaro_year'] = $request->input('palaro_year');

        // Handle File Uploads (Cloudinary)
        $currentFiles = $existingApp ? ($existingApp->uploaded_files ?? []) : [];

        // 1. ID Picture
        if ($request->hasFile('id_picture')) {
            try {
                $upload = (new UploadApi())->upload($request->file('id_picture')->getRealPath(), [
                    'folder' => 'nas_student_portal/id_pictures',
                    'resource_type' => 'auto'
                ]);
                $currentFiles['id_picture'] = $upload['secure_url'];
            } catch (\Exception $e) {
                // Log error if needed
            }
        }

        // 2. Document Files
        if ($request->has('files')) {
            foreach ($request->file('files') as $key => $file) {
                try {
                    $upload = (new UploadApi())->upload($file->getRealPath(), [
                        'folder' => "nas_student_portal/documents/{$key}",
                        'resource_type' => 'auto'
                    ]);
                    $currentFiles[$key] = $upload['secure_url'];
                } catch (\Exception $e) {
                    // Log error
                }
            }
        }
        
        $data['uploaded_files'] = $currentFiles;
        
        // Remove unnecessary keys that are not in DB columns
        unset($data['files']);
        unset($data['palaro_finisher']); // Helper input only

        return $data;
    }

    private function validateApplication(Request $request, $isUpdate = false)
    {
        $rules = [
            'lrn' => 'required|string|max:20',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string',
            'birthplace' => 'required|string',
            'religion' => 'nullable|string',
            'email_address' => 'required|email',
            
            // Address
            'region' => 'required|string',
            'province' => 'required|string',
            'municipality_city' => 'required|string',
            'barangay' => 'required|string',
            'street_address' => 'required|string',
            'zip_code' => 'required|string',
            
            // Academics
            'previous_school' => 'required|string',
            'school_type' => 'required|string',
            'grade_level_applied' => 'required|string',
            'sport' => 'required|string',
            'sport_specification' => 'nullable|string',
            
            // Background
            'learn_about_nas' => 'nullable|string',
            'referrer_name' => 'nullable|string',
            'attended_campaign' => 'nullable|string',

            // 👇 IMPORTANT: Required if Yes validation
            'is_ip' => 'required|in:Yes,No',
            'ip_group_name' => 'required_if:is_ip,Yes|nullable|string',
            
            'is_pwd' => 'required|in:Yes,No',
            'pwd_disability' => 'required_if:is_pwd,Yes|nullable|string',
            
            'is_4ps' => 'required|in:Yes,No',
            
            // Achievements
            'palaro_finisher' => 'nullable|in:Yes,No',
            'palaro_year' => 'nullable|string',
            'batang_pinoy_finisher' => 'nullable|in:Yes,No',

            // Guardian
            'guardian_name' => 'required|string',
            'guardian_relationship' => 'required|string',
            'guardian_contact' => 'required|string',
            'guardian_email' => 'nullable|email',
        ];

        // File Validation Logic
        if ($isUpdate) {
            $rules['files.*'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120';
            $rules['id_picture'] = 'nullable|image|max:5120';
        } else {
            // Required only on creation
            $rules['files.*'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:5120';
            $rules['id_picture'] = 'required|image|max:5120';
        }

        return $request->validate($rules);
    }
}