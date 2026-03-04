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
    public $filterSport = '';
    public $filterRegion = '';

    protected $queryString = ['search', 'status', 'filterSport', 'filterRegion'];

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
        $this->status = request()->query('status', $this->status);
        $this->filterSport = request()->query('filterSport', $this->filterSport);
        $this->filterRegion = request()->query('filterRegion', $this->filterRegion);
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

        // --- 1.5 ADVANCED FILTERING LOGIC ---
        if ($this->filterSport) {
            $query->where('sport', $this->filterSport);
        }
        if ($this->filterRegion) {
            $query->where('region', $this->filterRegion);
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
                    'For 2nd Level Assessment',
                    'Submitted for 1st Level Assessment'
                ]);
            } elseif ($this->status === 'Qualified') {
                $query->where('status', 'Qualified');
            } elseif ($this->status === 'Pending Renewal') {
                $query->where('status', 'Pending Renewal');
            } elseif ($this->status === 'Waitlisted') {
                $query->where('status', 'Waitlisted');
            } elseif ($this->status === 'Not Qualified') {
                $query->whereIn('status', ['Not Qualified', 'Rejected', 'Failed']);
            } elseif ($this->status === 'Enrolled' || $this->status === 'Admitted') {
                $query->whereIn('status', [
                    'For Enrollment Verification',
                    'Officially Enrolled', 
                    'Admitted',
                    'Enrolled'
                ]);
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

        // Count Assessment
        $countAssessment = Applicant::whereIn('status', [
            'Assessment',
            'For Assessment',
            'With Complete Requirements & for 1st Level Assessment', 
            'For 2nd Level Assessment',
            'Submitted for 1st Level Assessment'
        ])->count();

        $countQualified = Applicant::where('status', 'Qualified')->count();

        $countWaitlisted = Applicant::where('status', 'Waitlisted')->count();

        $countRenewal = Applicant::where('status', 'Pending Renewal')->count();

        // Count Rejected/Failed
        $countRejected = Applicant::whereIn('status', [
            'Not Qualified', 
            'Rejected', 
            'Failed'
        ])->count();

        // Count Enrolled 
        // ⚡ FIX: Idinagdag ang 'Admitted' and 'Officially Enrolled' sa bibilangin para maging '1' ang card ⚡
        $countEnrolled = Applicant::whereIn('status', [
            'For Enrollment Verification',
            'Officially Enrolled',
            'Admitted',
            'Enrolled'
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
            'countRenewal'    => $countRenewal,
            'countRejected'   => $countRejected,
            'countEnrolled'   => $countEnrolled,
        ]);
    }
}