<?php

namespace App\Http\Controllers;

use App\Models\TrainingPlan;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TrainingPlanController extends Controller
{
    public function index(): View
    {
        $trainingPlans = TrainingPlan::with('team')->latest()->get();
        return view('training-plans.index', compact('trainingPlans'));
    }

    public function create(): View
    {
        $teams = Team::orderBy('team_name')->get();
        return view('training-plans.create', compact('teams'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plan_name' => 'required|string|max:255',
            'team_id' => 'nullable|exists:teams,id',
            'details' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        TrainingPlan::create($validated);

        return redirect()->route('training-plans.index')->with('success', 'Training Plan created successfully.');
    }

    public function edit(TrainingPlan $trainingPlan): View
    {
        $teams = Team::orderBy('team_name')->get();
        return view('training-plans.edit', compact('trainingPlan', 'teams'));
    }

    public function update(Request $request, TrainingPlan $trainingPlan): RedirectResponse
    {
        $validated = $request->validate([
            'plan_name' => 'required|string|max:255',
            'team_id' => 'nullable|exists:teams,id',
            'details' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $trainingPlan->update($validated);

        return redirect()->route('training-plans.index')->with('success', 'Training Plan updated successfully.');
    }

    public function destroy(TrainingPlan $trainingPlan): RedirectResponse
    {
        $trainingPlan->delete();
        return redirect()->route('training-plans.index')->with('success', 'Training Plan deleted.');
    }

    public function show($id) { abort(404); }
}