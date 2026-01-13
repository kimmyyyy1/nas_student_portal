<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\ActivityLog; // 👈 1. ADDED THIS IMPORT

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // 👇 2. ADDED ACTIVITY LOGGING HERE
        // Ito ang magsasave sa database kung sino ang nag-login
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Login',
            'description' => Auth::user()->name . ' has logged in.',
        ]);

        $role = $request->user()->role;

        if ($role === 'student') return redirect()->route('student.dashboard');
        if ($role === 'applicant') return redirect()->route('applicant.dashboard');

        // ALLOWED STAFF
        if (in_array($role, ['admin', 'coach', 'teacher', 'medical'])) {
            return redirect()->route('dashboard');
        }

        return redirect('/');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}