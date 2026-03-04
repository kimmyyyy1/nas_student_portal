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

    public function toggleLock()
    {
        $application = Applicant::findOrFail($this->applicationId);
        
        // Prevent locking unless Officially Enrolled or qualified
        if ($application->status !== 'Officially Enrolled' && $application->status !== 'Enrolled') {
            session()->flash('error', 'You can only lock records that are Officially Enrolled.');
            return;
        }

        $application->is_locked = !$application->is_locked;
        $application->save();

        // LOG AUDIT TRAIL
        \App\Models\AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'applicant_id' => $application->id,
            'action' => $application->is_locked ? 'Locked Record' : 'Unlocked Record',
            'details' => json_encode(['remarks' => 'Admin toggled record lock status'])
        ]);

        session()->flash('success', $application->is_locked ? 'Record has been securely locked.' : 'Record has been unlocked.');
    }

    public function render()
    {
        $application = Applicant::findOrFail($this->applicationId);
        $auditLogs = \App\Models\AuditLog::with('user')->where('applicant_id', $this->applicationId)->latest()->get();

        return view('livewire.admin-application-review', [
            'application' => $application,
            'auditLogs' => $auditLogs
        ]);
    }
}