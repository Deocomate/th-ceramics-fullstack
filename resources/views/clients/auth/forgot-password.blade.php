<x-layouts.client title="Quên mật khẩu" main-class="bg-[#F8F5F0] min-h-screen flex flex-col">
    <!-- Phần Main Content Quên Mật Khẩu -->
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
                <img src="{{ asset('assets/images/showroom-01.png') }}" alt="TH Ceramics"
                    class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay transition-transform duration-700 hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                <div class="relative z-10 text-center flex flex-col items-center">
                    <a href="{{ route('client.home') }}">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="TH Ceramics"
                            class="w-32 h-32 mb-8 drop-shadow-lg">
                    </a>
                    <h2 class="text-white text-3xl font-bold uppercase tracking-wider mb-4 font-archivo">Khôi phục tài
                        khoản</h2>
                    <p class="text-white/90 text-sm leading-relaxed font-roboto max-w-xs">
                        Đừng lo lắng! Việc quên mật khẩu là rất bình thường. Hãy cung cấp email cho chúng tôi để lấy lại
                        quyền truy cập vào tài khoản của bạn.
                    </p>
                </div>
            </div>

            <!-- Cột phải: Form Quên mật khẩu -->
            <div class="w-full lg:w-1/2 p-8 lg:p-14 flex flex-col justify-center">

                <!-- Logo cho Mobile -->
                <div class="lg:hidden flex justify-center mb-6">
                    <a href="{{ route('client.home') }}">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="TH Ceramics"
                            class="w-20 h-20 bg-primary rounded-full p-2">
                    </a>
                </div>

                <div class="text-center lg:text-left mb-8">
                    <div
                        class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-orange-50 mb-4 lg:hidden">
                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                            </path>
                        </svg>
                    </div>
                    <h1 class="text-2xl lg:text-3xl font-bold uppercase text-primary mb-2 font-archivo">Quên mật khẩu?
                    </h1>
                    <p class="text-gray-500 text-sm font-roboto">Nhập địa chỉ email đã đăng ký của bạn và chúng tôi sẽ
                        gửi cho bạn một liên kết để đặt lại mật khẩu.</p>
                </div>

                <!-- Hiển thị thông báo thành công (Laravel dùng key 'status' hoặc 'success' cho password reset) -->
                @if (session('status') || session('success'))
                    <div
                        class="mb-6 p-4 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm flex items-start gap-3 font-roboto">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('status') ?? session('success') }}</span>
                    </div>
                @endif

                <!-- Hiển thị lỗi chung -->
                @if ($errors->has('error'))
                    <div
                        class="mb-6 p-4 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm flex items-start gap-3 font-roboto">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $errors->first('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('client.auth.forgot-password.post') }}" method="POST"
                    class="space-y-6 font-roboto">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-primary mb-1">Email đã đăng ký
                            <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                autofocus
                                class="w-full pl-11 pr-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-1 focus:ring-secondary focus:border-secondary transition-colors"
                                placeholder="name@example.com">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-secondary text-white font-bold text-sm uppercase tracking-wider py-3.5 rounded-md hover:bg-[#a65b00] transition-colors shadow-md">
                        Gửi liên kết khôi phục
                    </button>
                </form>

                <!-- Back to Login Link -->
                <div class="mt-8 text-center">
                    <a href="{{ route('client.auth.login') }}"
                        class="inline-flex items-center justify-center gap-2 text-sm font-semibold text-gray-500 hover:text-secondary transition-colors font-roboto group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Quay lại trang đăng nhập
                    </a>
                </div>

            </div>
        </div>
    </section>
</x-layouts.client>
