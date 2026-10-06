<x-client.layouts.main title="Xác thực email" main-class="bg-[#F8F5F0] min-h-screen flex flex-col">
    <section class="relative flex-grow flex items-center justify-center py-12 lg:py-20 overflow-hidden">
        <div class="absolute inset-0 pointer-events-none z-0">
            <img src="{{ asset('assets/images/background-decorate.svg') }}" alt=""
                class="absolute top-0 left-0 w-[300px] lg:w-[500px] opacity-10" style="transform: scaleX(-1);">
            <img src="{{ asset('assets/images/background-decorate.svg') }}" alt=""
                class="absolute bottom-0 right-0 w-[300px] lg:w-[500px] opacity-10">
        </div>

        <div class="relative z-10 w-[90%] max-w-[760px] bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] overflow-hidden"
            data-aos="fade-up">
            <div class="p-8 lg:p-12 text-center">
                <a href="{{ route('client.home') }}" class="inline-flex justify-center mb-6">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="TH Ceramics"
                        class="w-20 h-20 bg-primary rounded-full p-2">
                </a>

                <h1 class="text-2xl lg:text-3xl font-bold uppercase text-primary mb-3 font-archivo">
                    Xác thực email
                </h1>
                <p class="text-gray-600 text-sm leading-relaxed font-roboto max-w-xl mx-auto">
                    Chúng tôi đã gửi liên kết xác thực đến email của bạn. Vui lòng kiểm tra hộp thư và xác thực tài
                    khoản trước khi truy cập hồ sơ hoặc thanh toán.
                </p>

                @if (session('success'))
                    <div class="mt-6 p-4 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
                    <form action="{{ route('verification.send') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-3 bg-secondary text-white font-bold text-sm uppercase tracking-wider rounded-md hover:bg-[#a65b00] transition-colors shadow-md">
                            Gửi lại email
                        </button>
                    </form>

                    <form action="{{ route('client.auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-3 bg-white border border-gray-300 text-gray-700 font-bold text-sm uppercase tracking-wider rounded-md hover:bg-gray-50 transition-colors">
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const statusUrl = @json(route('verification.status'));
                const redirectUrl = @json(route('client.dich-vu.trang-thai-don-hang'));

                const checkVerification = async () => {
                    try {
                        const response = await fetch(statusUrl, {
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin',
                        });

                        if (!response.ok) {
                            return;
                        }

                        const data = await response.json();

                        if (data.verified) {
                            window.location.href = redirectUrl;
                        }
                    } catch (error) {
                        // Ignore transient network errors; next poll will retry.
                    }
                };

                checkVerification();
                setInterval(checkVerification, 5000);
            });
        </script>
    @endpush
</x-client.layouts.main>
