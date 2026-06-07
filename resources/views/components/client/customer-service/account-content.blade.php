@props([
    'trangChu' => null,
    'ngoiAmDuongs' => null,
    'ngoiHais' => null,
    'gachHoas' => null,
    'about' => null,
    'factory' => null,
    'showroomImages' => null,
    'showroomContent' => null,
    'news' => null,
    'article' => null,
    'articles' => null,
    'relatedArticles' => null,
    'historyArticles' => null,
    'projects' => null,
    'project' => null,
    'relatedProjects' => null,
    'categories' => null,
    'selectedCategory' => null,
    'currentCategory' => null,
    'config' => null,
    'products' => null,
    'relatedProducts' => null,
    'product' => null,
    'colors' => null,
    'dinhMuc' => null,
    'giaTriVuotTroi' => null,
    'parentConfig' => null,
    'pageLabel' => null,
    'indexRouteName' => null,
    'categoryType' => null,
    'categoryLabel' => null,
    'denGomProducts' => null,
    'denSuProducts' => null,
    'featuredProducts' => null,
    'collectionProducts' => null,
    'ngheProducts' => null,
    'linhVatProducts' => null,
    'bgImage' => null,
    'activeOrder' => false,
    'activeAccount' => false,
    'activeCatalog' => false,
    'activeGuide' => false,
    'activeProcess' => false,
    'activePrivacy' => false,
    'activeReturn' => false,
    'activeShipping' => false,
    'image' => null,
    'label1' => null,
    'rate1' => null,
    'label2' => null,
    'rate2' => null,
    'sectionId' => null,
    'sectionClass' => null,
    'sectionTitle' => null,
    'desktopLinkHref' => null,
    'detailRouteName' => null,
    'wrapperClass' => null,
    'titleClass' => null,
    'title' => null,
    'subtitle' => null,
    'description' => null,
    'items' => null,])
<div class="flex-1 lg:pl-12">
    <h1 class="text-3xl lg:text-[36px] font-arima font-medium text-primary mb-10 mt-[-6px]">Tài khoản của tôi</h1>

    <div class="bg-[#F8F3EF] border border-gray-300 rounded-md shadow-lg py-6 lg:py-8 px-6 lg:px-16 mb-8">
        <h2 class="text-center text-secondary font-bold text-lg lg:text-xl uppercase tracking-wider mb-6">Hồ sơ cá nhân
        </h2>

        <!-- Thông báo thành công Cập nhật hồ sơ/Avatar -->
        @if (session('success_profile'))
            <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-100 border border-green-200">
                {{ session('success_profile') }}
            </div>
        @endif

        <!-- FORM 1: UPLOAD AVATAR (Tự động submit bằng JS) -->
        <form id="form-avatar" action="{{ route('client.auth.profile.update-avatar') }}" method="POST"
            enctype="multipart/form-data"
            class="flex flex-col items-center mb-8 pb-6 border-b border-gray-300 max-w-[800px] mx-auto">
            @csrf
            <div class="relative w-24 h-24 mb-3 rounded-full overflow-hidden border-2 border-secondary/50">
                @if (Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar_url }}" alt="Avatar"
                        class="w-full h-full object-cover">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=EBDDD0&color=2E2F2A"
                        alt="Avatar" class="w-full h-full object-cover">
                @endif
            </div>
            <label class="cursor-pointer text-sm text-secondary hover:underline font-archivo">
                Thay đổi ảnh đại diện
                <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*">
            </label>
            <div id="avatar-loading" class="hidden text-xs text-gray-500 mt-1 animate-pulse">Đang tải ảnh lên...</div>
            @error('avatar')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </form>

        <!-- FORM 2: CẬP NHẬT THÔNG TIN CHUNG -->
        <form action="{{ route('client.auth.profile.update') }}" method="POST" class="max-w-[800px] mx-auto space-y-5">
            @csrf

            <!-- Họ tên -->
            <div class="flex flex-col lg:flex-row lg:items-start gap-2 lg:gap-8">
                <label class="lg:w-40 text-sm text-primary font-archivo lg:mt-3">Họ tên*</label>
                <div class="flex-1">
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                        class="w-full text-sm bg-white border border-gray-300 rounded-md px-5 py-3 text-primary focus:outline-none focus:border-secondary transition-colors font-archivo">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Số điện thoại -->
            <div class="flex flex-col lg:flex-row lg:items-start gap-2 lg:gap-8">
                <label class="lg:w-40 text-sm text-primary font-archivo lg:mt-3">Số điện thoại</label>
                <div class="flex-1">
                    <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                        class="w-full text-sm bg-white border border-gray-300 rounded-md px-5 py-3 text-primary focus:outline-none focus:border-secondary transition-colors font-archivo">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div class="flex flex-col lg:flex-row lg:items-start gap-2 lg:gap-8">
                <label class="lg:w-40 text-sm text-primary font-archivo lg:mt-3">Email*</label>
                <div class="flex-1">
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                        class="w-full text-sm bg-white border border-gray-300 rounded-md px-5 py-3 text-primary focus:outline-none focus:border-secondary transition-colors font-archivo">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Giới tính -->
            <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-8">
                <label class="lg:w-40 text-sm text-primary font-archivo">Giới tính</label>
                <div class="flex items-center gap-12">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input type="radio" name="gender" value="female" @checked(old('gender', Auth::user()->gender) == 'female')
                                class="peer sr-only">
                            <div
                                class="w-5 h-5 border border-primary rounded-full peer-checked:border-primary transition-all">
                            </div>
                            <div
                                class="absolute w-3 h-3 bg-primary rounded-full scale-0 peer-checked:scale-100 transition-transform">
                            </div>
                        </div>
                        <span class="text-base text-primary font-archivo">Nữ</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input type="radio" name="gender" value="male" @checked(old('gender', Auth::user()->gender) == 'male')
                                class="peer sr-only">
                            <div
                                class="w-5 h-5 border border-primary rounded-full peer-checked:border-primary transition-all">
                            </div>
                            <div
                                class="absolute w-3 h-3 bg-primary rounded-full scale-0 peer-checked:scale-100 transition-transform">
                            </div>
                        </div>
                        <span class="text-base text-primary font-archivo">Nam</span>
                    </label>
                </div>
            </div>

            <!-- Năm sinh -->
            <div class="flex flex-col lg:flex-row lg:items-start gap-2 lg:gap-8">
                <label class="lg:w-40 text-sm text-primary font-archivo lg:mt-3">Năm sinh</label>
                <div class="flex-1">
                    <input type="number" name="birth_year" value="{{ old('birth_year', Auth::user()->birth_year) }}"
                        class="w-full text-sm bg-white border border-gray-300 rounded-md px-5 py-3 text-primary focus:outline-none focus:border-secondary transition-colors font-archivo">
                    @error('birth_year')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center pt-4">
                <button type="submit"
                    class="min-w-[150px] lg:min-w-[200px] py-2 bg-secondary text-white text-lg font-bold rounded-md hover:opacity-90 transition-opacity font-archivo">
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>

    <!-- BỘ FORM ĐỔI MẬT KHẨU -->
    <div class="bg-[#F8F3EF] border border-gray-300 rounded-md shadow-lg py-6 lg:py-8 px-6 lg:px-16">
        <h2 class="text-center text-secondary font-bold text-lg lg:text-xl uppercase tracking-wider mb-6">Đổi mật khẩu
        </h2>

        <!-- Thông báo thành công Đổi mật khẩu -->
        @if (session('success_password'))
            <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-100 border border-green-200">
                {{ session('success_password') }}
            </div>
        @endif

        <form action="{{ route('client.auth.password.update') }}" method="POST"
            class="max-w-[800px] mx-auto space-y-5">
            @csrf

            @if (!empty(Auth::user()->password))
                <!-- Mật khẩu hiện tại -->
                <div class="flex flex-col lg:flex-row lg:items-start gap-2 lg:gap-8">
                    <label class="lg:w-40 text-sm text-primary font-archivo lg:mt-3">Mật khẩu hiện tại*</label>
                    <div class="flex-1">
                        <input type="password" name="current_password" required
                            class="w-full text-sm bg-white border border-gray-300 rounded-md px-5 py-3 text-primary focus:outline-none focus:border-secondary transition-colors font-archivo">
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif

            <!-- Mật khẩu mới -->
            <div class="flex flex-col lg:flex-row lg:items-start gap-2 lg:gap-8">
                <label class="lg:w-40 text-sm text-primary font-archivo lg:mt-3">Mật khẩu mới*</label>
                <div class="flex-1">
                    <input type="password" name="new_password" required placeholder="Tối thiểu 8 ký tự"
                        class="w-full text-sm bg-white border border-gray-300 rounded-md px-5 py-3 text-primary focus:outline-none focus:border-secondary transition-colors font-archivo">
                    @error('new_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Xác nhận mật khẩu mới -->
            <div class="flex flex-col lg:flex-row lg:items-start gap-2 lg:gap-8">
                <label class="lg:w-40 text-sm text-primary font-archivo lg:mt-3">Xác nhận mật khẩu*</label>
                <div class="flex-1">
                    <input type="password" name="new_password_confirmation" required
                        class="w-full text-sm bg-white border border-gray-300 rounded-md px-5 py-3 text-primary focus:outline-none focus:border-secondary transition-colors font-archivo">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center pt-4">
                <button type="submit"
                    class="min-w-[150px] lg:min-w-[200px] py-2 bg-primary text-white text-lg font-bold rounded-md hover:bg-secondary transition-colors font-archivo">
                    Cập nhật mật khẩu
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <!-- SCRIPT TỰ ĐỘNG SUBMIT AVATAR -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const avatarInput = document.getElementById('avatar-input');
            const formAvatar = document.getElementById('form-avatar');
            const loadingText = document.getElementById('avatar-loading');

            if (avatarInput && formAvatar) {
                avatarInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        // Hiển thị text "Đang tải ảnh lên..."
                        if (loadingText) loadingText.classList.remove('hidden');
                        // Tự động submit form
                        formAvatar.submit();
                    }
                });
            }
        });
    </script>
@endpush
