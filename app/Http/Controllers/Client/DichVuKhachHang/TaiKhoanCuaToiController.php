<?php

namespace App\Http\Controllers\Client\DichVuKhachHang;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TaiKhoanCuaToiController extends Controller
{
    /**
     * Hiển thị trang Quản lý tài khoản
     */
    public function index()
    {
        $user = Auth::user();

        // Trả về đúng view yêu cầu
        return view('clients.dich-vu-khach-hang.tai-khoan-cua-toi', compact('user'));
    }

    // 1. HÀM XỬ LÝ UPLOAD AVATAR TỰ ĐỘNG
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ], [
            'avatar.required' => 'Vui lòng chọn ảnh.',
            'avatar.image' => 'File tải lên phải là hình ảnh.',
            'avatar.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'avatar.max' => 'Kích thước ảnh tối đa là 2MB.',
        ]);

        $avatarPath = FileUploadHelper::replace(
            $request->file('avatar'),
            $user->avatar,
            'users/avatars'
        );

        $user->update(['avatar' => $avatarPath]);

        return back()->with('success_profile', 'Cập nhật ảnh đại diện thành công.');
    }

    // 2. HÀM CẬP NHẬT THÔNG TIN CÁ NHÂN (Đã bỏ phần avatar)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:male,female,other'],
            'birth_year' => ['nullable', 'integer', 'min:1900', 'max:'.date('Y')],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã được sử dụng.',
            'birth_year.integer' => 'Năm sinh phải là số.',
        ]);

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
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Kiểm tra xem user có mật khẩu không (Trường hợp đăng nhập bằng Google OAuth lần đầu sẽ null)
        $hasPassword = ! empty($user->password);

        // 1. Khởi tạo Rules
        $rules = [
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        // Nếu user đã có mật khẩu, bắt buộc phải nhập mật khẩu cũ cho an toàn
        if ($hasPassword) {
            $rules['current_password'] = ['required', 'current_password'];
            // Rule 'current_password' của Laravel tự động kiểm tra xem có khớp Hash với Auth::user() không
        }

        // 2. Validate dữ liệu
        $request->validate($rules, [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'current_password.current_password' => 'Mật khẩu hiện tại không chính xác.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        // 3. Cập nhật mật khẩu mới
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Quay lại trang hiện tại với thông báo thành công (Flash Session key: 'success_password')
        return back()->with('success_password', 'Đổi mật khẩu thành công.');
    }
}
