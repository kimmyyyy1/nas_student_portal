<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Applicant; // <--- ITO ANG KULANG KANINA (Import the Model)

class AdminApplicationReview extends Component
{
    public $applicationId;

    public function mount($id)
    {
        $this->applicationId = $id;
    }

    public function render()
    {
        // Pinalitan ko ng 'Applicant' dahil yan ang ginamit natin sa Dashboard
        $application = Applicant::findOrFail($this->applicationId);

        return view('livewire.admin-application-review', [
            'application' => $application
        ]);
    }
}