<?php

namespace Tests\Feature\Auth;

use App\Domains\Identity\Infrastructure\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class GoogleOAuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_callback_rejects_auto_link_to_admin_account(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@thceramics.com',
            'google_id' => null,
            'email_verified_at' => now(),
        ]);

        $this->mockGoogleUser('google-admin-1', 'admin@thceramics.com', 'Admin User');

        $response = $this->get(route('client.auth.google.callback'));

        $response->assertRedirect(route('client.auth.login'));
        $response->assertSessionHasErrors(['error']);

        // Check that admin was NOT logged in
        $this->assertGuest();

        // Check that admin record was NOT modified
        $freshAdmin = $admin->fresh();
        $this->assertNull($freshAdmin->google_id);
    }

    public function test_google_callback_rejects_auto_link_to_superadmin_account(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'superadmin@thceramics.com',
            'google_id' => null,
            'email_verified_at' => now(),
        ]);

        $this->mockGoogleUser('google-super-1', 'superadmin@thceramics.com', 'Super Admin');

        $response = $this->get(route('client.auth.google.callback'));

        $response->assertRedirect(route('client.auth.login'));
        $response->assertSessionHasErrors(['error']);

        $this->assertGuest();
        $freshSuper = $superadmin->fresh();
        $this->assertNull($freshSuper->google_id);
    }

    public function test_google_callback_rejects_existing_admin_matched_by_google_id(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@thceramics.com',
            'google_id' => 'existing-admin-gid',
            'email_verified_at' => now(),
        ]);

        $this->mockGoogleUser('existing-admin-gid', 'admin@thceramics.com', 'Admin User');

        $response = $this->get(route('client.auth.google.callback'));

        $response->assertRedirect(route('client.auth.login'));
        $response->assertSessionHasErrors(['error']);

        $this->assertGuest();
    }

    public function test_complete_google_registration_rejects_linking_to_admin(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@thceramics.com',
            'phone' => null,
        ]);

        $this->withSession([
            'google_user' => [
                'user_id' => $admin->id,
                'name' => 'Admin Test',
                'email' => 'admin@thceramics.com',
                'google_id' => 'google-admin-new',
            ],
        ]);

        $response = $this->post(route('client.auth.google.complete.post'), [
            'phone' => '0901234567',
        ]);

        $response->assertRedirect(route('client.auth.login'));
        $response->assertSessionHasErrors(['error']);

        $this->assertGuest();
        $freshAdmin = $admin->fresh();
        $this->assertNull($freshAdmin->phone);
        $this->assertNull($freshAdmin->google_id);
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
