<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Section;
use App\Models\Student;
use App\Models\Grade; // 👇 Importante: Siguraduhing may Grade Model ka

class GradesManager extends Component
{
    public $view = 'grid'; // 'grid' or 'sheet'
    public $selectedSection = null;
    public $students = [];

    // 👇 Dito natin ilalagay ang inputs ng user
    public $gradesData = []; // [student_id => [q1, q2, q3, q4, final]]
    public $studentStatus = []; // [student_id => status]

    // 👇 1. LOAD THE VIEW
    public function render()
    {
        $sections = Section::with('adviser')->withCount('students')->get();

        return view('livewire.grades-manager', [
            'sections' => $sections
        ]);
    }

    // 👇 2. OPEN GRADING SHEET (Switch to Table & Load Data)
    public function openGradingSheet($sectionId)
    {
        $this->selectedSection = Section::with('adviser')->findOrFail($sectionId);
        
        // Kunin ang students
        $this->students = $this->selectedSection->students ?? []; 

        // 🔄 RESET MUNA ANG ARRAYS
        $this->gradesData = [];
        $this->studentStatus = [];

        // 📥 LOAD DATA FROM DATABASE
        foreach ($this->students as $student) {
            
            // A. Load Status (Promoted/Retained) galing sa students table
            $this->studentStatus[$student->id] = $student->promotion_status ?? '';

            // B. Load Grades galing sa grades table
            // Note: Kung may Subject ID ka, dagdagan mo ng ->where('subject_id', $id)
            $grade = Grade::where('student_id', $student->id)->first();

            if ($grade) {
                $this->gradesData[$student->id] = [
                    'q1' => $grade->first_quarter,
                    'q2' => $grade->second_quarter,
                    'q3' => $grade->third_quarter,
                    'q4' => $grade->fourth_quarter,
                    'final' => $grade->final_grade,
                ];
            } else {
                // Default values kung wala pang grade
                $this->gradesData[$student->id] = [
                    'q1' => null, 'q2' => null, 'q3' => null, 'q4' => null, 'final' => null
                ];
            }
        }

        $this->view = 'sheet';

        // Update Header
        $this->dispatch('update-header', 
            title: 'Grading: ' . $this->selectedSection->grade_level . ' - ' . $this->selectedSection->section_name, 
            showBack: true
        );
    }

    // 👇 3. AUTO-CALCULATE FINAL GRADE (Real-time)
    // Ito ang magic function ni Livewire. Automatic itong tumatakbo pag nag-type ka.
    public function updatedGradesData($value, $key)
    {
        // Ang structure ng $key ay "student_id.q1"
        $parts = explode('.', $key);
        $studentId = $parts[0];

        // Kunin ang current values
        $q1 = $this->gradesData[$studentId]['q1'] ?? 0;
        $q2 = $this->gradesData[$studentId]['q2'] ?? 0;
        $q3 = $this->gradesData[$studentId]['q3'] ?? 0;
        $q4 = $this->gradesData[$studentId]['q4'] ?? 0;

        // Convert to float para safe
        $total = (float)$q1 + (float)$q2 + (float)$q3 + (float)$q4;
        
        // Mag-compute lang kung may laman na kahit isa, o mas maganda kung kumpleto 4
        if($q1 && $q2 && $q3 && $q4) {
            $average = $total / 4;
            // Round off to whole number or decimal (depende sa gusto mo)
            $this->gradesData[$studentId]['final'] = round($average); 
        }
    }

    // 👇 4. SAVE DATA TO DATABASE
    public function saveGrades()
    {
        foreach ($this->students as $student) {
            $sId = $student->id;
            
            // Siguraduhing may data bago i-save
            if(isset($this->gradesData[$sId])) {
                $data = $this->gradesData[$sId];

                // A. Update Student Status (Promoted/Retained)
                if (isset($this->studentStatus[$sId])) {
                    $student->promotion_status = $this->studentStatus[$sId];
                    $student->save();
                }

                // B. Update Grades Table
                Grade::updateOrCreate(
                    [
                        'student_id' => $sId,
                        // 'subject_id' => 1, // Uncomment at lagyan ng value kung per subject
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

        session()->flash('success', 'Grades and Status saved successfully!');
    }

    // 👇 5. GO BACK TO GRID
    public function goBack()
    {
        $this->view = 'grid';
        $this->selectedSection = null;
        $this->students = [];
        $this->gradesData = []; // Clear memory
        $this->studentStatus = [];

        $this->dispatch('update-header', 
            title: 'Select Class to Grade', 
            showBack: false
        );
    }
}