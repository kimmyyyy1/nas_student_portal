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
                $query->whereIn('status', ['Pending', 'pending', 'For Assessment', 'Pending Review']);
            } elseif ($status === 'Qualified') {
                $query->whereIn('status', ['Qualified', 'qualified']);
            } elseif ($status === 'Waitlisted') {
                $query->whereIn('status', ['Waitlisted', 'waitlisted']);
            } elseif ($status === 'Not Qualified') {
                $query->whereIn('status', ['Not Qualified', 'not qualified', 'Rejected', 'Failed']);
            } else {
                $query->where('status', $status);
            }
        }

        // Statistics
        $totalSubmitted = Applicant::count();
        $countPending    = Applicant::whereIn('status', ['Pending', 'pending', 'For Assessment', 'Pending Review'])->count();
        $countQualified  = Applicant::whereIn('status', ['Qualified', 'qualified'])->count();
        $countWaitlisted = Applicant::whereIn('status', ['Waitlisted', 'waitlisted'])->count();
        $countRejected   = Applicant::whereIn('status', ['Not Qualified', 'not qualified', 'Rejected', 'Failed'])->count();

        // Pagination
        $applications = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admission-masterlist', [
            'applications' => $applications,
            'totalSubmitted' => $totalSubmitted,
            'countPending' => $countPending,
            'countQualified' => $countQualified,
            'countWaitlisted' => $countWaitlisted,
            'countRejected' => $countRejected,
        ]);
    }
}