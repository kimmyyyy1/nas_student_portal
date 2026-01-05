<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Kung ang role ng user ay HINDI kasama sa allowed list ($roles)
        if (! in_array($request->user()->role, $roles)) {
            
            // Redirects
            if ($request->user()->role === 'student') {
                return redirect()->route('student.dashboard');
            }
            if ($request->user()->role === 'applicant') {
                return redirect()->route('applicant.dashboard');
            }
            
            // Staff Redirect
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}