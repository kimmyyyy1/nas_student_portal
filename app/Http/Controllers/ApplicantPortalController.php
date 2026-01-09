<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Support\Str;

// 👇 IMPORTANT IMPORTS PARA SA MANUAL UPLOAD
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class ApplicantPortalController extends Controller
{
    // --- CONSTRUCTOR: INITIALIZE CLOUDINARY ---
    public function __construct()
    {
        // Sine-set natin ang configuration tuwing gagamitin ang controller na ito.
        // Siguradong walang "Undefined array key" dahil nandito na mismo ang credentials.
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
        $application = EnrollmentApplication::where('user_id', Auth::id())->first();
        return view('applicant.dashboard', compact('application'));
    }

    public function create(): View|RedirectResponse
    {
        $existing = EnrollmentApplication::where('user_id', Auth::id())->first();
        if ($existing) return redirect()->route('applicant.dashboard');
        return view('applicant.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateApplication($request);
        $categories = $request->input('categories', []);

        $data = $this->mapInputData($validated, $request);
        
        $data['user_id'] = Auth::id();
        $data['special_categories'] = $this->processCategories($request);
        $data['is_ip'] = $this->checkCategory($categories, ['Indigenous People', 'IP']);
        $data['is_pwd'] = $this->checkCategory($categories, ['PWD', 'Person with Disability']);
        $data['is_4ps'] = $this->checkCategory($categories, ['4Ps', 'Beneficiary']);
        $data['status'] = 'Submitted (with Pending)';

        EnrollmentApplication::create($data);

        return redirect()->route('applicant.dashboard')->with('success', 'Application submitted successfully!');
    }

    public function edit(): View|RedirectResponse
    {
        $application = EnrollmentApplication::where('user_id', Auth::id())->firstOrFail();
        if (in_array($application->status, ['Enrolled'])) { 
             return redirect()->route('applicant.dashboard')->with('error', 'Cannot edit application at this stage.');
        }
        return view('applicant.edit', compact('application'));
    }

    public function update(Request $request): RedirectResponse
    {
        $application = EnrollmentApplication::where('user_id', Auth::id())->firstOrFail();
        
        if (in_array($application->status, ['Enrolled'])) {
            return redirect()->route('applicant.dashboard')->with('error', 'Application is locked.');
        }

        $validated = $this->validateApplication($request, true);
        $categories = $request->input('categories', []);

        $data = $this->mapInputData($validated, $request, $application);

        $data['special_categories'] = $this->processCategories($request); 
        $data['other_category_details'] = $validated['other_category_details'] ?? null;
        $data['is_ip'] = $this->checkCategory($categories, ['Indigenous People', 'IP']);
        $data['is_pwd'] = $this->checkCategory($categories, ['PWD', 'Person with Disability']);
        $data['is_4ps'] = $this->checkCategory($categories, ['4Ps', 'Beneficiary']);

        $application->update($data);

        return redirect()->route('applicant.dashboard')->with('success', 'Application updated successfully!');
    }

    // --- UPDATED: MANUAL UPLOAD FOR REQUIREMENTS ---
    public function submitRequirements(Request $request): RedirectResponse
    {
        $application = EnrollmentApplication::where('user_id', Auth::id())->first();

        if (!$application) {
            return back()->withErrors(['msg' => 'Application record not found.']);
        }

        $currentFiles = $application->uploaded_files ?? [];
        $fields = ['sf10', 'good_moral', 'psa_birth_cert', 'medical_cert', 'coach_reco'];
        $hasChanges = false;

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $request->validate([
                    $field => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
                ]);

                // ✅ MANUAL FIX: Direct Cloudinary Upload
                try {
                    $upload = (new UploadApi())->upload($request->file($field)->getRealPath(), [
                        'folder' => 'requirements',
                        'resource_type' => 'auto'
                    ]);
                    
                    // Kuhanin diretso ang URL galing sa response
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

    // --- Helper Methods ---

    private function mapInputData($validated, $request, $existingApp = null)
    {
        $data = $validated;
        
        $data['age'] = Carbon::parse($validated['date_of_birth'])->age;
        $data['has_palaro_participation'] = $request->has('has_palaro_participation') ? 1 : 0;

        $currentFiles = $existingApp ? ($existingApp->uploaded_files ?? []) : [];

        // ✅ FIX 1: ID Picture Upload (MANUAL)
        if ($request->hasFile('id_picture')) {
            try {
                $upload = (new UploadApi())->upload($request->file('id_picture')->getRealPath(), [
                    'folder' => 'applicants/photos',
                    'resource_type' => 'auto'
                ]);

                // Save secure URL directly
                $url = $upload['secure_url'];
                $data['id_picture_url'] = $url;
                $currentFiles['id_picture'] = $url;
            } catch (\Exception $e) {
                // Log error or handle gracefully
            }
        }

        // ✅ FIX 2: Document Files Upload Loop (MANUAL)
        if ($request->has('files')) {
            foreach ($request->file('files') as $key => $file) {
                try {
                    $upload = (new UploadApi())->upload($file->getRealPath(), [
                        'folder' => "applicants/docs/{$key}",
                        'resource_type' => 'auto'
                    ]);

                    $currentFiles[$key] = $upload['secure_url'];
                } catch (\Exception $e) {
                    // Continue uploading other files even if one fails
                }
            }
        }
        
        $data['uploaded_files'] = $currentFiles;
        
        unset($data['categories']); 
        unset($data['files']);

        return $data;
    }

    // ... (Validation & Logic helpers retained below) ...
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
            'categories' => 'nullable|array',
            'other_category_details' => 'nullable|string',
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
            'guardian_name' => 'required|string',
            'guardian_relationship' => 'required|string',
            'guardian_contact' => 'required|string',
            'guardian_email' => 'nullable|email',
            'has_palaro_participation' => 'nullable',
            'palaro_year' => 'nullable|string',
        ];

        if ($isUpdate) {
            $rules['files.*'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120';
            $rules['id_picture'] = 'nullable|image|max:5120';
        } else {
            $rules['files.*'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:5120';
            $rules['id_picture'] = 'required|image|max:5120';
        }

        return $request->validate($rules);
    }

    private function processCategories(Request $request): ?string
    {
        $categories = $request->input('categories', []);
        $finalList = [];
        foreach ($categories as $cat) {
            if ($cat === 'Others') {
                $details = $request->input('other_category_details');
                $finalList[] = $details ? "Others: " . trim($details) : "Others";
            } else {
                $finalList[] = $cat;
            }
        }
        return !empty($finalList) ? implode(', ', $finalList) : null;
    }

    private function checkCategory($categories, $keywords)
    {
        foreach ($categories as $cat) {
            foreach ($keywords as $keyword) {
                if (Str::contains(strtolower($cat), strtolower($keyword))) return true;
            }
        }
        return false;
    }
}