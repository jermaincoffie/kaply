<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'       => \App\Http\Middleware\CheckRole::class,
            'klant.auth' => \App\Http\Middleware\KlantAuth::class,
        ]);

        $middleware->validateCsrfTokens(except: ['stripe/webhook']);

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('boeken/*', 'mijn-afspraken', 'mijn-account')) {
                return route('klant.inloggen');
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if (!$request->expectsJson()) {
                if ($request->is('boeken/*', 'mijn-afspraken', 'mijn-account')) {
                    return redirect()->guest(route('klant.inloggen'));
                }
                return redirect()->guest(route('login'));
            }
        });
    })->create();
