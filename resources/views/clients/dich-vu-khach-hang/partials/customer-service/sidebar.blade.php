<aside class="w-full lg:w-[320px] shrink-0">
    <!-- Mobile Toggle Header (Visible only on mobile) -->
    <div id="mobile-sidebar-toggle"
        class="lg:hidden flex items-center gap-[10px] pl-4 pr-10 pb-4 pt-6 bg-white cursor-pointer">
        <svg id="mobile-sidebar-chevron" style="transform: rotate(90deg)" class="transition-transform duration-300"
            width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5 3L9 7L5 11" stroke="#2E2F2A" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
        <h2 class="text-[18px] text-primary font-normal font-archivo">Dịch vụ khách hàng</h2>
    </div>

    <!-- Sidebar Content (Desktop: always visible, Mobile: expanded by default) -->
    <div id="sidebar-content"
        class="lg:block bg-white lg:bg-transparent shadow-[0px_4px_4px_rgba(0,0,0,0.15)] lg:shadow-none px-10 lg:px-0">
        <h2 class="hidden lg:block text-lg lg:text-xl mb-8 text-primary">Dịch vụ khách hàng</h2>

        <nav class="flex flex-col lg:border-t border-gray-300 text-sm">
            <!-- First 5 items -->
            <div id="sidebar-items-initial" class="flex flex-col">
                <a href="{{ route('client.dich-vu.trang-thai-don-hang') }}"
                    class="py-4 border-b border-gray-300 transition-colors hover:underline font-bold @if ($activeOrder ?? false) text-primary decoration-primary underline underline-offset-4 @else text-primary @endif">
                    Trạng thái đơn hàng
                </a>
                <a href="{{ route('client.auth.profile') }}"
                    class="py-4 border-b border-gray-300 transition-colors hover:underline font-bold @if ($activeAccount ?? false) text-primary decoration-primary underline underline-offset-4 @else text-primary @endif">
                    Tài khoản của tôi
                </a>
                <a href="{{ route('client.dich-vu.tai-catalog') }}"
                    class="py-4 border-b border-gray-300 transition-colors hover:underline font-bold @if ($activeCatalog ?? false) text-primary decoration-primary underline underline-offset-4 @else text-primary @endif">
                    Tải Catalog
                </a>
                <a href="{{ route('client.dich-vu.quy-trinh-dat-hang') }}"
                    class="py-4 border-b border-gray-300 transition-colors hover:underline font-bold @if ($activeProcess ?? false) text-primary decoration-primary underline underline-offset-4 @else text-primary @endif">
                    Quy trình đặt hàng
                </a>
                <a href="{{ route('client.dich-vu.huong-dan-thi-cong') }}"
                    class="py-4 border-b border-gray-300 transition-colors hover:underline font-bold @if ($activeGuide ?? false) text-primary decoration-primary underline underline-offset-4 @else text-primary @endif">
                    Hướng dẫn thi công
                </a>
            </div>

            <!-- More Toggle (Mobile only, centered and at bottom of initial 5) -->
            <div id="mobile-more-toggle" class="lg:hidden flex justify-center py-5 cursor-pointer">
                <img src="{{ asset('assets/images/plus-circle.svg') }}" alt="More" class="w-[22px] h-[22px]">
            </div>

            <!-- Remaining items -->
            <div id="sidebar-items-more" class="hidden lg:flex flex-col w-full">
                <a href="{{ route('client.dich-vu.chinh-sach-van-chuyen') }}"
                    class="py-4 border-b border-gray-300 transition-colors hover:underline font-bold @if ($activeShipping ?? false) text-primary decoration-primary underline underline-offset-4 @else text-primary @endif">
                    Chính sách vận chuyển
                </a>
                <a href="{{ route('client.dich-vu.chinh-sach-doi-tra') }}"
                    class="py-4 border-b border-gray-300 transition-colors hover:underline font-bold @if ($activeReturn ?? false) text-primary decoration-primary underline underline-offset-4 @else text-primary @endif">
                    Chính sách đổi trả
                </a>
                <a href="{{ route('client.dich-vu.bao-mat-thong-tin') }}"
                    class="py-4 border-b border-gray-300 transition-colors hover:underline font-bold @if ($activePrivacy ?? false) text-primary decoration-primary underline underline-offset-4 @else text-primary @endif">
                    Bảo mật thông tin
                </a>
                <a href="{{ route('client.faq') }}"
                    class="mb-6 lg:mb-0 py-4 border-b border-gray-300 transition-colors hover:underline font-bold @if ($activeFaq ?? false) text-primary decoration-primary underline underline-offset-4 @else text-primary @endif">
                    FAQ
                </a>
            </div>
        </nav>

        <!-- Logout button (full width, black) -->
        @auth
            <div class="mt-4 lg:mt-6">
                <form method="POST" action="{{ route('client.auth.logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-black text-white py-3 rounded text-center font-bold">
                        Đăng xuất
                    </button>
                </form>
            </div>
        @endauth
    </div>
</aside>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('mobile-sidebar-toggle');
            const content = document.getElementById('sidebar-content');
            const chevron = document.getElementById('mobile-sidebar-chevron');
            const moreToggle = document.getElementById('mobile-more-toggle');
            const moreItems = document.getElementById('sidebar-items-more');

            if (toggle && content && chevron) {
                toggle.addEventListener('click', () => {
                    const isHidden = content.classList.contains('hidden');
                    if (isHidden) {
                        content.classList.remove('hidden');
                        chevron.style.transform = 'rotate(90deg)';
                    } else {
                        content.classList.add('hidden');
                        chevron.style.transform = 'rotate(0deg)';
                        // Reset "more" section when closing
                        if (moreItems && moreToggle) {
                            moreItems.classList.add('hidden');
                            moreToggle.classList.remove('hidden');
                        }
                    }
                });
            }

            if (moreToggle && moreItems) {
                moreToggle.addEventListener('click', () => {
                    moreItems.classList.remove('hidden');
                    moreItems.classList.add('flex');
                    moreToggle.classList.add('hidden');
                });
            }
        });
    </script>
@endpush
