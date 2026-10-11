<?php

namespace Tests\Feature\Admin;

use App\Domains\Content\Http\Middleware\EnsureContentWritesOpen;
use App\Domains\Identity\Infrastructure\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminRoleGateSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_is_denied_from_admin_dashboard(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_customer_is_denied_from_admin_home_redirect(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_customer_is_denied_from_catalog_admin_routes(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get('/admin/ngoi-am-duong-ct');

        $response->assertStatus(403);
    }

    public function test_customer_is_denied_from_content_admin_routes(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get('/admin/trang-chu');

        $response->assertStatus(403);
    }

    public function test_customer_is_denied_from_commerce_admin_routes(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get('/admin/orders');

        $response->assertStatus(403);
    }

    public function test_customer_is_denied_from_archive_admin_routes(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get('/admin/content-archive');

        $response->assertStatus(403);
    }

    public function test_customer_is_denied_from_media_staged_uploads(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        Storage::fake('local');
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $response = $this->actingAs($customer)->post(route('admin.media.staged-images.store'), [
            'file' => $file,
        ]);

        $response->assertStatus(403);
    }

    public function test_role_gate_runs_before_staged_images_middleware(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        // Customer sends a request with staged image tokens
        // Because role gate runs before SubstituteStagedImages, customer is blocked immediately with 403
        $response = $this->actingAs($customer)->post('/admin/ngoi-am-duong-ct', [
            'staged_main_image' => 'staged-token-xyz',
            'ten_san_pham' => 'Test',
        ]);

        $response->assertStatus(403);
    }

    public function test_role_gate_runs_before_content_writes_lock_middleware(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        // Lock content writes via lock file
        $path = storage_path(EnsureContentWritesOpen::LOCK_FILE);
        File::ensureDirectoryExists(dirname($path));
        file_put_contents($path, 'test');
        try {
            // A customer request should be blocked with 403 (role denied), NOT 423 (content locked)
            $response = $this->actingAs($customer)->post(route('admin.ngoi-am-duong-ct.store'), [
                'ten_san_pham' => 'New Product',
            ]);

            $response->assertStatus(403);
        } finally {
            @unlink($path);
        }
    }

    public function test_customer_cannot_login_through_admin_login(): void
    {
        User::factory()->create([
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->post(route('admin.auth.login.submit'), [
            'email' => 'customer@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_admin_user_can_login_and_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('admin.auth.login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        $dashResponse = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dashResponse->assertStatus(200);
    }

    public function test_admin_user_cannot_access_superadmin_user_management(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_superadmin_user_can_access_all_admin_routes_including_user_management(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
        ]);

        $response = $this->actingAs($superadmin)->get('/admin/users');

        $response->assertStatus(200);
    }
}
