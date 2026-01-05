<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. Trust Proxies (MAHALAGA PARA SA NGROK)
        // Sinasabi nito sa Laravel na pagkatiwalaan ang request galing sa Ngrok tunnel.
        // Kung wala ito, magkakaroon ka ng 419 Page Expired errors.
        $middleware->trustProxies(at: '*');

        // 2. Register Middleware Aliases
        // Dito natin pinapangalanan ang 'role' middleware para magamit sa routes/web.php
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();