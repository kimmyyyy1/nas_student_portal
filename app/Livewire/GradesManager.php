<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Section;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Schedule;
// 👇 1. ADD THESE IMPORTS
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class GradesManager extends Component
{
    public $view = 'grid'; 
    public $selectedSection = null;
    public $students = [];
    public $schedules = [];
    public $selectedScheduleId = null;

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
        
        $this->schedules = Schedule::with('subject')
                                   ->where('section_id', $sectionId)
                                   ->get();

        $this->selectedScheduleId = null; 
        $this->gradesData = [];
        $this->studentStatus = [];

        $this->view = 'sheet';

        $this->dispatch('update-header', 
            title: 'Grading: ' . $this->selectedSection->section_name, 
            showBack: true
        );
    }

    public function updatedSelectedScheduleId($scheduleId)
    {
        $this->gradesData = [];
        $this->studentStatus = [];

        if (!$scheduleId) return;

        foreach ($this->students as $student) {
            
            $this->studentStatus[$student->id] = $student->promotion_status ?? '';

            $grade = Grade::where('student_id', $student->id)
                          ->where('schedule_id', $scheduleId)
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

                // Logic para i-convert ang empty string sa NULL
                Grade::updateOrCreate(
                    [
                        'student_id' => $sId,
                        'schedule_id' => $this->selectedScheduleId, 
                    ],
                    [
                        'first_quarter'  => ($data['q1'] === '' || $data['q1'] === null) ? null : $data['q1'],
                        'second_quarter' => ($data['q2'] === '' || $data['q2'] === null) ? null : $data['q2'],
                        'third_quarter'  => ($data['q3'] === '' || $data['q3'] === null) ? null : $data['q3'],
                        'fourth_quarter' => ($data['q4'] === '' || $data['q4'] === null) ? null : $data['q4'],
                        'final_grade'    => ($data['final'] === '' || $data['final'] === null) ? null : $data['final'],
                    ]
                );
            }
        }

        // 👇 2. ADD THIS LOGIC: RECORD TO ACTIVITY LOG
        $subjectName = 'Unknown Subject';
        // Hanapin ang subject name para maganda tingnan sa log
        if($this->selectedScheduleId) {
            $schedule = Schedule::with('subject')->find($this->selectedScheduleId);
            $subjectName = $schedule->subject->subject_name ?? 'Subject';
        }

        ActivityLog::create([
            'user_id'     => Auth::id(), // Kung sino ang naka-login (Registrar/Teacher)
            'action'      => 'Updated Grades',
            'description' => "Updated $subjectName grades for " . $this->selectedSection->section_name,
        ]);

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