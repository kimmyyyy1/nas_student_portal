<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Order by Focus Sport alphabetically
        $teams = Team::orderBy('sport')->get();
        return view('teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validate ONLY sport and coach (REMOVE team_name here)
        $validatedData = $request->validate([
            'sport' => 'required|string|max:255',
            'coach_name' => 'nullable|string|max:255',
        ]);

        // 2. Auto-generate Team Name based on Sport
        // Format: "Sport Name" + " Team" (e.g., "Badminton Team")
        $validatedData['team_name'] = $validatedData['sport'] . ' Team';

        // 3. Create
        Team::create($validatedData);

        return redirect()->route('teams.index')->with('success', 'Focus Sport added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team): View
    {
        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team): RedirectResponse
    {
        // 1. Validate ONLY sport and coach
        $validatedData = $request->validate([
            'sport' => 'required|string|max:255',
            'coach_name' => 'nullable|string|max:255',
        ]);

        // 2. Auto-update Team Name based on new Sport
        $validatedData['team_name'] = $validatedData['sport'] . ' Team';

        // 3. Update
        $team->update($validatedData);

        return redirect()->route('teams.index')->with('success', 'Focus Sport updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team): RedirectResponse
    {
        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Focus Sport deleted successfully.');
    }
}