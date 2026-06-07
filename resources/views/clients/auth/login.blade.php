<x-client.layouts.main title="Đăng nhập tài khoản" main-class="bg-[#F8F5F0] min-h-screen flex flex-col">
    <!-- Phần Main Content Đăng Nhập -->
    <section class="relative flex-grow flex items-center justify-center py-12 lg:py-20 overflow-hidden">
        <!-- Họa tiết trang trí Background -->
        <div class="absolute inset-0 pointer-events-none z-0">
            <img src="{{ asset('assets/images/background-decorate.svg') }}" alt=""
                class="absolute top-0 left-0 w-[300px] lg:w-[500px] opacity-10" style="transform: scaleX(-1);">
            <img src="{{ asset('assets/images/background-decorate.svg') }}" alt=""
                class="absolute bottom-0 right-0 w-[300px] lg:w-[500px] opacity-10">
        </div>

        <!-- Form Container -->
        <div class="relative z-10 w-[90%] max-w-[1000px] bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] overflow-hidden flex flex-col lg:flex-row"
            data-aos="fade-up">

            <!-- Cột trái: Hình ảnh Branding (Chỉ hiện trên Desktop) -->
            <div class="hidden lg:flex lg:w-1/2 relative bg-primary items-center justify-center p-12 overflow-hidden">
                <img src="{{ asset('assets/images/home-hero-01.png') }}" alt="TH Ceramics"
                    class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay transition-transform duration-700 hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                <div class="relative z-10 text-center flex flex-col items-center">
                    <a href="{{ route('client.home') }}">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="TH Ceramics"
                            class="w-32 h-32 mb-8 drop-shadow-lg">
                    </a>
                    <h2 class="text-white text-3xl font-bold uppercase tracking-wider mb-4 font-archivo">Chào mừng trở
                        lại</h2>
                    <p class="text-white/90 text-sm leading-relaxed font-roboto max-w-xs">
                        Đăng nhập để quản lý đơn hàng, lưu lại các sản phẩm gốm sứ yêu thích và nhận ưu đãi đặc biệt từ
                        TH Ceramics.
                    </p>
                </div>
            </div>

            <!-- Cột phải: Form Đăng nhập -->
            <div class="w-full lg:w-1/2 p-8 lg:p-14 flex flex-col justify-center">

                <!-- Logo cho Mobile -->
                <div class="lg:hidden flex justify-center mb-6">
                    <a href="{{ route('client.home') }}">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="TH Ceramics"
                            class="w-20 h-20 bg-primary rounded-full p-2">
                    </a>
                </div>

                <div class="text-center lg:text-left mb-8">
                    <h1 class="text-2xl lg:text-3xl font-bold uppercase text-primary mb-2 font-archivo">Đăng nhập</h1>
                    <p class="text-gray-500 text-sm font-roboto">Vui lòng nhập thông tin tài khoản của bạn</p>
                </div>

                <!-- Hiển thị thông báo thành công hoặc lỗi chung -->
                @if (session('success'))
                    <div
                        class="mb-6 p-4 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->has('error'))
                    <div
                        class="mb-6 p-4 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $errors->first('error') }}
                    </div>
                @endif

                <form action="{{ route('client.auth.login.post') }}" method="POST" class="space-y-5 font-roboto">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-primary mb-1">Email <span
                                class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            autofocus
                            class="w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-1 focus:ring-secondary focus:border-secondary transition-colors"
                            placeholder="Nhập email của bạn">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-primary mb-1">Mật khẩu <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-3 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-1 focus:ring-secondary focus:border-secondary transition-colors pr-10"
                                placeholder="Nhập mật khẩu">
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-secondary focus:outline-none">
                                <!-- Eye Icon -->
                                <svg id="eyeIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <!-- Eye Slash Icon (Hidden by default) -->
                                <svg id="eyeSlashIcon" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember & Forgot Password -->
                    <div class="flex items-center justify-between mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 text-secondary border-gray-300 rounded focus:ring-secondary accent-secondary">
                            <span class="text-sm text-gray-600">Ghi nhớ đăng nhập</span>
                        </label>
                        <a href="{{ route('client.auth.forgot-password') }}"
                            class="text-sm font-semibold text-secondary hover:underline transition-all">Quên mật
                            khẩu?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full mt-6 bg-secondary text-white font-bold text-sm uppercase tracking-wider py-3.5 rounded-md hover:bg-[#a65b00] transition-colors shadow-md">
                        Đăng nhập
                    </button>
                </form>

                <!-- Divider -->
                <div class="flex items-center justify-center space-x-4 my-6">
                    <div class="h-px bg-gray-200 flex-grow"></div>
                    <span class="text-xs text-gray-400 uppercase tracking-widest">Hoặc đăng nhập bằng</span>
                    <div class="h-px bg-gray-200 flex-grow"></div>
                </div>

                <!-- Google Login Button -->
                <a href="{{ route('client.auth.google') }}"
                    class="w-full flex items-center justify-center gap-3 bg-white border border-gray-300 text-gray-700 font-semibold text-sm py-3 rounded-md hover:bg-gray-50 transition-colors shadow-sm mb-6">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            fill="#4285F4" />
                        <path
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            fill="#34A853" />
                        <path
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                            fill="#FBBC05" />
                        <path
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                            fill="#EA4335" />
                    </svg>
                    Tiếp tục với Google
                </a>

                <!-- Register Link -->
                <p class="text-center text-sm text-gray-600 mt-2 font-roboto">
                    Chưa có tài khoản?
                    <a href="{{ route('client.auth.register') }}"
                        class="font-bold text-secondary hover:underline transition-all">Đăng ký ngay</a>
                </p>

            </div>
        </div>
    </section>
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const togglePassword = document.getElementById('togglePassword');
                const passwordInput = document.getElementById('password');
                const eyeIcon = document.getElementById('eyeIcon');
                const eyeSlashIcon = document.getElementById('eyeSlashIcon');

                if (togglePassword && passwordInput) {
                    togglePassword.addEventListener('click', function(e) {
                        // Toggle the type attribute
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordInput.setAttribute('type', type);

                        // Toggle icons
                        eyeIcon.classList.toggle('hidden');
                        eyeSlashIcon.classList.toggle('hidden');
                    });
                }
            });
        </script>
    @endpush
</x-client.layouts.main>
