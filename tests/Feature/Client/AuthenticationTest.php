<?php

namespace Tests\Feature\Client;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Show login form
     */
    public function test_show_login_form(): void
    {
        $response = $this->get(route('client.auth.login'));

        $response->assertStatus(200);
        $response->assertViewIs('clients.auth.login');
    }

    /**
     * Test: Show register form
     */
    public function test_show_register_form(): void
    {
        $response = $this->get(route('client.auth.register'));

        $response->assertStatus(200);
        $response->assertViewIs('clients.auth.register');
    }

    /**
     * Test: Successful registration
     */
    public function test_register_with_valid_data(): void
    {
        $response = $this->post(route('client.auth.register.post'), [
            'name' => 'Nguyễn Văn A',
            'email' => 'test@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertRedirect(route('client.home'));
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Nguyễn Văn A',
            'role' => 'customer',
        ]);
        $this->assertAuthenticatedAs(User::where('email', 'test@example.com')->first());
    }

    /**
     * Test: Registration with invalid email
     */
    public function test_register_with_invalid_email(): void
    {
        $response = $this->post(route('client.auth.register.post'), [
            'name' => 'Nguyễn Văn A',
            'email' => 'invalid-email',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseMissing('users', ['email' => 'invalid-email']);
    }

    /**
     * Test: Registration with duplicate email
     */
    public function test_register_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post(route('client.auth.register.post'), [
            'name' => 'Nguyễn Văn B',
            'email' => 'test@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test: Registration with password mismatch
     */
    public function test_register_with_password_mismatch(): void
    {
        $response = $this->post(route('client.auth.register.post'), [
            'name' => 'Nguyễn Văn A',
            'email' => 'test@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'DifferentPassword123',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * Test: Successful login
     */
    public function test_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'Password123',
            'role' => 'customer',
        ]);

        $response = $this->post(route('client.auth.login.post'), [
            'email' => 'test@example.com',
            'password' => 'Password123',
        ]);

        $response->assertRedirect(route('client.home'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test: Login with invalid email
     */
    public function test_login_with_invalid_email(): void
    {
        $response = $this->post(route('client.auth.login.post'), [
            'email' => 'nonexistent@example.com',
            'password' => 'Password123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /**
     * Test: Login with invalid password
     */
    public function test_login_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'CorrectPassword123',
        ]);

        $response = $this->post(route('client.auth.login.post'), [
            'email' => 'test@example.com',
            'password' => 'WrongPassword123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /**
     * Test: Logout
     */
    public function test_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('client.auth.logout'));

        $response->assertRedirect(route('client.home'));
        $this->assertGuest();
    }

    /**
     * Test: Show forgot password form
     */
    public function test_show_forgot_password_form(): void
    {
        $response = $this->get(route('client.auth.forgot-password'));

        $response->assertStatus(200);
        $response->assertViewIs('clients.auth.forgot-password');
    }

    /**
     * Test: Send reset link with valid email
     */
    public function test_send_reset_link_with_valid_email(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post(route('client.auth.forgot-password.post'), [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHas('success');
    }

    /**
     * Test: Send reset link with non-existent email
     */
    public function test_send_reset_link_with_nonexistent_email(): void
    {
        $response = $this->post(route('client.auth.forgot-password.post'), [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test: Authenticated users redirected from login page
     */
    public function test_authenticated_user_redirected_from_login(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('client.auth.login'));

        $response->assertStatus(302);
        $response->assertRedirect();
    }

    /**
     * Test: Authenticated users redirected from register page
     */
    public function test_authenticated_user_redirected_from_register(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('client.auth.register'));

        $response->assertStatus(302);
        $response->assertRedirect();
    }
}
