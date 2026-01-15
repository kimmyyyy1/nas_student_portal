<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Section;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Schedule; // 👇 Make sure imported ito

class GradesManager extends Component
{
    public $view = 'grid'; 
    public $selectedSection = null;
    public $students = [];
    public $schedules = []; // 👇 Listahan ng subjects ng section
    public $selectedScheduleId = null; // 👇 Ang pipiliing subject

    public $gradesData = [];
    public $studentStatus = [];

    public function render()
    {
        $sections = Section::with('adviser')->withCount('students')->get();
        return view('livewire.grades-manager', ['sections' => $sections]);
    }

    public function openGradingSheet($sectionId)
    {
        $this->selectedSection = Section::with('adviser')->findOrFail($sectionId);
        $this->students = $this->selectedSection->students ?? []; 
        
        // 👇 Load Schedules/Subjects para sa Section na ito
        // Assuming may 'schedules' relationship ang Section model at may 'subject' relationship ang Schedule
        $this->schedules = Schedule::with('subject')
                                   ->where('section_id', $sectionId)
                                   ->get();

        // Reset data
        $this->selectedScheduleId = null; 
        $this->gradesData = [];
        $this->studentStatus = [];

        // Note: Hindi muna tayo maglo-load ng grades dito kasi wala pang pinipiling Subject.
        // Ililipat natin ang loading logic sa 'updatedSelectedScheduleId'.

        $this->view = 'sheet';

        $this->dispatch('update-header', 
            title: 'Grading: ' . $this->selectedSection->section_name, 
            showBack: true
        );
    }

    // 👇 BAGONG FUNCTION: Tumatakbo pag namili ka ng Subject sa Dropdown
    public function updatedSelectedScheduleId($scheduleId)
    {
        // Reset grades data
        $this->gradesData = [];
        $this->studentStatus = [];

        if (!$scheduleId) return;

        // Load Grades for the selected Subject/Schedule
        foreach ($this->students as $student) {
            
            $this->studentStatus[$student->id] = $student->promotion_status ?? '';

            // 👇 Hanapin ang grade gamit ang Student ID at Schedule ID
            $grade = Grade::where('student_id', $student->id)
                          ->where('schedule_id', $scheduleId) // Important fix!
                          ->first();

            if ($grade) {
                $this->gradesData[$student->id] = [
                    'q1' => $grade->first_quarter,
                    'q2' => $grade->second_quarter,
                    'q3' => $grade->third_quarter,
                    'q4' => $grade->fourth_quarter,
                    'final' => $grade->final_grade,
                ];
            } else {
                $this->gradesData[$student->id] = [
                    'q1' => null, 'q2' => null, 'q3' => null, 'q4' => null, 'final' => null
                ];
            }
        }
    }

    public function updatedGradesData($value, $key)
    {
        $parts = explode('.', $key);
        $studentId = $parts[0];

        $q1 = $this->gradesData[$studentId]['q1'] ?? 0;
        $q2 = $this->gradesData[$studentId]['q2'] ?? 0;
        $q3 = $this->gradesData[$studentId]['q3'] ?? 0;
        $q4 = $this->gradesData[$studentId]['q4'] ?? 0;

        $total = (float)$q1 + (float)$q2 + (float)$q3 + (float)$q4;
        
        if($q1 && $q2 && $q3 && $q4) {
            $this->gradesData[$studentId]['final'] = round($total / 4); 
        }
    }

    public function saveGrades()
    {
        // 👇 Validation: Dapat may napiling subject
        if (!$this->selectedScheduleId) {
            session()->flash('error', 'Please select a Subject first!');
            return;
        }

        foreach ($this->students as $student) {
            $sId = $student->id;
            
            if(isset($this->gradesData[$sId])) {
                $data = $this->gradesData[$sId];

                if (isset($this->studentStatus[$sId])) {
                    $student->promotion_status = $this->studentStatus[$sId];
                    $student->save();
                }

                Grade::updateOrCreate(
                    [
                        // 👇 Search Criteria: Student + Schedule (Subject)
                        'student_id' => $sId,
                        'schedule_id' => $this->selectedScheduleId, 
                    ],
                    [
                        'first_quarter'  => $data['q1'],
                        'second_quarter' => $data['q2'],
                        'third_quarter'  => $data['q3'],
                        'fourth_quarter' => $data['q4'],
                        'final_grade'    => $data['final'],
                    ]
                );
            }
        }

        session()->flash('success', 'Grades saved successfully!');
    }

    public function goBack()
    {
        $this->view = 'grid';
        $this->selectedSection = null;
        $this->students = [];
        $this->gradesData = [];
        $this->schedules = [];
        $this->selectedScheduleId = null;

        $this->dispatch('update-header', title: 'Select Class to Grade', showBack: false);
    }
}