<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Section;
use App\Models\Student; // Siguraduhin na may Student model ka

class GradesManager extends Component
{
    public $view = 'grid'; // 'grid' or 'sheet'
    public $selectedSection = null;
    public $students = [];

    // 👇 1. LOAD THE VIEW
    public function render()
    {
        // Kunin lahat ng sections
        $sections = Section::with('adviser')->withCount('students')->get();

        return view('livewire.grades-manager', [
            'sections' => $sections
        ]);
    }

    // 👇 2. OPEN GRADING SHEET (Switch to Table)
    public function openGradingSheet($sectionId)
    {
        $this->selectedSection = Section::with('adviser')->findOrFail($sectionId);
        
        // Kunin ang mga estudyante ng section na ito
        // Assuming na may relationship na 'students' sa Section model
        $this->students = $this->selectedSection->students ?? []; 

        $this->view = 'sheet';

        // Utusan ang Header na mag-iba ng Title at magpakita ng Back Button
        $this->dispatch('update-header', 
            title: 'Grading: ' . $this->selectedSection->grade_level . ' - ' . $this->selectedSection->section_name, 
            showBack: true
        );
    }

    // 👇 3. GO BACK TO GRID (Switch to Cards)
    public function goBack()
    {
        $this->view = 'grid';
        $this->selectedSection = null;
        $this->students = [];

        // Ibalik sa original Header
        $this->dispatch('update-header', 
            title: 'Select Class to Grade', 
            showBack: false
        );
    }
}