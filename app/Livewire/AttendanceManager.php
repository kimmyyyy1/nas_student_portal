<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Section;
use App\Models\Student;
use App\Models\Attendance;

class AttendanceManager extends Component
{
    public $view = 'grid'; // 'grid' or 'sheet'
    public $selectedSection = null;
    public $students = [];
    
    public $attendance = []; // [student_id => status]
    public $remarks = [];    // [student_id => reason] 👇 NEW: Para sa text box

    public $date;

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
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
        $this->selectedSection = Section::with(['adviser', 'students'])->findOrFail($sectionId);
        
        $this->students = $this->selectedSection->students ?? [];
        
        // I-load ang data
        $this->loadAttendanceData();

        $this->view = 'sheet';

        $this->dispatch('update-header', 
            title: 'Attendance: ' . $this->selectedSection->section_name, 
            showBack: true
        );
    }

    // 👇 3. HANDLE DATE CHANGE
    public function updatedDate()
    {
        if ($this->view === 'sheet' && $this->selectedSection) {
            $this->loadAttendanceData();
        }
    }

    // 👇 4. LOAD DATA (UPDATED: Kasama na ang Remarks)
    public function loadAttendanceData()
    {
        $this->attendance = [];
        $this->remarks = []; // Reset remarks

        // Kunin ang records (Get instead of Pluck para makuha pati remarks)
        $records = Attendance::where('section_id', $this->selectedSection->id)
                            ->where('date', $this->date)
                            ->get(); 

        // I-map ang database records sa ating public arrays
        foreach ($records as $record) {
            $this->attendance[$record->student_id] = $record->status;
            $this->remarks[$record->student_id] = $record->remarks; // Load existing remarks
        }

        // Set defaults para sa mga walang record
        foreach($this->students as $student) {
            if (!isset($this->attendance[$student->id])) {
                $this->attendance[$student->id] = 'present';
            }
            // Siguraduhing may key ang remarks array kahit null
            if (!isset($this->remarks[$student->id])) {
                $this->remarks[$student->id] = ''; 
            }
        }
    }

    // 👇 5. GO BACK
    public function goBack()
    {
        $this->view = 'grid';
        $this->selectedSection = null;
        $this->students = [];
        $this->attendance = [];
        $this->remarks = [];

        $this->dispatch('update-header', 
            title: 'Attendance Checking', 
            showBack: false
        );
    }

    // 👇 6. SAVE ATTENDANCE (UPDATED: Saves Remarks)
    public function saveAttendance()
    {
        $this->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'remarks.*' => 'nullable|string|max:255', // Validation para sa remarks
        ]);

        foreach ($this->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'section_id' => $this->selectedSection->id,
                    'date' => $this->date,
                ],
                [
                    'status' => $status,
                    // I-save ang remarks (o null kung wala)
                    'remarks' => $this->remarks[$studentId] ?? null,
                    
                    // 'recorded_by' => auth()->id(), // Pwede mo na i-uncomment ito kung na-fix na ang migration
                ]
            );
        }

        session()->flash('success', 'Attendance for ' . $this->date . ' saved successfully!');
    }
}