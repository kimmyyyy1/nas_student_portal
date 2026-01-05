<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ApplicantPortalController extends Controller
{
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

        // 1. Prepare Data
        $data = $this->mapInputData($validated, $request);
        
        // 2. Add Special Category Logic (Sync New Array -> Old Booleans)
        $data['user_id'] = Auth::id();
        $data['special_categories'] = $this->processCategories($request); // New Column
        
        // SYNC: Check if keywords exist in the categories array to set old booleans
        $data['is_ip'] = $this->checkCategory($categories, ['Indigenous People', 'IP']);
        $data['is_pwd'] = $this->checkCategory($categories, ['PWD', 'Person with Disability']);
        $data['is_4ps'] = $this->checkCategory($categories, ['4Ps', 'Beneficiary']);

        $data['status'] = 'Submitted (with Pending)';

        // 3. Create
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

        // 1. Prepare Data
        $data = $this->mapInputData($validated, $request, $application);

        // 2. Add Special Category Logic
        $data['special_categories'] = $this->processCategories($request); 
        $data['other_category_details'] = $validated['other_category_details'] ?? null;

        $data['is_ip'] = $this->checkCategory($categories, ['Indigenous People', 'IP']);
        $data['is_pwd'] = $this->checkCategory($categories, ['PWD', 'Person with Disability']);
        $data['is_4ps'] = $this->checkCategory($categories, ['4Ps', 'Beneficiary']);

        // 3. Update
        $application->update($data);

        return redirect()->route('applicant.dashboard')->with('success', 'Application updated successfully!');
    }

    /**
     * DITO ANG FIX: Logic para sa Upload Requirements sa Dashboard
     */
    public function submitRequirements(Request $request): RedirectResponse
    {
        // 1. Kunin ang Application
        $application = EnrollmentApplication::where('user_id', Auth::id())->first();

        if (!$application) {
            return back()->withErrors(['msg' => 'Application record not found.']);
        }

        // 2. Kunin ang existing uploaded files (Array)
        $currentFiles = $application->uploaded_files ?? [];

        // 3. Listahan ng mga expected files mula sa form
        $fields = ['sf10', 'good_moral', 'psa_birth_cert', 'medical_cert', 'coach_reco'];
        
        $hasChanges = false;

        // 4. Loop sa bawat field para i-check kung may in-upload
        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                
                // Validate file type and size (Max 10MB)
                $request->validate([
                    $field => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
                ]);

                // Kung may dating file, burahin muna sa storage para hindi kalat
                if (isset($currentFiles[$field]) && Storage::disk('public')->exists($currentFiles[$field])) {
                    Storage::disk('public')->delete($currentFiles[$field]);
                }

                // I-save ang bagong file
                // Path format: requirements/sf10_user123.pdf
                $filename = "{$field}_" . Auth::id() . '_' . time() . '.' . $request->file($field)->extension();
                $path = $request->file($field)->storeAs('requirements', $filename, 'public');

                // I-update ang array key
                $currentFiles[$field] = $path;
                
                $hasChanges = true;
            }
        }

        // 5. I-save sa database kapag may nabago
        if ($hasChanges) {
            $application->uploaded_files = $currentFiles;
            $application->save();

            return back()->with('success', 'Upload Successful! Requirements have been updated.');
        }

        return back()->with('success', 'No new files were selected.');
    }

    // --- Helper Methods ---

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

        // Files logic
        if ($isUpdate) {
            $rules['files.*'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120';
            $rules['id_picture'] = 'nullable|image|max:5120';
        } else {
            $rules['files.*'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:5120';
            $rules['id_picture'] = 'required|image|max:5120';
        }

        return $request->validate($rules);
    }

    private function mapInputData($validated, $request, $existingApp = null)
    {
        $data = $validated;
        
        // Calculate Age
        $data['age'] = Carbon::parse($validated['date_of_birth'])->age;
        $data['has_palaro_participation'] = $request->has('has_palaro_participation') ? 1 : 0;

        // Handle Files
        $currentFiles = $existingApp ? ($existingApp->uploaded_files ?? []) : [];

        if ($request->hasFile('id_picture')) {
            if (isset($currentFiles['id_picture'])) Storage::disk('public')->delete($currentFiles['id_picture']);
            $currentFiles['id_picture'] = $request->file('id_picture')->store('applicants/photos', 'public');
        }

        if ($request->has('files')) {
            foreach ($request->file('files') as $key => $file) {
                if (isset($currentFiles[$key])) Storage::disk('public')->delete($currentFiles[$key]);
                $currentFiles[$key] = $file->store("applicants/docs/{$key}", 'public');
            }
        }
        
        $data['uploaded_files'] = $currentFiles;
        
        unset($data['categories']); 
        unset($data['files']);

        return $data;
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