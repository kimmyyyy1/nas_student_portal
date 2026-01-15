<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Section;
use App\Models\Student;

class AttendanceManager extends Component
{
    public $view = 'grid'; // 'grid' or 'sheet'
    public $selectedSection = null;
    public $students = [];
    public $attendance = []; // Dito natin isisave ang attendance data [student_id => status]
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

        // Initialize default attendance (optional: pwede ring blank)
        foreach($this->students as $student) {
            $this->attendance[$student->id] = 'present'; 
        }

        $this->view = 'sheet';

        // Update Header
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

        // Reset Header
        $this->dispatch('update-header', 
            title: 'Attendance Checking', 
            showBack: false
        );
    }

    // 👇 4. SAVE ATTENDANCE (Placeholder Logic)
    public function saveAttendance()
    {
        // Dito mo ilalagay ang logic para i-save sa database
        // Example: Attendance::create(...)
        
        session()->flash('success', 'Attendance for ' . $this->date . ' saved successfully!');
    }
}