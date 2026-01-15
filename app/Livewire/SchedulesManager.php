<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Staff;

class SchedulesManager extends Component
{
    public $schedule_id;
    public $subject_id;
    public $section_id;
    public $staff_id;
    public $day;
    public $time_start;
    public $time_end;
    public $room;

    public $isEditing = false;
    public $isCreating = false;

    // Validation rules
    protected $rules = [
        'subject_id' => 'required|exists:subjects,id',
        'section_id' => 'required|exists:sections,id',
        'staff_id'   => 'required|exists:staff,id',
        'day'        => 'required|string|max:255',
        'time_start' => 'required',
        'time_end'   => 'required',
        'room'       => 'nullable|string|max:255',
    ];

    public function render()
    {
        // Kunin ang schedules at related models
        $schedules = Schedule::with(['subject', 'section', 'staff'])->latest()->get();
        
        // Kunin ang data para sa dropdowns
        $subjects = Subject::all();
        $sections = Section::all();
        $staff    = Staff::all(); // Assuming 'Staff' model is used for teachers

        return view('livewire.schedules-manager', [
            'schedules' => $schedules,
            'subjects'  => $subjects,
            'sections'  => $sections,
            'staff'     => $staff,
        ]);
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

        Schedule::create([
            'subject_id' => $this->subject_id,
            'section_id' => $this->section_id,
            'staff_id'   => $this->staff_id,
            'day'        => $this->day,
            'time_start' => $this->time_start,
            'time_end'   => $this->time_end,
            'room'       => $this->room,
        ]);

        session()->flash('success', 'Schedule created successfully.');
        $this->resetInputFields();
        $this->isCreating = false;
        $this->dispatch('toggle-add-button', show: true);
    }

    // 👇 SIGNAL: Hide button kapag nag-edit
    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);

        $this->schedule_id = $id;
        $this->subject_id  = $schedule->subject_id;
        $this->section_id  = $schedule->section_id;
        $this->staff_id    = $schedule->staff_id;
        $this->day         = $schedule->day;
        $this->time_start  = $schedule->time_start;
        $this->time_end    = $schedule->time_end;
        $this->room        = $schedule->room;

        $this->isEditing = true;
        $this->isCreating = false;
        $this->dispatch('toggle-add-button', show: false);
    }

    // 👇 SIGNAL: Show button kapag tapos na mag-update
    public function update()
    {
        $this->validate();

        $schedule = Schedule::findOrFail($this->schedule_id);
        $schedule->update([
            'subject_id' => $this->subject_id,
            'section_id' => $this->section_id,
            'staff_id'   => $this->staff_id,
            'day'        => $this->day,
            'time_start' => $this->time_start,
            'time_end'   => $this->time_end,
            'room'       => $this->room,
        ]);

        session()->flash('success', 'Schedule updated successfully.');
        $this->resetInputFields();
        $this->isEditing = false;
        $this->dispatch('toggle-add-button', show: true);
    }

    public function delete($id)
    {
        Schedule::find($id)->delete();
        session()->flash('success', 'Schedule deleted successfully.');
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
        $this->subject_id = '';
        $this->section_id = '';
        $this->staff_id   = '';
        $this->day        = '';
        $this->time_start = '';
        $this->time_end   = '';
        $this->room       = '';
        $this->schedule_id = null;
        $this->resetErrorBag();
    }
}