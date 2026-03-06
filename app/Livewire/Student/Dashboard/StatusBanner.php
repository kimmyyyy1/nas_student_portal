<?php

namespace App\Livewire\Student\Dashboard;

use Livewire\Component;
use App\Models\Student;

class StatusBanner extends Component
{
    public $studentId;
    public $isEnrollmentOpen;

    public function mount($studentId, $isEnrollmentOpen)
    {
        $this->studentId = $studentId;
        $this->isEnrollmentOpen = $isEnrollmentOpen;
    }

    public function render()
    {
        // Fetch the latest student record dynamically right here on every poll
        $student = Student::find($this->studentId);

        $enrollmentStartDate = \App\Models\SystemSetting::where('setting_key', 'enrollment_start_date')->value('setting_value') ?? date('Y') . '-06-01';
        $enrollmentEndDate   = \App\Models\SystemSetting::where('setting_key', 'enrollment_end_date')->value('setting_value') ?? date('Y') . '-08-31';
        
        $displayStartDate = date('F j, Y', strtotime($enrollmentStartDate));
        $displayEndDate   = date('F j, Y', strtotime($enrollmentEndDate));

        return view('livewire.student.dashboard.status-banner', [
            'student' => $student,
            'isEnrollmentOpen' => $this->isEnrollmentOpen,
            'displayStartDate' => $displayStartDate,
            'displayEndDate' => $displayEndDate
        ]);
    }
}
