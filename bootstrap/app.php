<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SecurityHeaders; // 👈 1. ADD THIS IMPORT

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. Trust Proxies (MAHALAGA PARA SA NGROK & VERCEL)
        $middleware->trustProxies(at: '*');

        // 2. Register Middleware Aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // 3. 👇 SECURITY HEADERS (Global Middleware)
        // Ito ang papalit sa tinanggal natin sa vercel.json
        $middleware->append(SecurityHeaders::class);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();