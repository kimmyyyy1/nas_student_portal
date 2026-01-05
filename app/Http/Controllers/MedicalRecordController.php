<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord; // IMPORTANTE: Huwag kalimutan ito
use App\Models\Student;       // IMPORTANTE: Huwag kalimutan ito
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MedicalRecordController extends Controller
{
    public function index(): View
    {
        $medicalRecords = MedicalRecord::with('student')->latest()->get();
        return view('medical-records.index', compact('medicalRecords'));
    }

    public function create(): View
    {
        $students = Student::orderBy('last_name')->get();
        return view('medical-records.create', compact('students'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'record_type' => 'required|string',
            'status' => 'required|in:Cleared,Pending,Restricted',
            'notes' => 'nullable|string',
            'date_cleared' => 'nullable|date',
        ]);

        MedicalRecord::create($validated);

        return redirect()->route('medical-records.index')->with('success', 'Record added successfully.');
    }

    public function edit(MedicalRecord $medicalRecord): View
    {
        return view('medical-records.edit', compact('medicalRecord'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:Cleared,Pending,Restricted',
            'notes' => 'nullable|string',
            'date_cleared' => 'nullable|date',
        ]);

        $medicalRecord->update($validated);

        return redirect()->route('medical-records.index')->with('success', 'Record updated successfully.');
    }

    public function destroy(MedicalRecord $medicalRecord): RedirectResponse
    {
        $medicalRecord->delete();
        return redirect()->route('medical-records.index')->with('success', 'Record deleted.');
    }

    public function show($id) { abort(404); }
}