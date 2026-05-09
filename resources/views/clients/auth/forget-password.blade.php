@extends('layouts.app')

@section('title', 'Quên Mật Khẩu - Th Ceramics')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Quên Mật Khẩu?</h1>
            <p class="text-slate-600">Vui lòng nhập email của bạn để nhận liên kết đặt lại mật khẩu</p>
        </div>

        <!-- Forgot Password Form -->
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

            <form action="{{ route('client.auth.forgot-password.post') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('email') border-red-500 @enderror"
                        placeholder="your@email.com"
                    >
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Message -->
                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-blue-800 text-sm">
                        Chúng tôi sẽ gửi một liên kết đặt lại mật khẩu đến email của bạn. Vui lòng kiểm tra thư mục Spam nếu bạn không nhận được email.
                    </p>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200"
                >
                    Gửi Liên Kết Đặt Lại Mật Khẩu
                </button>
            </form>

            <!-- Footer Links -->
            <div class="mt-6 text-center space-y-2">
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
