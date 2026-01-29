<?php

namespace App\Http\Controllers;

use App\Models\Applicant; 
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http; 
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Support\Facades\Validator;

class ApplicantPortalController extends Controller
{
    // --- CONSTRUCTOR: INITIALIZE CLOUDINARY ---
    public function __construct()
    {
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
        // 👇 FIX: Bigyan ng 5 minutes (300s) bago mag-timeout dahil sa bagal ng Cloudinary upload
        set_time_limit(300);

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

    // --- UPDATE METHOD ---
    public function update(Request $request): RedirectResponse
    {
        // 👇 FIX: Bigyan ng 5 minutes (300s)
        set_time_limit(300);

        // 1. Get Application
        $application = Applicant::where('user_id', Auth::id())->firstOrFail();
        
        // Check Lock Status
        if (in_array($application->status, ['Enrolled'])) {
            return redirect()->route('applicant.dashboard')->with('error', 'Application is locked.');
        }

        // 2. Validate Inputs
        $validated = $this->validateApplication($request, true);
        
        // 3. Prepare Data (Text Fields)
        $data = $this->mapInputData($validated, $request, $application);

        // 4. Handle Remarks Clearing, Status & Timestamp Updating
        $remarks = $application->document_remarks ?? [];
        $statuses = $application->document_statuses ?? [];
        $fileTimestamps = $application->file_timestamps ?? []; 
        $hasNewUploads = false;

        // A. Check ID Picture Upload
        if ($request->hasFile('id_picture')) {
            if (isset($remarks['id_picture'])) {
                unset($remarks['id_picture']); 
            }
            $statuses['id_picture'] = 'pending_review';
            $fileTimestamps['id_picture'] = now()->toDateTimeString(); 
            $hasNewUploads = true;
        }

        // B. Check Document Uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $key => $file) {
                if (isset($remarks[$key])) {
                    unset($remarks[$key]); 
                }
                $statuses[$key] = 'pending_review';
                $fileTimestamps[$key] = now()->toDateTimeString();
                $hasNewUploads = true;
            }
        }

        // Save updated arrays
        $data['document_remarks'] = $remarks;
        $data['document_statuses'] = $statuses;
        $data['file_timestamps'] = $fileTimestamps; 

        // 5. Execute Update
        $application->update($data);

        return redirect()->route('applicant.dashboard')->with('success', 'Application updated successfully!');
    }

    // --- SUBMIT REQUIREMENTS (Dashboard) ---
    public function submitRequirements(Request $request): RedirectResponse
    {
        // 👇 FIX: Bigyan ng 5 minutes (300s) dahil maraming files ito
        set_time_limit(300);

        $application = Applicant::where('user_id', Auth::id())->first();

        if (!$application) {
            return back()->withErrors(['msg' => 'Application record not found.']);
        }

        // 1. Load Current Data
        $currentFiles = $application->uploaded_files ?? [];
        $remarks = $application->document_remarks ?? [];
        $statuses = $application->document_statuses ?? [];
        $fileTimestamps = $application->file_timestamps ?? []; 
        
        $hasChanges = false;

        // 2. Define Allowed Keys
        $allowedKeys = [
            'id_picture', 'scholarship_form', 'student_profile', 'medical_clearance', 
            'coach_reco', 'adviser_reco', 'birth_cert', 'report_card', 'guardian_id',
            'kukkiwon_cert', 'ip_cert', 'pwd_id', '4ps_id'
        ];

        // 3. Process Uploads (Iterate over 'files' array)
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $key => $file) {
                
                // Security Check
                if (!in_array($key, $allowedKeys)) {
                    continue; 
                }

                // Validate File
                $validator = Validator::make(
                    ['file' => $file], 
                    ['file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120']
                );

                if ($validator->fails()) {
                    return back()->withErrors(['msg' => "File upload error for {$key}: " . $validator->errors()->first()]);
                }

                try {
                    // Upload to Cloudinary
                    $upload = (new UploadApi())->upload($file->getRealPath(), [
                        'folder' => 'nas_student_portal/requirements',
                        'resource_type' => 'auto'
                    ]);
                    
                    // Update File URL & Timestamp
                    $currentFiles[$key] = $upload['secure_url'];
                    $fileTimestamps[$key] = now()->toDateTimeString(); 
                    $statuses[$key] = 'pending_review'; // Set status for review
                    
                    // Clear Remark if exists
                    if (isset($remarks[$key])) {
                        unset($remarks[$key]);
                    }
                    
                    $hasChanges = true;

                } catch (\Exception $e) {
                    return back()->withErrors(['msg' => 'Upload failed for ' . $key . ': ' . $e->getMessage()]);
                }
            }
        }

        // 4. Save Changes
        if ($hasChanges) {
            $application->uploaded_files = $currentFiles;
            $application->document_remarks = $remarks;
            $application->document_statuses = $statuses;
            $application->file_timestamps = $fileTimestamps;
            $application->save();
            
            return back()->with('success', 'Upload Successful! Requirements have been updated and sent for review.');
        }

        return back()->with('warning', 'No new files were selected to upload.');
    }

    // --- PROXY FILE VIEW ---
    public function viewFile($id, $type)
    {
        // 👇 FIX: Bigyan din ng time limit ang pag-view kung mabagal mag-load si Cloudinary
        set_time_limit(120); 

        $applicant = Applicant::findOrFail($id);

        if (Auth::id() !== $applicant->user_id && Auth::user()->role !== 'admin' && Auth::user()->role !== 'registrar') {
            abort(403, 'Unauthorized access.');
        }

        $files = $applicant->uploaded_files ?? [];
        $url = $files[$type] ?? null;

        if (!$url) {
            abort(404, 'File not found.');
        }

        try {
            $response = Http::get($url);

            if ($response->failed()) {
                abort(404, 'File could not be retrieved from storage.');
            }

            $fileContent = $response->body();
            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
            
            $mimeTypes = [
                'pdf'  => 'application/pdf',
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png'  => 'image/png',
            ];
            
            $contentType = $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
            $base64 = base64_encode($fileContent);
            $src = 'data:' . $contentType . ';base64,' . $base64;
            
            $fileType = (strtolower($extension) === 'pdf') ? 'pdf' : 'image';
            $fileName = strtoupper(str_replace('_', ' ', $type));

            return view('applicant.file-viewer', compact('src', 'fileType', 'fileName'));

        } catch (\Exception $e) {
            abort(404, 'Error loading file.');
        }
    }

    // --- HELPER METHODS ---

    private function mapInputData($validated, $request, $existingApp = null)
    {
        $data = $validated;
        
        if (isset($validated['date_of_birth'])) {
            $data['age'] = Carbon::parse($validated['date_of_birth'])->age;
        }
        
        // Handle Boolean Conversions
        if ($request->has('is_ip')) $data['is_ip'] = ($request->input('is_ip') === 'Yes');
        if ($request->has('is_pwd')) $data['is_pwd'] = ($request->input('is_pwd') === 'Yes');
        if ($request->has('is_4ps')) $data['is_4ps'] = ($request->input('is_4ps') === 'Yes');
        if ($request->has('palaro_finisher')) $data['has_palaro_participation'] = ($request->input('palaro_finisher') === 'Yes');
        
        $data['batang_pinoy_finisher'] = $request->input('batang_pinoy_finisher');
        $data['palaro_year'] = $request->input('palaro_year');

        // Handle File Uploads (Cloudinary) - Mostly used for 'store' method
        $currentFiles = $existingApp ? ($existingApp->uploaded_files ?? []) : [];
        $fileTimestamps = $existingApp ? ($existingApp->file_timestamps ?? []) : [];
        
        // Handle ID Picture
        if ($request->hasFile('id_picture')) {
            try {
                $upload = (new UploadApi())->upload($request->file('id_picture')->getRealPath(), [
                    'folder' => 'nas_student_portal/id_pictures',
                    'resource_type' => 'auto'
                ]);
                $currentFiles['id_picture'] = $upload['secure_url'];
                $fileTimestamps['id_picture'] = now()->toDateTimeString();
            } catch (\Exception $e) {}
        }

        // Handle Document Files (Array)
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $key => $file) {
                try {
                    $upload = (new UploadApi())->upload($file->getRealPath(), [
                        'folder' => "nas_student_portal/documents/{$key}",
                        'resource_type' => 'auto'
                    ]);
                    $currentFiles[$key] = $upload['secure_url'];
                    $fileTimestamps[$key] = now()->toDateTimeString();
                } catch (\Exception $e) {}
            }
        }
        
        $data['uploaded_files'] = $currentFiles;
        $data['file_timestamps'] = $fileTimestamps;
        
        // Remove non-DB fields
        unset($data['files']);
        unset($data['palaro_finisher']); 

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
            
            'region' => 'required|string',
            'province' => 'required|string',
            'municipality_city' => 'required|string',
            'barangay' => 'required|string',
            'street_address' => 'required|string',
            'zip_code' => 'required|string',
            
            'previous_school' => 'required|string',
            'school_type' => 'required|string',
            'grade_level_applied' => 'required|string',
            'sport' => 'required|string',
            'sport_specification' => 'nullable|string',
            
            'learn_about_nas' => 'nullable|string',
            'referrer_name' => 'nullable|string',
            'attended_campaign' => 'nullable|string',

            'is_ip' => 'required|in:Yes,No',
            'ip_group_name' => 'required_if:is_ip,Yes|nullable|string',
            
            'is_pwd' => 'required|in:Yes,No',
            'pwd_disability' => 'required_if:is_pwd,Yes|nullable|string',
            
            'is_4ps' => 'required|in:Yes,No',
            
            'palaro_finisher' => 'nullable|in:Yes,No',
            'palaro_year' => 'nullable|string',
            'batang_pinoy_finisher' => 'nullable|in:Yes,No',

            'guardian_name' => 'required|string',
            'guardian_relationship' => 'required|string',
            'guardian_contact' => 'required|string',
            'guardian_email' => 'nullable|email',
        ];

        if ($isUpdate) {
            $rules['files.*'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120';
            $rules['id_picture'] = 'nullable|image|max:5120';
        } else {
            // Note: Validation for file presence is mostly handled by HTML required attribute 
            // because conditional requirements (like kukkiwon) are hard to do in basic array validation.
            // We ensure files.* are valid types if present.
            $rules['files.*'] = 'file|mimes:jpg,jpeg,png,pdf|max:5120';
            $rules['id_picture'] = 'required|image|max:5120';
        }

        return $request->validate($rules);
    }
}