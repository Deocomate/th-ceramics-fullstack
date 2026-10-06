<?php

namespace App\Domains\Identity\Http\Client;

use App\Domains\Identity\Http\Requests\Client\UpdateAvatarRequest;
use App\Domains\Identity\Http\Requests\Client\UpdatePasswordRequest;
use App\Domains\Identity\Http\Requests\Client\UpdateProfileRequest;
use App\Domains\Media\Infrastructure\FileUploadHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    /**
     * Hiển thị trang Quản lý tài khoản
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('clients.identity.dich-vu-khach-hang.tai-khoan-cua-toi', compact('user'));
    }

    /**
     * Xử lý tải lên avatar tự động
     */
    public function updateAvatar(UpdateAvatarRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $avatarPath = FileUploadHelper::replace(
            $request->file('avatar'),
            $user->avatar,
            'users/avatars'
        );

        $user->update(['avatar' => $avatarPath]);

        return back()->with('success_profile', 'Cập nhật ảnh đại diện thành công.');
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $validated = $request->validated();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'birth_year' => $validated['birth_year'] ?? null,
        ]);

        return back()->with('success_profile', 'Cập nhật thông tin tài khoản thành công.');
    }

    /**
     * Thay đổi mật khẩu
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->validated('new_password')),
        ]);

        return back()->with('success_password', 'Đổi mật khẩu thành công.');
    }
}
