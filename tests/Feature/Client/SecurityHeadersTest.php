<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Testing\TestResponse;

function assertSecurityHeaders(TestResponse $response): void
{
    $response
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('Content-Security-Policy', "frame-ancestors 'self'")
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
}

test('home page sends security headers', function () {
    assertSecurityHeaders($this->get(route('client.home'))->assertOk());
});

test('admin login page sends security headers', function () {
    assertSecurityHeaders($this->get(route('admin.auth.login'))->assertOk());
});

test('redirect responses send security headers', function () {
    assertSecurityHeaders($this->get(route('admin.dashboard'))->assertRedirect(route('admin.auth.login')));
});

test('security headers already set by a response are kept', function () {
    Route::middleware('web')->get('/__security-headers-probe', fn () => response('ok')
        ->header('X-Frame-Options', 'DENY')
        ->header('Content-Security-Policy', "frame-ancestors 'none'"));

    $this->get('/__security-headers-probe')
        ->assertOk()
        ->assertHeader('X-Frame-Options', 'DENY')
        ->assertHeader('Content-Security-Policy', "frame-ancestors 'none'")
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
});
