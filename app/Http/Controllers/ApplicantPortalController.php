<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Ensure this is imported
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ApplicantPortalController extends Controller
{
    // ... (keep index, create, edit methods as they are) ...
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

    // ... (keep submitRequirements logic, just updated below) ...
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

                // ✅ FIX: Use store() with 'cloudinary' disk
                // This returns the path (public ID) on Cloudinary
                $path = $request->file($field)->store('requirements', 'cloudinary');
                
                // Get the Full URL
                $url = Storage::disk('cloudinary')->url($path);

                $currentFiles[$field] = $url;
                $hasChanges = true;
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

        // ✅ FIX 1: ID Picture Upload
        if ($request->hasFile('id_picture')) {
            // Upload using the standard Laravel storage facade with 'cloudinary' disk
            $path = $request->file('id_picture')->store('applicants/photos', 'cloudinary');
            
            // Get the secure URL to save in the database
            $data['id_picture_url'] = Storage::disk('cloudinary')->url($path); // Optional: if you save URL separately
            $currentFiles['id_picture'] = Storage::disk('cloudinary')->url($path);
        }

        // ✅ FIX 2: Document Files Upload Loop
        if ($request->has('files')) {
            foreach ($request->file('files') as $key => $file) {
                $path = $file->store("applicants/docs/{$key}", 'cloudinary');
                $currentFiles[$key] = Storage::disk('cloudinary')->url($path);
            }
        }
        
        $data['uploaded_files'] = $currentFiles;
        
        unset($data['categories']); 
        unset($data['files']);

        return $data;
    }

    // ... (keep validateApplication, processCategories, checkCategory exactly as they were) ...
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