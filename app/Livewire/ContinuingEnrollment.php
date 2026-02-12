<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Applicant; // Note: Kung Student model ang gamit mo sa old students, palitan ito
use Illuminate\Support\Facades\Auth;

class ContinuingEnrollment extends Component
{
    use WithFileUploads;

    public $grade_level;
    public $sa_info_form; // ⚡ BAGO: Variable para sa SA Info Form
    public $report_card;
    public $medical_clearance;

    protected $rules = [
        'grade_level'       => 'required|string',
        'sa_info_form'      => 'required|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB Max
        'report_card'       => 'required|mimes:jpg,jpeg,png,pdf|max:5120',
        'medical_clearance' => 'required|mimes:jpg,jpeg,png,pdf|max:5120',
    ];

    public function submitEnrollment()
    {
        $this->validate();

        // 1. Upload files sa 'public/requirements' folder
        $saInfoPath     = $this->sa_info_form->store('requirements', 'public');
        $reportCardPath = $this->report_card->store('requirements', 'public');
        $medicalPath    = $this->medical_clearance->store('requirements', 'public');

        // 2. I-save or i-update sa database ang student record
        // Hahanapin natin ang record niya gamit ang user_id
        $student = Applicant::where('user_id', Auth::id())->first();

        if ($student) {
            
            // Kukunin natin ang mga luma niyang files para hindi mabura (like Birth Cert, Kukkiwon, etc.)
            $existingFiles = is_string($student->uploaded_files) 
                             ? json_decode($student->uploaded_files, true) 
                             : ($student->uploaded_files ?? []);

            // I-override lang natin yung 3 files na bago
            $existingFiles['sa_info_form']      = 'storage/' . $saInfoPath;
            $existingFiles['report_card']       = 'storage/' . $reportCardPath;
            $existingFiles['medical_clearance'] = 'storage/' . $medicalPath;

            // Update the existing record
            $student->update([
                'status' => 'Pending Renewal', // Status para alam ni Admin na renewal ito
                'uploaded_files' => json_encode($existingFiles) // Ise-save ang pinagsamang luma at bagong files
            ]);
            
        }

        // 3. Clear form at magpakita ng Success Message
        $this->reset(['grade_level', 'sa_info_form', 'report_card', 'medical_clearance']);
        
        session()->flash('message', 'Your Student-Athlete Information Form and other renewal documents have been successfully submitted! Please wait for the Registrar\'s verification.');
    }

    public function render()
    {
        return view('livewire.continuing-enrollment')->layout('layouts.app');
    }
}