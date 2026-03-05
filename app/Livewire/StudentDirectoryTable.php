<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;
use App\Models\Section;
use App\Models\Applicant;
use App\Models\EnrollmentDetail;

class StudentDirectoryTable extends Component
{
    use WithPagination;

    public $search = '';
    public $grade_level = '';
    public $section_id = '';
    public $status = '';
    public $sport = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'grade_level' => ['except' => ''],
        'section_id' => ['except' => ''],
        'status' => ['except' => ''],
        'sport' => ['except' => ''],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingGradeLevel() { $this->resetPage(); }
    public function updatingSectionId() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingSport() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->search = '';
        $this->grade_level = '';
        $this->section_id = '';
        $this->status = '';
        $this->sport = '';
        $this->resetPage();
    }

    private function buildStudentQuery()
    {
        $query = Student::query();

        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%")
                  ->orWhere('nas_student_id', 'like', "%{$search}%");
            });
        }

        if (!empty($this->grade_level)) {
            $query->where('grade_level', $this->grade_level);
        }

        if (!empty($this->section_id)) {
            $query->where('section_id', $this->section_id);
        }

        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        if (!empty($this->sport)) {
            $sport = $this->sport;
            $searchSport = explode(' ', $sport)[0];
            $searchSport = rtrim($searchSport, 's'); 

            $applicantLrns = Applicant::where('sport', 'LIKE', "%{$searchSport}%")->pluck('lrn')->toArray();
            $detailLrns = EnrollmentDetail::where('sport', 'LIKE', "%{$searchSport}%")->pluck('lrn')->toArray();

            $query->where(function($q) use ($searchSport, $applicantLrns, $detailLrns) {
                $q->whereHas('team', function($t) use ($searchSport) {
                      $t->where('sport', 'LIKE', "%{$searchSport}%")
                        ->orWhere('team_name', 'LIKE', "%{$searchSport}%");
                  })
                  ->orWhereIn('lrn', $applicantLrns)
                  ->orWhereIn('lrn', $detailLrns);
            });
        }

        return $query;
    }

    public function render()
    {
        $sections = Section::orderBy('grade_level')->orderBy('section_name')->get();
        
        $query = $this->buildStudentQuery()->with(['section', 'team']);
        $students = $query->orderBy('last_name')->paginate(15); 

        return view('livewire.student-directory-table', compact('students', 'sections'));
    }
}
