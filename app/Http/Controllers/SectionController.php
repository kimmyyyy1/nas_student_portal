<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Staff; // Import Staff Model
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Eager load 'adviser' relationship para hindi mabagal ang loading ng table
        $sections = Section::with('adviser')->orderBy('grade_level')->orderBy('section_name')->get();
        return view('sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // KUNIN ANG STAFF (TEACHERS)
        // Siguraduhin na 'teacher' ang role
        // Kung may 'user' relationship ang Staff, pwede ring ->with('user')
        $teachers = Staff::where('role', 'teacher')->get();
        
        return view('sections.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // FIX: Pinalitan ang validation para sa adviser_id
        $validated = $request->validate([
            'grade_level' => 'required|string',
            'section_name' => 'required|string|max:255',
            
            // IMPORTANT: Validate na ang ID ay exist sa 'staff' table
            'adviser_id' => 'required|exists:staff,id', 
            
            'room_number' => 'nullable|string|max:255',
            'schedule' => 'nullable|string|max:255', // Optional: Kung ginagamit mo pa ito
        ]);

        Section::create($validated);

        return redirect()->route('sections.index')->with('success', 'Section created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section): View
    {
        // Kunin ulit ang teachers para sa edit dropdown
        $teachers = Staff::where('role', 'teacher')->get();

        return view('sections.edit', compact('section', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section): RedirectResponse
    {
        // FIX: Pinalitan din dito ang validation
        $validated = $request->validate([
            'grade_level' => 'required|string',
            'section_name' => 'required|string|max:255',
            
            // IMPORTANT: Validate na ang ID ay exist sa 'staff' table
            'adviser_id' => 'required|exists:staff,id',
            
            'room_number' => 'nullable|string|max:255',
            'schedule' => 'nullable|string|max:255',
        ]);

        $section->update($validated);

        return redirect()->route('sections.index')->with('success', 'Section updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section): RedirectResponse
    {
        $section->delete();
        return redirect()->route('sections.index')->with('success', 'Section deleted successfully.');
    }

    public function show(Section $section) { return view('sections.edit', compact('section')); }
}