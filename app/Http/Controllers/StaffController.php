<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Display a listing of the staff.
     */
    public function index(): View
    {
        $staff = Staff::latest()->get();
        return view('staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create(): View
    {
        return view('staff.create');
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validation
        $validated = $request->validate([
            'employee_id' => 'required|string|unique:staff,employee_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email|unique:users,email',
            'contact_number' => 'nullable|string',
            // Added 'staff' and 'registrar' to allowed roles to match the dropdown
            'role' => 'required|in:teacher,coach,sass,admin,staff,registrar', 
            'department' => 'nullable|string',
            'position' => 'nullable|string',
        ]);

        // 2. Create Staff Record
        $staff = Staff::create($validated);

        // 3. Auto-create User Account (Linked via email)
        $defaultPassword = 'password'; 
        
        User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'], // Combine names
            'email' => $validated['email'],
            'password' => Hash::make($defaultPassword),
            'role' => $validated['role'],
            // Optional: Kung ginagamit mo ang student_id column ng users table bilang employee ID
            'student_id' => $validated['employee_id'], 
        ]);

        return redirect()->route('staff.index')
            ->with('success', "Staff added! Account created. Email: {$staff->email} | Password: {$defaultPassword}");
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(Staff $staff): View
    {
        return view('staff.edit', compact('staff'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(Request $request, Staff $staff): RedirectResponse
    {
        // 1. Find the Linked User Account FIRST (Before updating staff email)
        // Gamitin ang old email para mahanap ang user account
        $user = User::where('email', $staff->email)->first();

        // 2. Validation
        $validated = $request->validate([
            // 'ignore' is crucial here: allows the current staff to keep their own ID/Email
            'employee_id' => ['required', 'string', Rule::unique('staff')->ignore($staff->id)],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('staff')->ignore($staff->id)],
            'contact_number' => 'nullable|string',
            // Added 'staff' and 'registrar' to allowed roles
            'role' => 'required|in:teacher,coach,sass,admin,staff,registrar',
            'department' => 'nullable|string',
            'position' => 'nullable|string',
        ]);

        // 3. Update Staff Table
        $staff->update($validated);

        // 4. Sync Changes to User Table
        if ($user) {
            // Check if email is being changed, ensure it's unique in users table too (except for self)
            $emailCheck = User::where('email', $validated['email'])->where('id', '!=', $user->id)->exists();
            
            if (!$emailCheck) {
                $user->update([
                    'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                    'email' => $validated['email'],
                    'role' => $validated['role'],
                    'student_id' => $validated['employee_id'], // Keep IDs in sync
                ]);
            }
        }

        return redirect()->route('staff.index')->with('success', 'Staff details updated successfully.');
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(Staff $staff): RedirectResponse
    {
        // Delete User Account first
        $user = User::where('email', $staff->email)->first();
        if ($user) {
            $user->delete();
        }

        // Delete Staff Record
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member and associated account deleted.');
    }
}