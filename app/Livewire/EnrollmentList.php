<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Applicant;

class EnrollmentList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterSport = '';
    public $filterRegion = '';
    public $filterStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterSport' => ['except' => ''],
        'filterRegion' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterSport()
    {
        $this->resetPage();
    }

    public function updatingFilterRegion()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Applicant::whereIn('status', [
            'For Enrollment Verification', 
            'Qualified (Returned)',
            'Renewal (Returned)',
            'Officially Enrolled', 
            'Pending Renewal',     
            'Admitted',            
            'Enrolled'             
        ]);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('lrn', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->filterSport)) {
            $query->where('sport', $this->filterSport);
        }

        if (!empty($this->filterRegion)) {
            $query->where('region', $this->filterRegion);
        }

        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        $enrollees = $query->latest()->paginate(10);

        return view('livewire.enrollment-list', [
            'enrollees' => $enrollees,
            'sports' => Applicant::select('sport')->distinct()->whereNotNull('sport')->pluck('sport'),
            'regions' => Applicant::select('region')->distinct()->whereNotNull('region')->pluck('region'),
        ]);
    }

    public function exportCSV()
    {
        $query = Applicant::whereIn('status', [
            'For Enrollment Verification', 
            'Qualified (Returned)',
            'Renewal (Returned)',
            'Officially Enrolled', 
            'Pending Renewal',     
            'Admitted',            
            'Enrolled'             
        ]);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('lrn', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->filterSport)) {
            $query->where('sport', $this->filterSport);
        }

        if (!empty($this->filterRegion)) {
            $query->where('region', $this->filterRegion);
        }

        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        $enrollees = $query->latest()->get();

        $fileName = "Official_Enrollment_Records_" . date('Y-m-d_H-i-s') . ".csv";

        $columns = [
            'App ID', 'LRN', 'First Name', 'Middle Name', 'Last Name', 
            'Extension Name', 'Email', 'Sport', 'Region', 'Gender', 
            'Date of Birth', 'Status', 'Record Locked'
        ];

        return response()->streamDownload(function() use($enrollees, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($enrollees as $applicant) {
                $row['App ID']  = str_pad($applicant->id, 6, '0', STR_PAD_LEFT);
                $row['LRN']     = $applicant->lrn;
                $row['First Name'] = current(explode(' ', $applicant->first_name)); // Just to safeguard formatting
                $row['First Name'] = $applicant->first_name;
                $row['Middle Name'] = $applicant->middle_name;
                $row['Last Name'] = $applicant->last_name;
                $row['Extension Name'] = $applicant->extension_name;
                $row['Email'] = $applicant->email;
                $row['Sport'] = $applicant->sport;
                $row['Region'] = $applicant->region;
                $row['Gender'] = $applicant->sex;
                $row['Date of Birth'] = filled($applicant->date_of_birth) ? date('M d, Y', strtotime($applicant->date_of_birth)) : 'N/A';
                $row['Status'] = $applicant->status;
                $row['Record Locked'] = $applicant->is_locked ? 'Yes' : 'No';

                fputcsv($file, array(
                    $row['App ID'], $row['LRN'], $row['First Name'], $row['Middle Name'], 
                    $row['Last Name'], $row['Extension Name'], $row['Email'], 
                    $row['Sport'], $row['Region'], $row['Gender'], $row['Date of Birth'], 
                    $row['Status'], $row['Record Locked']
                ));
            }
            
            fclose($file);
        }, $fileName);
    }
}