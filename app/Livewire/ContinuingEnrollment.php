<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Applicant; 
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class ContinuingEnrollment extends Component
{
    use WithFileUploads;

    public $grade_level;
    public $sa_info_form; 
    public $report_card;
    public $medical_clearance;

    // Existing Data
    public $applicant;
    public $statuses = [];
    public $remarks = [];
    public $currentFiles = [];

    public function mount()
    {
        $user = Auth::user();
        $student = Student::where('nas_student_id', $user->student_id)->first();
        
        $this->applicant = Applicant::where('user_id', $user->id)
                         ->orWhere('lrn', $student->lrn ?? null)
                         ->first();

        if ($this->applicant) {
            $this->statuses = $this->applicant->document_statuses ?? [];
            $this->remarks = $this->applicant->document_remarks ?? [];
            $this->currentFiles = is_string($this->applicant->uploaded_files) 
                                 ? json_decode($this->applicant->uploaded_files, true) 
                                 : ($this->applicant->uploaded_files ?? []);
            
            $this->grade_level = $this->remarks['renewal_grade_level'] ?? '';
        }
    }

    protected function rules()
    {
        $rules = [
            'grade_level' => 'required|string',
        ];

        $fields = [
            'sa_info_form'      => 'renewal_sa_info_form',
            'report_card'       => 'renewal_report_card',
            'medical_clearance' => 'renewal_medical_clearance',
        ];

        foreach ($fields as $model => $key) {
            $status = $this->statuses[$key] ?? 'pending';
            $isMissing = !isset($this->currentFiles[$key]) || empty($this->currentFiles[$key]);
            
            // Required if missing OR declined
            if ($isMissing || $status === 'declined') {
                $rules[$model] = 'required|mimes:jpg,jpeg,png,pdf|max:5120';
            } else {
                $rules[$model] = 'nullable|mimes:jpg,jpeg,png,pdf|max:5120';
            }
        }

        return $rules;
    }

    public function submitEnrollment()
    {
        $this->validate();

        // 🚀 GAYAHIN ANG CONFIGURATION SA APPLICANT PORTAL
        Configuration::instance([
            'cloud' => [
                'cloud_name' => 'dqkzofruk', 
                'api_key'    => '452544782214523', 
                'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo'
            ],
            'url' => ['secure' => true]
        ]);

        try {
            $user = Auth::user();
            $uploadApi = new UploadApi();
            $student = Student::where('nas_student_id', $user->student_id)->first();
            
            $fileUrls = [];
            $updatedStatuses = $this->statuses;
            $isNewSubmission = false;

            // 1. Upload sa Cloudinary (Only if provided)
            if ($this->sa_info_form) {
                $fileUrls['renewal_sa_info_form'] = $uploadApi->upload($this->sa_info_form->getRealPath(), ['folder' => 'nas_requirements'])['secure_url'];
                $updatedStatuses['renewal_sa_info_form'] = 'pending';
                $isNewSubmission = true;
            }
            if ($this->report_card) {
                $fileUrls['renewal_report_card'] = $uploadApi->upload($this->report_card->getRealPath(), ['folder' => 'nas_requirements'])['secure_url'];
                $updatedStatuses['renewal_report_card'] = 'pending';
                $isNewSubmission = true;
            }
            if ($this->medical_clearance) {
                $fileUrls['renewal_medical_clearance'] = $uploadApi->upload($this->medical_clearance->getRealPath(), ['folder' => 'nas_requirements'])['secure_url'];
                $updatedStatuses['renewal_medical_clearance'] = 'pending';
                $isNewSubmission = true;
            }

            // 2. Database Update - Student Record
            if ($student) {
                $student->update([
                    'status' => 'Pending Renewal',
                    'enrollment_remarks' => 'Renewal requested for ' . $this->grade_level
                ]);
            }

            // 3. Database Update - Applicant Record (for tracking and file storage)
            $applicant = Applicant::where('user_id', $user->id)
                         ->orWhere('lrn', $student->lrn ?? null)
                         ->first();

            if (!$applicant && $student) {
                $applicant = Applicant::create([
                    'user_id' => $user->id,
                    'lrn' => $student->lrn,
                    'last_name' => $student->last_name,
                    'first_name' => $student->first_name,
                    'middle_name' => $student->middle_name,
                    'gender' => $student->sex,
                    'sex' => $student->sex,
                    'date_of_birth' => $student->birthdate,
                    'email_address' => $student->email_address,
                    'status' => 'Pending Renewal',
                ]);
            }

            if ($applicant) {
                $existingFiles = is_string($applicant->uploaded_files) 
                                 ? json_decode($applicant->uploaded_files, true) 
                                 : ($applicant->uploaded_files ?? []);

                // Merge new files
                foreach ($fileUrls as $key => $url) {
                    $existingFiles[$key] = $url;
                    // Also update legacy keys for main table display
                    if ($key == 'renewal_sa_info_form') $existingFiles['scholarship_form'] = $url;
                    if ($key == 'renewal_report_card') $existingFiles['report_card'] = $url;
                    if ($key == 'renewal_medical_clearance') $existingFiles['medical_clearance'] = $url;
                }

                $applicant->update([
                    'status' => 'Pending Renewal',
                    'uploaded_files' => json_encode($existingFiles),
                    'document_statuses' => $updatedStatuses,
                    'document_remarks' => array_merge($applicant->document_remarks ?? [], [
                        'renewal_grade_level' => $this->grade_level,
                        'renewal_date' => now()->toDateTimeString(),
                        'is_renewal' => true
                    ])
                ]);
            }

            // 4. (Notification system removed)
            if ($isNewSubmission) {
                // Admins can check the dashboard for 'Pending Renewal' status
            }

            $this->reset(['sa_info_form', 'report_card', 'medical_clearance']);
            $this->mount(); // Refresh data
            session()->flash('message', 'Renewal documents updated and submitted for review!');

        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.continuing-enrollment')->layout('layouts.app');
    }
}