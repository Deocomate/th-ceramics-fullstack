<?php

use App\Domains\Identity\Http\Admin\AuthController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// Admin routes
// ─────────────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // ── Guest routes (unauthenticated only) ──────────────────────────────────
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])
            ->name('auth.login');
        Route::post('login', [AuthController::class, 'login'])
            ->name('auth.login.submit');
        Route::get('forgot-password', [AuthController::class, 'showForgotPassword'])
            ->name('auth.forgot-password');
        Route::post('forgot-password', [AuthController::class, 'forgotPassword'])
            ->name('auth.forgot-password.submit');
        Route::get('reset-password/{token}', [AuthController::class, 'showResetPassword'])
            ->name('auth.reset-password');
        Route::post('reset-password', [AuthController::class, 'resetPassword'])
            ->name('auth.reset-password.submit');
    });

    // ── Authenticated routes ──────────────────────────────────────────────────
    Route::middleware(['auth', 'staged.images', 'content.writes'])->group(function () {
        Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('home');
        Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        require __DIR__.'/domains/media.php';

        require __DIR__.'/domains/content-sections.php';

        require __DIR__.'/domains/catalog.php';
        require __DIR__.'/domains/content.php';

        require __DIR__.'/domains/identity.php';
        require __DIR__.'/domains/archive.php';

        require __DIR__.'/domains/commerce.php';
    });
});
