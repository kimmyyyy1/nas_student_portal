<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display all schedules (Admin View).
     */
    public function index(): View
    {
        $schedules = Schedule::with(['section', 'subject', 'staff'])
                        ->orderBy('day')
                        ->orderBy('time_start')
                        ->get();
        return view('schedules.index', compact('schedules'));
    }

    /**
     * SHOW TEACHER'S OWN SCHEDULE (New Method)
     * Ito ang hinahanap ng system na nawawala.
     */
    public function mySchedule(): View
    {
        $userEmail = Auth::user()->email;
        
        // 1. Hanapin ang Staff ID gamit ang email ng naka-login na user
        $staff = Staff::where('email', $userEmail)->first();

        if (!$staff) {
            // Kung walang match na staff record
            return view('schedules.my-schedule', ['schedules' => collect([])])
                ->with('warning', 'No staff record found linked to this account.');
        }

        // 2. Kunin ang schedules na naka-assign sa staff na ito
        $schedules = Schedule::with(['section', 'subject'])
                        ->where('staff_id', $staff->id)
                        ->orderBy('day')
                        ->orderBy('time_start')
                        ->get();

        return view('schedules.my-schedule', compact('schedules'));
    }

    public function create(): View
    {
        $sections = Section::all();
        $subjects = Subject::all();
        // Get Teachers and Coaches only
        $staff = Staff::whereIn('role', ['teacher', 'coach'])->get();
        return view('schedules.create', compact('sections', 'subjects', 'staff'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'staff_id' => 'required|exists:staff,id',
            'day' => 'required|string',
            'time_start' => 'required',
            'time_end' => 'required|after:time_start',
            'room' => 'nullable|string',
        ]);

        Schedule::create($validated);

        return redirect()->route('schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function edit(Schedule $schedule): View
    {
        $sections = Section::all();
        $subjects = Subject::all();
        $staff = Staff::whereIn('role', ['teacher', 'coach'])->get();
        return view('schedules.edit', compact('schedule', 'sections', 'subjects', 'staff'));
    }

    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'staff_id' => 'required|exists:staff,id',
            'day' => 'required|string',
            'time_start' => 'required',
            'time_end' => 'required|after:time_start',
            'room' => 'nullable|string',
        ]);

        $schedule->update($validated);

        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully.');
    }

    public function show(Schedule $schedule) { abort(404); }
}