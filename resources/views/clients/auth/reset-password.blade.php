@extends('layouts.app')

@section('title', 'Đặt Lại Mật Khẩu - Th Ceramics')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Đặt Lại Mật Khẩu</h1>
            <p class="text-slate-600">Vui lòng tạo mật khẩu mới cho tài khoản của bạn</p>
        </div>

        <!-- Reset Password Form -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="text-red-800 text-sm font-medium">Có lỗi xảy ra:</div>
                    <ul class="text-red-700 text-sm mt-2 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800 text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('client.auth.reset-password.post') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Token (Hidden) -->
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ $email ?? old('email') }}"
                        required
                        readonly
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-slate-50 cursor-not-allowed @error('email') border-red-500 @enderror"
                    >
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Mật Khẩu Mới</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('password') border-red-500 @enderror"
                        placeholder="Tối thiểu 8 ký tự"
                    >
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">Xác Nhận Mật Khẩu Mới</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('password_confirmation') border-red-500 @enderror"
                        placeholder="Nhập lại mật khẩu"
                    >
                    @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Requirements -->
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg">
                    <p class="text-slate-700 text-sm font-medium mb-2">Yêu cầu mật khẩu:</p>
                    <ul class="text-slate-600 text-sm space-y-1">
                        <li>✓ Tối thiểu 8 ký tự</li>
                        <li>✓ Chứa chữ hoa, chữ thường, số và ký tự đặc biệt (tuỳ chọn)</li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200"
                >
                    Đặt Lại Mật Khẩu
                </button>
            </form>

            <!-- Footer Links -->
            <div class="mt-6 text-center">
                <p class="text-slate-600 text-sm">
                    <a href="{{ route('client.auth.login') }}" class="text-amber-600 hover:text-amber-700 font-medium">
                        Quay lại đăng nhập
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
