<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Section;
use App\Models\Staff;

class SectionsManager extends Component
{
    public $section_id;
    public $grade_level;
    public $section_name;
    public $adviser_id;
    public $room_number;

    public $isEditing = false;
    public $isCreating = false;

    protected $rules = [
        'grade_level' => 'required',
        'section_name' => 'required|string|max:255',
        'adviser_id' => 'nullable|exists:staff,id',
        'room_number' => 'nullable|string|max:255',
    ];

    public function render()
    {
        $sections = Section::with('adviser')->latest()->get();
        $teachers = Staff::all();

        return view('livewire.sections-manager', [
            'sections' => $sections,
            'teachers' => $teachers,
        ]);
    }

    // 👇 UPDATED: Nagdadagdag tayo ng dispatch('hide-add-button')
    public function create()
    {
        $this->resetInputFields();
        $this->isCreating = true;
        $this->isEditing = false;
        $this->dispatch('hide-add-button'); // Magtago ang button sa taas
    }

    // 👇 UPDATED: Nagdadagdag tayo ng dispatch('show-add-button')
    public function store()
    {
        $this->validate();

        Section::create([
            'grade_level' => $this->grade_level,
            'section_name' => $this->section_name,
            'adviser_id' => $this->adviser_id,
            'room_number' => $this->room_number,
        ]);

        session()->flash('success', 'Section created successfully.');
        $this->resetInputFields();
        $this->isCreating = false;
        $this->dispatch('show-add-button'); // Palabasin ulit ang button
    }

    // 👇 UPDATED: Hide button pag nag-edit
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
        $this->dispatch('hide-add-button'); // Magtago ang button
    }

    // 👇 UPDATED: Show button pag tapos na mag-update
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

        session()->flash('success', 'Section updated successfully.');
        $this->resetInputFields();
        $this->isEditing = false;
        $this->dispatch('show-add-button'); // Palabasin ulit ang button
    }

    public function delete($id)
    {
        Section::find($id)->delete();
        session()->flash('success', 'Section deleted successfully.');
    }

    // 👇 UPDATED: Show button pag nag-cancel
    public function cancel()
    {
        $this->resetInputFields();
        $this->isCreating = false;
        $this->isEditing = false;
        $this->dispatch('show-add-button'); // Palabasin ulit ang button
    }

    private function resetInputFields()
    {
        $this->grade_level = '';
        $this->section_name = '';
        $this->adviser_id = '';
        $this->room_number = '';
        $this->section_id = null;
        $this->resetErrorBag();
    }
}