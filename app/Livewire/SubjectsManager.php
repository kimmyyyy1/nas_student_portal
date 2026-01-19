<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subject;

class SubjectsManager extends Component
{
    public $subject_id;
    public $subject_code;
    public $subject_name;
    public $description;

    public $isEditing = false;
    public $isCreating = false;

    // Validation rules
    protected function rules()
    {
        return [
            'subject_code' => 'required|unique:subjects,subject_code,' . $this->subject_id,
            'subject_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public function render()
    {
        $subjects = Subject::latest()->get();
        return view('livewire.subjects-manager', compact('subjects'));
    }

    // 👇 SIGNAL: Hide button kapag nag-create
    public function create()
    {
        $this->resetInputFields();
        $this->isCreating = true;
        $this->isEditing = false;
        $this->dispatch('toggle-add-button', show: false);
    }

    // 👇 SIGNAL: Show button kapag tapos na mag-save
    public function store()
    {
        $this->validate();

        Subject::create([
            'subject_code' => $this->subject_code,
            'subject_name' => $this->subject_name,
            'description' => $this->description,
        ]);

        session()->flash('success', 'Subject created successfully.');
        $this->resetInputFields();
        $this->isCreating = false;
        $this->dispatch('toggle-add-button', show: true);
    }

    // 👇 SIGNAL: Hide button kapag nag-edit
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);

        $this->subject_id = $id;
        $this->subject_code = $subject->subject_code;
        $this->subject_name = $subject->subject_name;
        $this->description = $subject->description;

        $this->isEditing = true;
        $this->isCreating = false;
        $this->dispatch('toggle-add-button', show: false);
    }

    // 👇 SIGNAL: Show button kapag tapos na mag-update
    public function update()
    {
        $this->validate();

        $subject = Subject::findOrFail($this->subject_id);
        $subject->update([
            'subject_code' => $this->subject_code,
            'subject_name' => $this->subject_name,
            'description' => $this->description,
        ]);

        session()->flash('success', 'Subject updated successfully.');
        $this->resetInputFields();
        $this->isEditing = false;
        $this->dispatch('toggle-add-button', show: true);
    }

    public function delete($id)
    {
        Subject::find($id)->delete();
        session()->flash('success', 'Subject deleted successfully.');
    }

    // 👇 SIGNAL: Show button kapag nag-cancel
    public function cancel()
    {
        $this->resetInputFields();
        $this->isCreating = false;
        $this->isEditing = false;
        $this->dispatch('toggle-add-button', show: true);
    }

    private function resetInputFields()
    {
        $this->subject_code = '';
        $this->subject_name = '';
        $this->description = '';
        $this->subject_id = null;
        $this->resetErrorBag();
    }
}