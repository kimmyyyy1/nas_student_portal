<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Applicant; // Siguraduhing tama ang Model mo

class EnrollmentList extends Component
{
    use WithPagination;

    public function render()
    {
        // Kukunin natin ang mga 'Officially Enrolled' or kung ano man ang logic mo sa controller dati
        // Palitan mo 'to kung may iba kang filters
        $enrollees = Applicant::where('status', 'Officially Enrolled')
            ->orWhere('status', 'For Verification') // Example lang, adjust mo base sa logic mo
            ->latest()
            ->paginate(10);

        return view('livewire.enrollment-list', [
            'enrollees' => $enrollees
        ]);
    }
}