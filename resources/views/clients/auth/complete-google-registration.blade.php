<x-layouts.client title="Hoàn tất đăng ký Google" main-class="bg-[#F8F5F0] min-h-screen flex flex-col">
    <section class="relative flex-grow flex items-center justify-center py-12 lg:py-20 overflow-hidden">
        <div class="absolute inset-0 pointer-events-none z-0">
            <img src="{{ asset('assets/images/background-decorate.svg') }}" alt=""
                class="absolute top-0 left-0 w-[300px] lg:w-[500px] opacity-10" style="transform: scaleX(-1);">
            <img src="{{ asset('assets/images/background-decorate.svg') }}" alt=""
                class="absolute bottom-0 right-0 w-[300px] lg:w-[500px] opacity-10">
        </div>

        <div class="relative z-10 w-[90%] max-w-[1000px] bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] overflow-hidden flex flex-col lg:flex-row"
            data-aos="fade-up">
            <div class="hidden lg:flex lg:w-1/2 relative bg-primary items-center justify-center p-12 overflow-hidden">
                <img src="{{ asset('assets/images/ngoi-01.jpg') }}" alt="TH Ceramics"
                    class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay">
                <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/30 to-black/80"></div>
                <div class="relative z-10 text-center flex flex-col items-center">
                    <a href="{{ route('client.home') }}">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="TH Ceramics"
                            class="w-32 h-32 mb-8 drop-shadow-lg">
                    </a>
                    <h2 class="text-white text-3xl font-bold uppercase tracking-wider mb-4 font-archivo">
                        Hoàn tất hồ sơ
                    </h2>
                    <p class="text-white/90 text-sm leading-relaxed font-roboto max-w-sm">
                        Bổ sung số điện thoại để TH Ceramics có thể hỗ trợ đơn hàng và tư vấn nhanh hơn.
                    </p>
                </div>
            </div>

            <div class="w-full lg:w-1/2 p-8 lg:p-14 flex flex-col justify-center">
                <div class="lg:hidden flex justify-center mb-6">
                    <a href="{{ route('client.home') }}">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="TH Ceramics"
                            class="w-20 h-20 bg-primary rounded-full p-2">
                    </a>
                </div>

                <div class="text-center lg:text-left mb-8">
                    <h1 class="text-2xl lg:text-3xl font-bold uppercase text-primary mb-2 font-archivo">
                        Hoàn tất đăng ký
                    </h1>
                    <p class="text-gray-500 text-sm font-roboto">Vui lòng bổ sung số điện thoại để tiếp tục.</p>
                </div>

                <form action="{{ route('client.auth.google.complete.post') }}" method="POST" class="space-y-5 font-roboto">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-primary mb-1">Họ và tên</label>
                        <input type="text" value="{{ $googleUser['name'] ?? '' }}" readonly
                            class="w-full px-4 py-3 border border-gray-200 rounded-md bg-gray-50 text-gray-600">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-primary mb-1">Email</label>
                        <input type="email" value="{{ $googleUser['email'] ?? '' }}" readonly
                            class="w-full px-4 py-3 border border-gray-200 rounded-md bg-gray-50 text-gray-600">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-primary mb-1">
                            Số điện thoại <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required autofocus
                            class="w-full px-4 py-3 border @error('phone') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-1 focus:ring-secondary focus:border-secondary transition-colors"
                            placeholder="Nhập số điện thoại của bạn">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-secondary text-white font-bold text-sm uppercase tracking-wider py-3.5 rounded-md hover:bg-[#a65b00] transition-colors shadow-md">
                        Hoàn tất
                    </button>
                </form>
            </div>
        </div>
    </section>
</x-layouts.client>
