<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // Truyền một mảng chứa cả 2 file route vào middleware 'web'
        web: [
            __DIR__.'/../routes/web.php',     // Route cho Admin
            __DIR__.'/../routes/client.php',  // Route cho Client
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Redirect unauthenticated users based on route prefix
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            // If accessing admin routes, redirect to admin login
            if ($request->routeIs('admin.*') || $request->is('admin*')) {
                return route('admin.auth.login');
            }
            // For client routes, redirect to client login
            return route('client.auth.login');
        });

        // Redirect authenticated users away from guest-only pages
        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            // If accessing admin pages, redirect to admin dashboard
            if ($request->routeIs('admin.*') || $request->is('admin*')) {
                return route('admin.dashboard');
            }
            // For client pages, redirect to client home
            return route('client.home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
