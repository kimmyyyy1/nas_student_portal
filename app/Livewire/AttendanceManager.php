<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Section;
use App\Models\Student;
use App\Models\Attendance; // 👇 Importante: Siguraduhin na na-import ang Attendance Model

class AttendanceManager extends Component
{
    public $view = 'grid'; // 'grid' or 'sheet'
    public $selectedSection = null;
    public $students = [];
    public $attendance = []; // Dito natin isisave ang status [student_id => status]
    public $date;

    public function mount()
    {
        $this->date = now()->format('Y-m-d'); // Default date is today
    }

    // 👇 1. LOAD THE VIEW
    public function render()
    {
        $sections = Section::with('adviser')->withCount('students')->get();
        
        return view('livewire.attendance-manager', [
            'sections' => $sections
        ]);
    }

    // 👇 2. OPEN ATTENDANCE SHEET
    public function openAttendanceSheet($sectionId)
    {
        $this->selectedSection = Section::with('adviser')->findOrFail($sectionId);
        
        // Kunin ang students ng section
        $this->students = $this->selectedSection->students ?? [];

        // Reset attendance array
        $this->attendance = [];

        // Initialize default attendance status (Pwede mong baguhin logic nito later para mag-load ng existing data)
        foreach($this->students as $student) {
            // Check kung may record na for TODAY, kung wala, default to 'present'
            // Sa ngayon, i-default muna natin sa 'present' para simple
            $this->attendance[$student->id] = 'present'; 
        }

        $this->view = 'sheet';

        // Update Header para magbago ang Title at lumabas ang Back button
        $this->dispatch('update-header', 
            title: 'Attendance: ' . $this->selectedSection->section_name, 
            showBack: true
        );
    }

    // 👇 3. GO BACK TO GRID
    public function goBack()
    {
        $this->view = 'grid';
        $this->selectedSection = null;
        $this->students = [];
        $this->attendance = [];

        // Reset Header
        $this->dispatch('update-header', 
            title: 'Attendance Checking', 
            showBack: false
        );
    }

    // 👇 4. SAVE ATTENDANCE (REAL DATABASE LOGIC)
    public function saveAttendance()
    {
        // Validation
        $this->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        // Loop sa lahat ng attendance data at i-save/update sa database
        foreach ($this->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    // Hanapin ang record gamit ang mga ito (Unique constraint)
                    'student_id' => $studentId,
                    'section_id' => $this->selectedSection->id,
                    'date' => $this->date,
                ],
                [
                    // I-update o i-create gamit ang values na ito
                    'status' => $status,
                    'recorded_by' => auth()->id(), // Optional: Kung sino ang nag-record
                ]
            );
        }

        // Magpakita ng success message
        session()->flash('success', 'Attendance for ' . $this->date . ' saved successfully!');
    }
}