<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Tiyakin na may naka-login na user at tingnan ang role niya
        if ($request->user() && $request->user()->role !== 'admin') {
            
            // Kung HINDI admin (estudyante), ibalik sa Student Portal
            return redirect()->route('student.dashboard');
        }

        // Kung Admin, papasukin sa route
        return $next($request);
    }
}