<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;  // Import
use App\Models\Schedule; // Import
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Eager load ang relationships para mas mabilis
        $grades = Grade::with('student', 'schedule.subject')->latest()->get();
        
        return view('grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Kunin lahat ng data para sa dropdowns
        $students = Student::orderBy('last_name')->get();
        $schedules = Schedule::with('subject', 'section')->get();
        
        return view('grades.create', compact('students', 'schedules'));
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
            'mark' => 'required|numeric|min:0|max:100',
            // INAYOS ANG VALIDATION: 4th Quarter lang
            'grading_period' => 'required|in:1st Quarter,2nd Quarter,3rd Quarter,4th Quarter',
        ]);

        // I-create
        Grade::create($validatedData);

        // I-redirect
        return redirect()->route('grades.index')->with('success', 'Grade added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade): View
    {
        // Kunin lahat ng data para sa dropdowns
        $students = Student::orderBy('last_name')->get();
        $schedules = Schedule::with('subject', 'section')->get();
        
        // Ipasa ang $grade at ang dropdown data
        return view('grades.edit', compact('grade', 'students', 'schedules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade): RedirectResponse
    {
        // I-validate
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'schedule_id' => 'required|exists:schedules,id',
            'mark' => 'required|numeric|min:0|max:100',
            // INAYOS ANG VALIDATION: 4th Quarter lang
            'grading_period' => 'required|in:1st Quarter,2nd Quarter,3rd Quarter,4th Quarter',
        ]);

        // I-update
        $grade->update($validatedData);

        // I-redirect
        return redirect()->route('grades.index')->with('success', 'Grade updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade): RedirectResponse
    {
        // I-delete
        $grade->delete();

        // I-redirect
        return redirect()->route('grades.index')->with('success', 'Grade deleted successfully.');
    }
}