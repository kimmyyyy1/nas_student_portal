<?php

namespace App\Http\Controllers;

use App\Models\Subject; // Pinalitan ng Subject
use Illuminate\Http\Request;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule; // Gagamitin natin ito

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $subjects = Subject::latest()->get(); // Pinalitan ng $subjects
        return view('subjects.index', compact('subjects')); // Pinalitan ang view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('subjects.create'); // Pinalitan ang view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // I-validate
        $validatedData = $request->validate([
            'subject_code' => 'required|string|max:255|unique:subjects', // Unique
            'subject_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // I-create
        Subject::create($validatedData);

        // I-redirect
        return redirect()->route('subjects.index')->with('success', 'Subject added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject): View // Pinalitan ang type-hint
    {
        // Ipasa ang $subject sa edit view
        return view('subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject): RedirectResponse // Pinalitan ang type-hint
    {
        // I-validate
        $validatedData = $request->validate([
            'subject_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects')->ignore($subject->id), // Unique maliban sa sarili
            ],
            'subject_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // I-update
        $subject->update($validatedData);

        // I-redirect
        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject): RedirectResponse // Pinalitan ang type-hint
    {
        // I-delete
        $subject->delete();

        // I-redirect
        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}