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
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewApplicantNotification;

class ContinuingEnrollment extends Component
{
    use WithFileUploads;

    public $grade_level;
    public $sa_info_form; 
    public $basic_ed_form;
    public $scholarship_agreement;
    public $uniform_measurement;
    public $health_assessment;
    public $passport;
    public $mother_id;
    public $father_id;
    public $guardian_id;

    // Existing Data
    public $applicant;
    public $statuses = [];
    public $remarks = [];
    public $currentFiles = [];

    private function calculateNextGrade($currentGrade)
    {
        $currentGradeNum = (int) filter_var($currentGrade, FILTER_SANITIZE_NUMBER_INT);
        $nextGradeNum = $currentGradeNum < 12 && $currentGradeNum > 0 ? $currentGradeNum + 1 : ($currentGradeNum ?: 7);
        return "Grade " . $nextGradeNum;
    }

    public function mount()
    {
        $user = Auth::user();
        $student = Student::where('nas_student_id', $user->student_id)->first();
        
        $this->applicant = Applicant::where('user_id', $user->id)
                         ->orWhere('lrn', $student->lrn ?? null)
                         ->first();

        $currentGradeLevel = $student->grade_level ?? ($student->section->grade_level ?? 'Grade 7');
        $computedGradeLevel = $this->calculateNextGrade($currentGradeLevel);

        if ($this->applicant) {
            $this->statuses = $this->applicant->document_statuses ?? [];
            $this->remarks = $this->applicant->document_remarks ?? [];
            $this->currentFiles = is_string($this->applicant->uploaded_files) 
                                 ? json_decode($this->applicant->uploaded_files, true) 
                                 : ($this->applicant->uploaded_files ?? []);
            
            $this->grade_level = $this->remarks['renewal_grade_level'] ?? $computedGradeLevel;
        } else {
            $this->grade_level = $computedGradeLevel;
        }
    }

    protected function rules()
    {
        $rules = [];

        $fields = [
            'sa_info_form'          => 'renewal_sa_info_form',
            'basic_ed_form'         => 'renewal_basic_ed_form',
            'scholarship_agreement' => 'renewal_scholarship_agreement',
            'uniform_measurement'   => 'renewal_uniform_measurement',
            'health_assessment'     => 'renewal_health_assessment',
            'passport'              => 'renewal_passport',
            'mother_id'             => 'renewal_mother_id',
            'father_id'             => 'renewal_father_id',
            'guardian_id'           => 'renewal_guardian_id',
        ];

        foreach ($fields as $model => $key) {
            $status = $this->statuses[$key] ?? 'pending';
            $isMissing = !isset($this->currentFiles[$key]) || empty($this->currentFiles[$key]);
            
            $isOptional = in_array($model, ['passport', 'mother_id', 'father_id']);
            
            // Required if missing OR declined, unless optional
            if (!$isOptional && ($isMissing || $status === 'declined')) {
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

            $uploadFields = [
               'sa_info_form' => 'renewal_sa_info_form',
               'basic_ed_form' => 'renewal_basic_ed_form',
               'scholarship_agreement' => 'renewal_scholarship_agreement',
               'uniform_measurement' => 'renewal_uniform_measurement',
               'health_assessment' => 'renewal_health_assessment',
               'passport' => 'renewal_passport',
               'mother_id' => 'renewal_mother_id',
               'father_id' => 'renewal_father_id',
               'guardian_id' => 'renewal_guardian_id',
            ];

            foreach ($uploadFields as $model => $key) {
                if ($this->$model) {
                    $fileUrls[$key] = $uploadApi->upload($this->$model->getRealPath(), ['folder' => 'nas_requirements'])['secure_url'];
                    $updatedStatuses[$key] = 'pending';
                    $isNewSubmission = true;
                }
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
                    if ($key == 'renewal_health_assessment') $existingFiles['medical_clearance'] = $url;
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

            // 4. Admin Notification
            if ($isNewSubmission && $applicant) {
                $admins = User::whereIn('role', ['admin', 'registrar'])->get();
                $message = "Continuing Enrollment renewal submitted by: {$applicant->first_name} {$applicant->last_name} ({$this->grade_level})";
                Notification::send($admins, new NewApplicantNotification($applicant, $message));
            }

            $this->reset(array_keys($uploadFields));
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