<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Applicant;

class EnrollmentList extends Component
{
    use WithPagination;

    public function render()
    {
        // ⚡ Ginamit natin ang 'whereIn' para saluhin lahat ng enrollment-related statuses ⚡
        $enrollees = Applicant::whereIn('status', [
            'Officially Enrolled', // Bagong submit ng estudyante (Pending)
            'Pending Renewal',     // ✅ Idinagdag para sa Continuing Students
            'For Verification',    // Kung sakaling ito ang gamit mo
            'Admitted',            // ✅ Idinagdag natin para makita ang tapos na
            'Enrolled'             // ✅ Idinagdag din in case na ito ang exact status text
        ])
        ->latest()
        ->paginate(10);

        return view('livewire.enrollment-list', [
            'enrollees' => $enrollees
        ]);
    }
}