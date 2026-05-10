<?php

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

// ─── Admin Flow ──────────────────────────────────────────────────────

test('admin forgot password page renders', function () {
    $this->get(route('admin.auth.forgot-password'))
        ->assertOk();
});

test('admin can request password reset link', function () {
    Notification::fake();

    $admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@test.com']);

    $this->post(route('admin.auth.forgot-password.submit'), [
        'email' => 'admin@test.com',
    ])->assertSessionHasNoErrors();

    Notification::assertSentTo($admin, ResetPasswordNotification::class);
});

test('admin reset password page renders with valid token', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $token = Password::createToken($admin);

    $this->get(route('admin.auth.reset-password', [
        'token' => $token,
        'email' => $admin->email,
    ]))->assertOk();
});

test('admin can reset password with valid token', function () {
    $admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@reset.com']);
    $token = Password::createToken($admin);

    $response = $this->post(route('admin.auth.reset-password.submit'), [
        'token' => $token,
        'email' => 'admin@reset.com',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertRedirect(route('admin.auth.login'));
    $response->assertSessionHas('success');

    $this->assertTrue(Hash::check('newpassword123', $admin->fresh()->password));
});

// ─── Client Flow ─────────────────────────────────────────────────────

test('client forgot password page renders', function () {
    $this->get(route('client.auth.forgot-password'))
        ->assertOk();
});

test('client can request password reset link', function () {
    Notification::fake();

    $customer = User::factory()->create(['role' => 'customer', 'email' => 'customer@test.com']);

    $this->post(route('client.auth.forgot-password.post'), [
        'email' => 'customer@test.com',
    ])->assertSessionHasNoErrors();

    Notification::assertSentTo($customer, ResetPasswordNotification::class);
});

test('client reset password page renders with valid token', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $token = Password::createToken($customer);

    $this->get(route('client.auth.reset-password', [
        'token' => $token,
        'email' => $customer->email,
    ]))->assertOk();
});

test('client can reset password with valid token', function () {
    $customer = User::factory()->create(['role' => 'customer', 'email' => 'cust@reset.com']);
    $token = Password::createToken($customer);

    $response = $this->post(route('client.auth.reset-password.post'), [
        'token' => $token,
        'email' => 'cust@reset.com',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertRedirect(route('client.auth.login'));
    $response->assertSessionHas('success');

    $this->assertTrue(Hash::check('newpassword123', $customer->fresh()->password));
});

// ─── Edge Cases ──────────────────────────────────────────────────────

test('reset password fails with expired token', function () {
    $user = User::factory()->create(['email' => 'expired@test.com']);
    $token = Password::createToken($user);

    $this->travelTo(now()->addMinutes(61));

    $response = $this->post(route('client.auth.reset-password.post'), [
        'token' => $token,
        'email' => 'expired@test.com',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('forgot password fails with non-existent email', function () {
    $response = $this->post(route('client.auth.forgot-password.post'), [
        'email' => 'nonexistent@test.com',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('reset password fails with invalid token', function () {
    $user = User::factory()->create(['email' => 'valid@test.com']);
    Password::createToken($user);

    $response = $this->post(route('client.auth.reset-password.post'), [
        'token' => 'invalid-fake-token-123',
        'email' => 'valid@test.com',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('reset password fails with mismatched confirmation', function () {
    $user = User::factory()->create(['email' => 'mismatch@test.com']);
    $token = Password::createToken($user);

    $response = $this->post(route('client.auth.reset-password.post'), [
        'token' => $token,
        'email' => 'mismatch@test.com',
        'password' => 'newpassword123',
        'password_confirmation' => 'differentpassword',
    ]);

    $response->assertSessionHasErrors(['password']);
});

test('reset password fails with short password', function () {
    $user = User::factory()->create(['email' => 'shortpw@test.com']);
    $token = Password::createToken($user);

    $response = $this->post(route('client.auth.reset-password.post'), [
        'token' => $token,
        'email' => 'shortpw@test.com',
        'password' => '123',
        'password_confirmation' => '123',
    ]);

    $response->assertSessionHasErrors(['password']);
});

// ─── Notification URL Routing ────────────────────────────────────────

test('reset notification generates correct url for admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $token = 'test-token-abc';

    $notification = new ResetPasswordNotification($token);
    $mailMessage = $notification->toMail($admin);

    expect($mailMessage->toArray()['actionUrl'])
        ->toContain('/admin/reset-password/')
        ->toContain(urlencode($admin->email));
});

test('reset notification generates correct url for customer', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $token = 'test-token-xyz';

    $notification = new ResetPasswordNotification($token);
    $mailMessage = $notification->toMail($customer);

    expect($mailMessage->toArray()['actionUrl'])
        ->toContain('/tai-khoan/dat-lai-mat-khau/')
        ->toContain(urlencode($customer->email));
});
