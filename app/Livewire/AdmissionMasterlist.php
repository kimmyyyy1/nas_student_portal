<?php

namespace App\Livewire;

use App\Models\Applicant;
use Livewire\Component;
use Livewire\WithPagination;

class AdmissionMasterlist extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    protected $queryString = ['search', 'status'];

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
        $this->status = request()->query('status', $this->status);
    }

    public function render()
    {
        $query = Applicant::query();

        // --- 1. SEARCH LOGIC ---
        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('lrn', 'like', "%{$search}%")
                    ->orWhere('email_address', 'like', "%{$search}%");
            });
        }

        // --- 2. FILTER LOGIC (Clicking Dashboard Cards) ---
        if ($this->status) {
            if ($this->status === 'Pending') {
                $query->whereIn('status', ['Pending', 'With Pending Requirements']);
            } elseif ($this->status === 'Assessment') {
                // Includes ALL variations of Assessment
                $query->whereIn('status', [
                    'Assessment', 
                    'For Assessment', 
                    'With Complete Requirements & for 1st Level Assessment', 
                    'For 2nd Level Assessment'
                ]);
            } elseif ($this->status === 'Qualified') {
                $query->where('status', 'Qualified');
            } elseif ($this->status === 'Waitlisted') {
                $query->where('status', 'Waitlisted');
            } elseif ($this->status === 'Not Qualified') {
                $query->whereIn('status', ['Not Qualified', 'Rejected', 'Failed']);
            } elseif ($this->status === 'Enrolled') {
                $query->whereIn('status', ['Enrolled', 'Endorsed for Enrollment']);
            } else {
                $query->where('status', $this->status);
            }
        }

        // --- 3. DASHBOARD COUNTERS (STATISTICS) ---
        
        $totalSubmitted = Applicant::count();

        // Count Pending
        $countPending = Applicant::whereIn('status', [
            'Pending', 
            'With Pending Requirements'
        ])->count();

        // Count Assessment (Crucial Fix: Added long status string)
        $countAssessment = Applicant::whereIn('status', [
            'Assessment',
            'For Assessment',
            'With Complete Requirements & for 1st Level Assessment', 
            'For 2nd Level Assessment'
        ])->count();

        $countQualified = Applicant::where('status', 'Qualified')->count();

        $countWaitlisted = Applicant::where('status', 'Waitlisted')->count();

        // Count Rejected/Failed
        $countRejected = Applicant::whereIn('status', [
            'Not Qualified', 
            'Rejected', 
            'Failed'
        ])->count();

        // Count Enrolled
        $countEnrolled = Applicant::whereIn('status', [
            'Enrolled', 
            'Endorsed for Enrollment'
        ])->count();

        // --- 4. PAGINATION ---
        $applications = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admission-masterlist', [
            'applications'    => $applications,
            'totalSubmitted'  => $totalSubmitted,
            'countPending'    => $countPending,
            'countAssessment' => $countAssessment,
            'countQualified'  => $countQualified,
            'countWaitlisted' => $countWaitlisted,
            'countRejected'   => $countRejected,
            'countEnrolled'   => $countEnrolled,
        ]);
    }
}