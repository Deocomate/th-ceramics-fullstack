<?php

use App\Domains\Commerce\Http\Middleware\EnsureEcommerceEnabled;
use App\Domains\Content\Http\Middleware\EnsureContentWritesOpen;
use App\Domains\Identity\Http\Middleware\RoleMiddleware;
use App\Domains\Media\Http\Middleware\SubstituteStagedImages;
use App\Domains\Protection\Http\Middleware\SecurityHeaders;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

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
            'role' => RoleMiddleware::class,
            'verified' => EnsureEmailIsVerified::class,
            'ecommerce' => EnsureEcommerceEnabled::class,
            'staged.images' => SubstituteStagedImages::class,
            'content.writes' => EnsureContentWritesOpen::class,
        ]);

        $middleware->web(append: [SecurityHeaders::class]);

        $middleware->priority([
            EnsureEcommerceEnabled::class,
            Authenticate::class,
            EnsureEmailIsVerified::class,
        ]);

        // Redirect unauthenticated users based on route prefix
        $middleware->redirectGuestsTo(function (Request $request) {
            // If accessing admin routes, redirect to admin login
            if ($request->routeIs('admin.*') || $request->is('admin*')) {
                return route('admin.auth.login');
            }

            // For client routes, redirect to client login
            return route('client.auth.login');
        });

        // Redirect authenticated users away from guest-only pages
        $middleware->redirectUsersTo(function (Request $request) {
            // If accessing admin pages, redirect to admin dashboard
            if ($request->routeIs('admin.*') || $request->is('admin*')) {
                return route('admin.dashboard');
            }

            // For client pages, redirect to client home
            return route('client.home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            $message = 'Máy chủ từ chối dữ liệu tải lên vì quá lớn. Với ảnh, hãy đợi tối ưu xong rồi thử lưu; với video hoặc tài liệu, hãy chọn tệp nhỏ hơn.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => $message], 413);
            }

            return back()->withErrors(['images' => $message]);
        });
    })->create();
