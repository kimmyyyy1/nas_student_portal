<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Section;
use App\Models\Staff; // Siguraduhin na tama ang model name mo para sa Teachers/Staff

class SectionsManager extends Component
{
    // Variables para sa Form Inputs
    public $section_id;
    public $grade_level;
    public $section_name;
    public $adviser_id;
    public $room_number;

    // Variable para kontrolin kung Table ba o Form ang nakikita
    public $isEditing = false;
    public $isCreating = false;

    // Validation Rules
    protected $rules = [
        'grade_level' => 'required',
        'section_name' => 'required|string|max:255',
        'adviser_id' => 'nullable|exists:staff,id', // Palitan ang 'staff' kung iba ang table name
        'room_number' => 'nullable|string|max:255',
    ];

    // 👇 1. RENDER (Ito ang nagpapakita ng listahan)
    public function render()
    {
        // Kunin ang lahat ng Sections at Teachers
        $sections = Section::with('adviser')->latest()->get();
        // Assuming na ang role ng teacher ay 'Teacher' o kunin lahat ng staff
        $teachers = Staff::all(); 

        return view('livewire.sections-manager', [
            'sections' => $sections,
            'teachers' => $teachers,
        ]);
    }

    // 👇 2. SHOW CREATE FORM
    public function create()
    {
        $this->resetInputFields();
        $this->isCreating = true;
        $this->isEditing = false;
    }

    // 👇 3. STORE NEW SECTION
    public function store()
    {
        $this->validate();

        Section::create([
            'grade_level' => $this->grade_level,
            'section_name' => $this->section_name,
            'adviser_id' => $this->adviser_id,
            'room_number' => $this->room_number,
        ]);

        session()->flash('message', 'Section created successfully.');
        $this->resetInputFields();
        $this->isCreating = false; // Balik sa Table
    }

    // 👇 4. SHOW EDIT FORM
    public function edit($id)
    {
        $section = Section::findOrFail($id);

        $this->section_id = $id;
        $this->grade_level = $section->grade_level;
        $this->section_name = $section->section_name;
        $this->adviser_id = $section->adviser_id;
        $this->room_number = $section->room_number;

        $this->isEditing = true;
        $this->isCreating = false;
    }

    // 👇 5. UPDATE SECTION
    public function update()
    {
        $this->validate();

        $section = Section::findOrFail($this->section_id);
        $section->update([
            'grade_level' => $this->grade_level,
            'section_name' => $this->section_name,
            'adviser_id' => $this->adviser_id,
            'room_number' => $this->room_number,
        ]);

        session()->flash('message', 'Section updated successfully.');
        $this->resetInputFields();
        $this->isEditing = false; // Balik sa Table
    }

    // 👇 6. DELETE SECTION
    public function delete($id)
    {
        Section::find($id)->delete();
        session()->flash('message', 'Section deleted successfully.');
    }

    // 👇 7. CANCEL / BACK
    public function cancel()
    {
        $this->resetInputFields();
        $this->isCreating = false;
        $this->isEditing = false;
    }

    // Helper para linisin ang inputs
    private function resetInputFields()
    {
        $this->grade_level = '';
        $this->section_name = '';
        $this->adviser_id = '';
        $this->room_number = '';
        $this->section_id = null;
    }
}