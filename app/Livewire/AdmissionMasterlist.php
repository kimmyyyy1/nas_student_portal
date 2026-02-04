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

        // Search Logic
        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('lrn', 'like', "%{$search}%")
                    ->orWhere('email_address', 'like', "%{$search}%");
            });
        }

        // Filter Logic
        if ($this->status) {
            $status = $this->status;

            if ($status === 'Pending') {
                $query->where('status', 'With Pending Requirements');
            } elseif ($status === 'Assessment') {
                $query->whereIn('status', ['With Complete Requirements & for 1st Level Assessment', 'For 2nd Level Assessment']);
            } elseif ($status === 'Qualified') {
                $query->where('status', 'Qualified');
            } elseif ($status === 'Waitlisted') {
                $query->where('status', 'Waitlisted');
            } elseif ($status === 'Not Qualified') {
                $query->where('status', 'Not Qualified');
            } elseif ($status === 'Enrolled') {
                $query->where('status', 'Endorsed for Enrollment');
            } else {
                $query->where('status', $status);
            }
        }

        // Statistics
        $totalSubmitted = Applicant::count();
        $countPending    = Applicant::where('status', 'With Pending Requirements')->count();
        $countAssessment = Applicant::whereIn('status', ['With Complete Requirements & for 1st Level Assessment', 'For 2nd Level Assessment'])->count();
        $countQualified  = Applicant::where('status', 'Qualified')->count();
        $countWaitlisted = Applicant::where('status', 'Waitlisted')->count();
        $countRejected   = Applicant::where('status', 'Not Qualified')->count();
        $countEnrolled   = Applicant::where('status', 'Endorsed for Enrollment')->count();

        // Pagination
        $applications = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admission-masterlist', [
            'applications' => $applications,
            'totalSubmitted' => $totalSubmitted,
            'countPending' => $countPending,
            'countAssessment' => $countAssessment,
            'countQualified' => $countQualified,
            'countWaitlisted' => $countWaitlisted,
            'countRejected' => $countRejected,
            'countEnrolled' => $countEnrolled,
        ]);
    }
}