<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ---------------
        // Role-based portal access guard
        // ---------------
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'remember.session' => \App\Http\Middleware\EnforceRememberSession::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);

        // ---------------
        // This app has one login screen, named manage.auth.login, not the
        // framework's default plain 'login'. Without this, any protected
        // page visited without an active session throws a
        // RouteNotFoundException instead of redirecting to sign in.
        // ---------------
        $middleware->redirectGuestsTo(fn () => route('manage.auth.login'));

        // ---------------
        // Africa's Talking posts to the voice webhook directly, no
        // browser, no session, no CSRF token possible
        // ---------------
        $middleware->validateCsrfTokens(except: [
            'voice/incoming',
            'voice/events',
            'voice/recording',
            'incoming.php',
            'events.php',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
