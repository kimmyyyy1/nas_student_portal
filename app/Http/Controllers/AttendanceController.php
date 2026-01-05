<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;  // Import
use App\Models\Schedule; // Import
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Eager load ang relationships
        $attendances = Attendance::with('student', 'schedule.subject', 'schedule.section')->latest()->get();
        
        return view('attendances.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Kunin lahat ng data para sa dropdowns
        $students = Student::orderBy('last_name')->get();
        // Kukunin natin ang subject, section, at staff para sa malinaw na dropdown
        $schedules = Schedule::with('subject', 'section', 'staff')->get(); 
        
        return view('attendances.create', compact('students', 'schedules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // I-validate
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'schedule_id' => 'required|exists:schedules,id',
            'date' => 'required|date',
            'status' => 'required|in:Present,Absent,Late,Excused',
        ]);

        // I-create
        Attendance::create($validatedData);

        // I-redirect
        return redirect()->route('attendances.index')->with('success', 'Attendance recorded successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance): View
    {
        // Kunin lahat ng data para sa dropdowns
        $schedules = Schedule::with('subject', 'section', 'staff')->get(); 
        
        // Ipasa ang $attendance at ang dropdown data
        return view('attendances.edit', compact('attendance', 'schedules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance): RedirectResponse
    {
        // I-validate
        $validatedData = $request->validate([
            // Student ID ay nanggagaling sa hidden field (tingnan ang edit.blade.php)
            'student_id' => 'required|exists:students,id', 
            'schedule_id' => 'required|exists:schedules,id',
            'date' => 'required|date',
            'status' => 'required|in:Present,Absent,Late,Excused',
        ]);

        // I-update
        $attendance->update($validatedData);

        // I-redirect
        return redirect()->route('attendances.index')->with('success', 'Attendance record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance): RedirectResponse
    {
        // I-delete
        $attendance->delete();

        // I-redirect
        return redirect()->route('attendances.index')->with('success', 'Attendance record deleted successfully.');
    }
    
    public function show(Attendance $attendance)
    {
        abort(404);
    }
}