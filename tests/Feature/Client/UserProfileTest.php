<?php

namespace Tests\Feature\Client;

use App\Domains\Identity\Infrastructure\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_profile_page(): void
    {
        $response = $this->get(route('client.auth.profile'));

        $response->assertRedirect(route('client.auth.login'));
    }

    public function test_unverified_user_is_redirected_to_verification_notice(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('client.auth.profile'));

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_verified_user_can_view_profile_page(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
            'name' => 'Nguyễn Văn Test',
        ]);

        $response = $this->actingAs($user)->get(route('client.auth.profile'));

        $response->assertOk();
        $response->assertViewIs('clients.identity.dich-vu-khach-hang.tai-khoan-cua-toi');
        $response->assertSee('Nguyễn Văn Test');
    }

    public function test_user_can_update_profile_information(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
            'name' => 'Tên Cũ',
            'email' => 'tencu@example.com',
            'phone' => '0901111111',
            'gender' => 'male',
            'birth_year' => 1990,
        ]);

        $response = $this->actingAs($user)->post(route('client.auth.profile.update'), [
            'name' => 'Tên Mới',
            'email' => 'tenmoi@example.com',
            'phone' => '0902222222',
            'gender' => 'female',
            'birth_year' => 1995,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success_profile', 'Cập nhật thông tin tài khoản thành công.');

        $freshUser = $user->fresh();
        $this->assertSame('Tên Mới', $freshUser->name);
        $this->assertSame('tenmoi@example.com', $freshUser->email);
        $this->assertSame('0902222222', $freshUser->phone);
        $this->assertSame('female', $freshUser->gender);
        $this->assertSame(1995, $freshUser->birth_year);
    }

    public function test_update_profile_validation_prevents_duplicate_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
            'email' => 'myemail@example.com',
        ]);

        $response = $this->actingAs($user)->post(route('client.auth.profile.update'), [
            'name' => 'User Name',
            'email' => 'existing@example.com',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertSame('myemail@example.com', $user->fresh()->email);
    }

    public function test_user_can_update_avatar(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
            'avatar' => null,
        ]);

        $file = UploadedFile::fake()->image('my-avatar.jpg', 200, 200);

        $response = $this->actingAs($user)->post(route('client.auth.profile.update-avatar'), [
            'avatar' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success_profile', 'Cập nhật ảnh đại diện thành công.');

        $freshUser = $user->fresh();
        $this->assertNotNull($freshUser->avatar);
        $this->assertStringStartsWith('users/avatars/', $freshUser->avatar);
        Storage::disk('public')->assertExists($freshUser->avatar);
    }

    public function test_user_can_change_password_with_correct_current_password(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->post(route('client.auth.password.update'), [
            'current_password' => 'oldpassword123',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success_password', 'Đổi mật khẩu thành công.');

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_user_cannot_change_password_with_incorrect_current_password(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->post(route('client.auth.password.update'), [
            'current_password' => 'wrongpassword',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $this->assertTrue(Hash::check('oldpassword123', $user->fresh()->password));
    }

    public function test_oauth_user_without_password_can_set_password_without_current_password(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
            'password' => null,
            'google_id' => 'google-oauth-123',
        ]);

        $response = $this->actingAs($user)->post(route('client.auth.password.update'), [
            'new_password' => 'firstpassword123',
            'new_password_confirmation' => 'firstpassword123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success_password', 'Đổi mật khẩu thành công.');

        $this->assertTrue(Hash::check('firstpassword123', $user->fresh()->password));
    }
}
