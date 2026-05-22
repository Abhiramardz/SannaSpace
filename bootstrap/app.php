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
    ->withMiddleware(function (Middleware $middleware): void {
        // 1. Registrasi Web Middleware bawaan Anda
        $middleware->web(append: [
            \App\Http\Middleware\PreventBackHistory::class,
        ]);

        // 2. Registrasi Alias Role Middleware Anda
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // 3. PENGECUALIAN CSRF TOKEN UNTUK CALLBACK MIDTRANS (Sudah digabung dengan benar)
        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();