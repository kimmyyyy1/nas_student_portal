<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Staff; 
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
        // 👇 FINAL SORTING LOGIC:
        // 1. Unahin ang haba ng text (Para ang Grade 7 ay mauna sa Grade 10)
        // 2. Sunod ang Grade Level mismo (7, 8, 9...)
        // 3. Huli ang Section Name (A-Z sa loob ng Grade)
        
        $sections = Section::with('adviser')
                           ->orderByRaw('LENGTH(grade_level) ASC') 
                           ->orderBy('grade_level', 'asc')
                           ->orderBy('section_name', 'asc') 
                           ->get();

        return view('sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $teachers = Staff::where('role', 'teacher')->orderBy('last_name')->get();
        return view('sections.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'grade_level' => 'required|string',
            'section_name' => 'required|string|max:255',
            'adviser_id' => 'required|exists:staff,id', 
            'room_number' => 'nullable|string|max:255',
            'schedule' => 'nullable|string|max:255', 
        ]);

        Section::create($validated);

        return redirect()->route('sections.index')->with('success', 'Section created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section): View
    {
        $teachers = Staff::where('role', 'teacher')->orderBy('last_name')->get();
        return view('sections.edit', compact('section', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section): RedirectResponse
    {
        $validated = $request->validate([
            'grade_level' => 'required|string',
            'section_name' => 'required|string|max:255',
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