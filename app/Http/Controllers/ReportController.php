<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class ReportController extends Controller
{
    // 1. MATCH: resources/views/reports/index.blade.php
    public function index(): View
    {
        return view('reports.index');
    }

    // 2. MATCH: resources/views/reports/grade_sheets.blade.php
    public function gradeSheets(): View
    {
        return view('reports.grade_sheets');
    }

    // 3. MATCH: resources/views/reports/report_cards.blade.php
    public function reportCards(): View
    {
        return view('reports.report_cards');
    }

    // 4. MATCH: resources/views/reports/awardees.blade.php
    public function awardees(): View
    {
        return view('reports.awardees');
    }

    // 5. MATCH: resources/views/reports/ranking.blade.php
    public function ranking(): View
    {
        return view('reports.ranking');
    }

    // 6. MISSING FILE: resources/views/reports/school_forms.blade.php
    // Gumawa tayo ng logic para dito.
    public function schoolForms(): View
    {
        return view('reports.school_forms');
    }

    // Note: May file ka na 'academic.blade.php', hindi natin ito ginagamit sa ngayon.
    // Kung ito dapat ang School Forms, palitan mo ang nasa taas ng: return view('reports.academic');

    // --- RESOURCE METHODS (Safety Net) ---
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function show(string $id) { abort(404); }
    public function edit(string $id) { abort(404); }
    public function update(Request $request, string $id) { abort(404); }
    public function destroy(string $id) { abort(404); }
}