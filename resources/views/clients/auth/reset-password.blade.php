<x-layouts.client title="Đặt lại mật khẩu" main-class="bg-[#F8F5F0] min-h-screen flex flex-col">
    <!-- Phần Main Content Đặt Lại Mật Khẩu -->
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
                <!-- Hình ảnh mang tính chất bảo mật, an tâm -->
                <img src="{{ asset('assets/images/ngoi-03.jpg') }}" alt="TH Ceramics"
                    class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay transition-transform duration-700 hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>

                <div class="relative z-10 text-center flex flex-col items-center">
                    <a href="{{ route('client.home') }}">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="TH Ceramics"
                            class="w-32 h-32 mb-8 drop-shadow-lg">
                    </a>
                    <h2 class="text-white text-3xl font-bold uppercase tracking-wider mb-4 font-archivo">Bảo mật tài
                        khoản</h2>
                    <p class="text-white/90 text-sm leading-relaxed font-roboto max-w-sm">
                        Hãy thiết lập một mật khẩu mạnh và dễ nhớ đối với bạn để tiếp tục trải nghiệm các sản phẩm và
                        dịch vụ của TH Ceramics.
                    </p>
                </div>
            </div>

            <!-- Cột phải: Form Đặt lại mật khẩu -->
            <div class="w-full lg:w-1/2 p-8 lg:p-14 flex flex-col justify-center">

                <!-- Logo cho Mobile -->
                <div class="lg:hidden flex justify-center mb-6">
                    <a href="{{ route('client.home') }}">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="TH Ceramics"
                            class="w-20 h-20 bg-primary rounded-full p-2">
                    </a>
                </div>

                <div class="text-center lg:text-left mb-8">
                    <!-- Icon Khóa bảo mật -->
                    <div
                        class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-orange-50 mb-4 lg:hidden">
                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h1 class="text-2xl lg:text-3xl font-bold uppercase text-primary mb-2 font-archivo">Đặt lại mật khẩu
                    </h1>
                    <p class="text-gray-500 text-sm font-roboto">Vui lòng nhập mật khẩu mới cho tài khoản của bạn</p>
                </div>

                <!-- Hiển thị lỗi chung nếu có (ví dụ Token hết hạn) -->
                @if ($errors->has('error') || $errors->has('email'))
                    <div
                        class="mb-6 p-4 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm flex items-start gap-3 font-roboto">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $errors->first('error') ?: $errors->first('email') }}</span>
                    </div>
                @endif

                <form action="{{ route('client.auth.reset-password.post') }}" method="POST"
                    class="space-y-5 font-roboto">
                    @csrf

                    <!-- Truyền Token ẩn -->
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Input (Thường chỉ đọc để xác nhận tài khoản) -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-primary mb-1">Email tài
                            khoản</label>
                        <input type="email" id="email" name="email"
                            value="{{ old('email', $email ?? request()->email) }}" readonly
                            class="w-full px-4 py-3 border border-gray-200 rounded-md bg-gray-50 text-gray-500 cursor-not-allowed focus:outline-none transition-colors">
                    </div>

                    <!-- New Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-primary mb-1">Mật khẩu mới <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required autofocus
                                class="w-full px-4 py-3 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-1 focus:ring-secondary focus:border-secondary transition-colors pr-10"
                                placeholder="Tối thiểu 8 ký tự">
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-secondary focus:outline-none">
                                <svg id="eyeIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
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

                    <!-- Confirm New Password Input -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-primary mb-1">Xác
                            nhận mật khẩu mới <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-secondary focus:border-secondary transition-colors pr-10"
                                placeholder="Nhập lại mật khẩu mới">
                            <button type="button" id="togglePasswordConfirm"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-secondary focus:outline-none">
                                <svg id="eyeIconConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeSlashIconConfirm" class="h-5 w-5 hidden" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full mt-2 bg-secondary text-white font-bold text-sm uppercase tracking-wider py-3.5 rounded-md hover:bg-[#a65b00] transition-colors shadow-md">
                        Lưu mật khẩu & Đăng nhập
                    </button>
                </form>

            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Hàm xử lý ẩn/hiện chung cho ô mật khẩu
                function setupPasswordToggle(toggleBtnId, inputId, eyeIconId, eyeSlashIconId) {
                    const toggleBtn = document.getElementById(toggleBtnId);
                    const input = document.getElementById(inputId);
                    const eye = document.getElementById(eyeIconId);
                    const eyeSlash = document.getElementById(eyeSlashIconId);

                    if (toggleBtn && input) {
                        toggleBtn.addEventListener('click', function() {
                            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                            input.setAttribute('type', type);

                            eye.classList.toggle('hidden');
                            eyeSlash.classList.toggle('hidden');
                        });
                    }
                }

                // Kích hoạt cho ô Mật khẩu mới
                setupPasswordToggle('togglePassword', 'password', 'eyeIcon', 'eyeSlashIcon');

                // Kích hoạt cho ô Xác nhận mật khẩu mới
                setupPasswordToggle('togglePasswordConfirm', 'password_confirmation', 'eyeIconConfirm',
                    'eyeSlashIconConfirm');
            });
        </script>
    @endpush

</x-layouts.client>
