<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Applicant;
use Carbon\Carbon;

class ApplicantStatusMonitor extends Component
{
    // Empty listener to allow refresh triggers
    protected $listeners = ['refreshDashboard' => '$refresh'];

    public function render()
    {
        // Fetch fresh data
        $application = Applicant::where('user_id', Auth::id())->first();
        
        // Progress Logic
        $progress = 35; // Default Phase 1
        
        if ($application) {
            if (str_contains($application->status, '2nd Level') || 
                str_contains($application->status, 'Requirements Submitted') || 
                str_contains($application->status, 'Returned')) {
                $progress = 65;
            }
            if (str_contains($application->status, 'Qualified')) {
                $progress = 90;
            }
            if (str_contains($application->status, 'Enrolled') || str_contains($application->status, 'Endorsed')) {
                $progress = 100;
            }
        }

        return view('livewire.applicant-status-monitor', [
            'application' => $application,
            'progress' => $progress,
            'currentDate' => Carbon::now()->format('l, F j, Y')
        ]);
    }
}