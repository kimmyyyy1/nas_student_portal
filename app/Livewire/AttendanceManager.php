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
    public $date;

    public function mount()
    {
        // Default date ay ngayon
        $this->date = now()->format('Y-m-d');
    }

    // 👇 1. LOAD THE VIEW
    public function render()
    {
        // Kunin ang sections kasama ang adviser at bilang ng students
        $sections = Section::with('adviser')->withCount('students')->get();
        
        return view('livewire.attendance-manager', [
            'sections' => $sections
        ]);
    }

    // 👇 2. OPEN ATTENDANCE SHEET
    public function openAttendanceSheet($sectionId)
    {
        // Eager load students para iwas query loop
        $this->selectedSection = Section::with(['adviser', 'students'])->findOrFail($sectionId);
        
        $this->students = $this->selectedSection->students ?? [];
        
        // I-load ang data (Check kung may record na)
        $this->loadAttendanceData();

        $this->view = 'sheet';

        // Update Header
        $this->dispatch('update-header', 
            title: 'Attendance: ' . $this->selectedSection->section_name, 
            showBack: true
        );
    }

    // 👇 3. HANDLE DATE CHANGE (Auto-reload pag nagbago ang date sa picker)
    public function updatedDate()
    {
        // Kung nasa 'sheet' view tayo at may selected section, i-reload ang data
        if ($this->view === 'sheet' && $this->selectedSection) {
            $this->loadAttendanceData();
        }
    }

    // 👇 4. HELPER: LOGIC TO LOAD ATTENDANCE STATUS
    public function loadAttendanceData()
    {
        $this->attendance = [];

        // Kunin ang existing records sa DB para sa Section at Date na ito
        // Gamit ang 'pluck' para maging array na [student_id => status] agad
        $existingRecords = Attendance::where('section_id', $this->selectedSection->id)
                            ->where('date', $this->date)
                            ->pluck('status', 'student_id');

        foreach($this->students as $student) {
            // Check kung may record na sa DB based sa ID ng student
            if (isset($existingRecords[$student->id])) {
                // Kung meron, gamitin ang nasa DB
                $this->attendance[$student->id] = $existingRecords[$student->id];
            } else {
                // Kung wala, default to 'present'
                $this->attendance[$student->id] = 'present';
            }
        }
    }

    // 👇 5. GO BACK TO GRID
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

    // 👇 6. SAVE ATTENDANCE
    public function saveAttendance()
    {
        $this->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        foreach ($this->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    // Search criteria (Unique combination)
                    'student_id' => $studentId,
                    'section_id' => $this->selectedSection->id,
                    'date' => $this->date,
                ],
                [
                    // Values to update or create
                    'status' => $status,
                    
                    // 👇 NOTE: Naka-comment out muna ito para mawala ang error.
                    // Kung nag-run ka na ng migration (Option 1), pwede mo itong tanggalan ng comment.
                    // 'recorded_by' => auth()->id(), 
                ]
            );
        }

        session()->flash('success', 'Attendance for ' . $this->date . ' saved successfully!');
    }
}