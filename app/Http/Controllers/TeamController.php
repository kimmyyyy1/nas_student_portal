<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
// Tandaan: Pwede tayong magdagdag ng 'Rule' dito kung kailangan ng unique validation

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $teams = Team::latest()->get();
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
        $validatedData = $request->validate([
            'team_name' => 'required|string|max:255',
            'sport' => 'required|string|max:255',
            'coach_name' => 'nullable|string|max:255',
        ]);

        Team::create($validatedData);

        return redirect()->route('teams.index')->with('success', 'Team added successfully.');
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
    public function edit(Team $team): View // Pinuno na natin ito
    {
        // Ipakita ang edit view at ipasa ang team data
        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team): RedirectResponse // Pinuno na natin ito
    {
        // 1. I-validate
        $validatedData = $request->validate([
            'team_name' => 'required|string|max:255',
            'sport' => 'required|string|max:255',
            'coach_name' => 'nullable|string|max:255',
        ]);

        // 2. I-update
        $team->update($validatedData);

        // 3. Bumalik sa listahan
        return redirect()->route('teams.index')->with('success', 'Team updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team): RedirectResponse // Pinuno na natin ito
    {
        // Burahin ang team
        $team->delete();

        // Bumalik sa listahan
        return redirect()->route('teams.index')->with('success', 'Team deleted successfully.');
    }
}