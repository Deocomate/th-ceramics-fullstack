<?php

namespace Tests\Feature\Client;

use App\Models\User;
use App\Notifications\VerifyEmailQueued;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
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
        Notification::fake();

        $response = $this->post(route('client.auth.register.post'), [
            'name' => 'Nguyễn Văn A',
            'email' => 'test@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertRedirect(route('verification.notice'));
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Nguyễn Văn A',
            'role' => 'customer',
            'email_verified_at' => null,
        ]);
        $user = User::where('email', 'test@example.com')->first();
        $this->assertAuthenticatedAs($user);
        Notification::assertSentTo($user, VerifyEmailQueued::class, function ($notification) {
            return $notification instanceof ShouldQueue;
        });
    }

    public function test_user_can_verify_email_with_signed_link(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => null,
        ]);

        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]);

        $this->actingAs($user)
            ->get($url)
            ->assertRedirect(route('client.dich-vu.trang-thai-don-hang'));

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_verified_user_redirected_from_verification_notice(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('verification.notice'))
            ->assertRedirect(route('client.dich-vu.trang-thai-don-hang'));
    }

    public function test_resend_verification_when_already_verified(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->post(route('verification.send'))
            ->assertRedirect(route('client.dich-vu.trang-thai-don-hang'));
    }

    public function test_verification_status_returns_json(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => null,
        ]);

        $this->actingAs($user)
            ->getJson(route('verification.status'))
            ->assertOk()
            ->assertJson(['verified' => false]);
    }

    public function test_unverified_user_is_redirected_from_profile_and_checkout(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => null,
        ]);

        $this->actingAs($user)
            ->get(route('client.auth.profile'))
            ->assertRedirect(route('verification.notice'));

        $this->actingAs($user)
            ->get(route('client.cart.checkout'))
            ->assertRedirect(route('verification.notice'));
    }

    public function test_verified_user_can_reach_protected_profile(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('client.auth.profile'))
            ->assertOk();
    }

    public function test_resend_verification_sends_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => null,
        ]);

        $this->actingAs($user)
            ->post(route('verification.send'))
            ->assertSessionHas('success');

        Notification::assertSentTo($user, VerifyEmailQueued::class, function ($notification) {
            return $notification instanceof ShouldQueue;
        });
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

    public function test_login_with_remember_me(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'Password123',
            'role' => 'customer',
        ]);

        $this->post(route('client.auth.login.post'), [
            'email' => 'test@example.com',
            'password' => 'Password123',
            'remember' => '1',
        ])->assertRedirect(route('client.home'));

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->remember_token);
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

    public function test_google_callback_logs_in_existing_google_user(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'google@example.com',
            'google_id' => 'google-123',
            'email_verified_at' => now(),
        ]);

        $this->mockGoogleUser('google-123', 'google@example.com', 'Google User');

        $this->get(route('client.auth.google.callback'))
            ->assertRedirect(route('client.home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_google_callback_links_existing_verified_email(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'verified@example.com',
            'google_id' => null,
            'email_verified_at' => now(),
        ]);

        $this->mockGoogleUser('google-456', 'verified@example.com', 'Verified User');

        $this->get(route('client.auth.google.callback'))
            ->assertRedirect(route('client.home'));

        $this->assertAuthenticatedAs($user);
        $this->assertSame('google-456', $user->fresh()->google_id);
    }

    public function test_google_callback_force_updates_unverified_email_and_requires_phone(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'pending@example.com',
            'google_id' => null,
            'phone' => null,
            'email_verified_at' => null,
        ]);

        $this->mockGoogleUser('google-789', 'pending@example.com', 'Pending User');

        $this->get(route('client.auth.google.callback'))
            ->assertRedirect(route('client.auth.google.complete'))
            ->assertSessionHas('google_user');

        $freshUser = $user->fresh();
        $this->assertSame('google-789', $freshUser->google_id);
        $this->assertTrue($freshUser->hasVerifiedEmail());
        $this->assertSame(1, User::where('email', 'pending@example.com')->count());
    }

    public function test_new_google_user_completes_registration_with_phone(): void
    {
        $this->mockGoogleUser('google-new', 'new-google@example.com', 'New Google User');

        $this->get(route('client.auth.google.callback'))
            ->assertRedirect(route('client.auth.google.complete'));

        $this->post(route('client.auth.google.complete.post'), [
            'phone' => '0909123456',
        ])->assertRedirect(route('client.home'));

        $user = User::where('email', 'new-google@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('0909123456', $user->phone);
        $this->assertSame('google-new', $user->google_id);
        $this->assertTrue($user->hasVerifiedEmail());
        $this->assertAuthenticatedAs($user);
    }

    public function test_complete_google_registration_requires_session(): void
    {
        $this->get(route('client.auth.google.complete'))
            ->assertRedirect(route('client.auth.login'))
            ->assertSessionHasErrors();
    }

    public function test_avatar_url_handles_google_and_local_paths(): void
    {
        $googleUser = User::factory()->make([
            'avatar' => 'https://lh3.googleusercontent.com/avatar.png',
        ]);

        $localUser = User::factory()->make([
            'avatar' => 'users/avatars/photo.jpg',
        ]);

        $this->assertSame('https://lh3.googleusercontent.com/avatar.png', $googleUser->avatar_url);
        $this->assertStringContainsString('/storage/users/avatars/photo.jpg', $localUser->avatar_url);
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

    private function mockGoogleUser(string $id, string $email, string $name, ?string $avatar = 'https://example.com/avatar.png'): void
    {
        $googleUser = Mockery::mock(SocialiteUser::class);
        $googleUser->shouldReceive('getId')->andReturn($id);
        $googleUser->shouldReceive('getEmail')->andReturn($email);
        $googleUser->shouldReceive('getName')->andReturn($name);
        $googleUser->shouldReceive('getAvatar')->andReturn($avatar);

        $provider = Mockery::mock();
        $provider->shouldReceive('user')->once()->andReturn($googleUser);

        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturn($provider);
    }
}
