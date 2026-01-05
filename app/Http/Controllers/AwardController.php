<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AwardController extends Controller
{
    /**
     * Display a listing of awards.
     */
    public function index(): View
    {
        // Eager load 'student' para makuha ang pangalan ng awardee
        $awards = Award::with('student')->latest()->get();
        return view('awards.index', compact('awards'));
    }

    /**
     * Show the form for creating a new award.
     */
    public function create(): View
    {
        // Kunin ang listahan ng students para sa dropdown
        $students = Student::orderBy('last_name')->get();
        return view('awards.create', compact('students'));
    }

    /**
     * Store a newly created award in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'award_name' => 'required|string|max:255',
            'date_received' => 'required|date',
            'category' => 'required|in:Academic,Sports,Special', // Categories
            'description' => 'nullable|string',
        ]);

        Award::create($validated);

        return redirect()->route('awards.index')->with('success', 'Award recorded successfully.');
    }

    /**
     * Show the form for editing the specified award.
     */
    public function edit(Award $award): View
    {
        $students = Student::orderBy('last_name')->get();
        return view('awards.edit', compact('award', 'students'));
    }

    /**
     * Update the specified award in storage.
     */
    public function update(Request $request, Award $award): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'award_name' => 'required|string|max:255',
            'date_received' => 'required|date',
            'category' => 'required|in:Academic,Sports,Special',
            'description' => 'nullable|string',
        ]);

        $award->update($validated);

        return redirect()->route('awards.index')->with('success', 'Award updated successfully.');
    }

    /**
     * Remove the specified award from storage.
     */
    public function destroy(Award $award): RedirectResponse
    {
        $award->delete();
        return redirect()->route('awards.index')->with('success', 'Award deleted successfully.');
    }

    /**
     * Display the specified resource (Placeholder).
     */
    public function show(Award $award)
    {
        abort(404);
    }
}