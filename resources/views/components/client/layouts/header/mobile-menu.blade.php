@props(['cartCount' => 0])

<div id="mobile-menu" class="hidden fixed inset-0 bg-primary z-50 overflow-y-auto">
    <div class="w-[85%] max-w-[1320px] mx-auto py-6 relative min-h-full flex flex-col">
        <div class="flex justify-between items-center mb-8 shrink-0">
            <a href="/" class="block">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-[90px] w-[90px]" />
            </a>
            <div class="flex items-center gap-5">
                @if ($isEcommerceEnabled)
                <x-client.commerce.shared.mini-cart :count="$cartCount ?? 0" />
                @endif
                @auth
                    <a href="{{ ($isEcommerceEnabled ?? true) ? route('client.customer-service.show', 'trang-thai-don-hang') : route('client.auth.profile') }}"
                        class="hover:text-secondary transition-colors" aria-label="User">
                        <img src="{{ asset('assets/images/user.svg') }}" alt="user" class="w-5 h-5" />
                    </a>
                @else
                    <a href="{{ route('client.auth.login') }}"
                        class="hover:text-secondary transition-colors" aria-label="User">
                        <img src="{{ asset('assets/images/user.svg') }}" alt="user" class="w-5 h-5" />
                    </a>
                @endauth
                <button id="close-menu-button" class="text-white hover:text-secondary transition-colors">
                    <img src="{{ asset('assets/images/icon-close.svg') }}" alt="close" class="w-8 h-8" />
                </button>
            </div>
        </div>
        <div class="relative mb-6 flex items-center gap-2" data-mobile-menu-search data-search-open="true">
            <input
                type="search"
                placeholder="Tìm kiếm sản phẩm"
                autocomplete="off"
                data-search-input
                class="min-w-0 w-full rounded-sm border border-white/20 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-white/60 outline-none transition-all duration-300 focus:border-secondary opacity-100 pointer-events-auto"
            />
            <button type="button" data-search-submit class="w-11 h-11 shrink-0 flex items-center justify-center rounded-sm border border-white/20" aria-label="Tìm kiếm">
                <img src="{{ asset('assets/images/search.svg') }}" alt="" class="w-5 h-5" />
            </button>
            <div data-search-dropdown
                class="hidden absolute left-0 right-0 top-full mt-2 max-h-[62vh] overflow-y-auto rounded-sm border border-neutral-1 bg-white text-primary shadow-2xl z-[70]">
            </div>
        </div>
        <div class="flex flex-col space-y-6 flex-grow z-10">
            <a href="{{ route('client.home') }}"
                class="mobile-nav-link text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary"
                data-path="/">trang chủ</a>
            <a href="{{ route('client.about') }}"
                class="mobile-nav-link text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary"
                data-path="/ve-chung-toi">về chúng tôi</a>
            <div class="flex flex-col">
                <button id="mobile-products-toggle"
                    class="mobile-nav-toggle flex items-center gap-[6px] text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary w-full text-left"
                    data-path="/san-pham/">
                    <span>sản phẩm</span>
                    <svg viewBox="0 0 7 7" fill="none" xmlns="http://www.w3.org/2000/svg"
                        id="mobile-products-icon"
                        class="w-[10px] h-[10px] fill-current transition-transform duration-300 transform">
                        <path d="M6.05225 1.87792L3.50017 3.91959L0.948088 1.87792C0.88826 1.83005 0.819587 1.79442 0.745991 1.77308C0.672395 1.75174 0.595317 1.74511 0.519158 1.75356C0.442998 1.76201 0.369249 1.78538 0.30212 1.82233C0.234992 1.85928 0.175799 1.90909 0.127921 1.96892C0.0800432 2.02875 0.0444182 2.09742 0.0230801 2.17102C0.00174206 2.24462 -0.00489129 2.32169 0.00355885 2.39785C0.012009 2.47401 0.0353771 2.54776 0.072329 2.61489C0.109281 2.68202 0.159093 2.74121 0.218921 2.78909L3.13559 5.12242C3.23905 5.20526 3.36763 5.25039 3.50017 5.25039C3.63271 5.25039 3.76129 5.20526 3.86475 5.12242L6.78142 2.78909C6.90225 2.6924 6.97972 2.55166 6.99678 2.39785C7.01385 2.24404 6.96911 2.08975 6.87242 1.96892C6.82454 1.90909 6.76535 1.85928 6.69822 1.82233C6.63109 1.78538 6.55734 1.76201 6.48118 1.75356C6.32737 1.7365 6.17308 1.78123 6.05225 1.87792Z" />
                    </svg>
                </button>
                <div id="mobile-products-submenu"
                    class="hidden flex-col pl-4 mt-4 space-y-4 border-l-2 border-white/20 ml-2">
                    <a href="{{ route('client.products.ngoi-am-duong.index') }}"
                        class="mobile-nav-link text-white/90 font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary"
                        data-path="/san-pham/ngoi-am-duong">Ngói Âm Dương</a>
                    <a href="{{ route('client.products.ngoi-hai-van-mieu.index') }}"
                        class="mobile-nav-link text-white/90 font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary"
                        data-path="/san-pham/ngoi-hai-van-mieu">Ngói Hài Văn Miếu</a>
                    <a href="{{ route('client.products.gach-hoa-thong-gio.index') }}"
                        class="mobile-nav-link text-white/90 font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary"
                        data-path="/san-pham/gach-hoa-thong-gio">Gạch Hoa Thông Gió</a>
                    <a href="{{ route('client.products.phu-kien-ngoi.index') }}"
                        class="mobile-nav-link text-white/90 font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary"
                        data-path="/san-pham/phu-kien-ngoi">Phụ Kiện Ngói</a>
                    <a href="{{ route('client.products.gach-trang-tri.index') }}"
                        class="mobile-nav-link text-white/90 font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary"
                        data-path="/san-pham/gach-trang-tri">Gạch Trang Trí</a>
                    <a href="{{ route('client.products.lan-can-gom-su.index') }}"
                        class="mobile-nav-link text-white/90 font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary"
                        data-path="/san-pham/lan-can-gom-su">Lan Can Gốm Sứ</a>
                    <a href="{{ route('client.products.gach-co-bat-trang.index') }}"
                        class="mobile-nav-link text-white/90 font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary"
                        data-path="/san-pham/gach-co-bat-trang">Gạch Cổ Bát Tràng</a>
                    <a href="{{ route('client.products.linh-vat-phong-thuy.index') }}"
                        class="mobile-nav-link text-white/90 font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary"
                        data-path="/san-pham/linh-vat-phong-thuy">Linh vật Phong Thủy</a>
                    <a href="{{ route('client.products.den-gom-su.index') }}"
                        class="mobile-nav-link text-white/90 font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary"
                        data-path="/san-pham/den-gom-su">Đèn Gốm Sứ</a>
                </div>
            </div>

            <a href="{{ route('client.projects.index') }}"
                class="mobile-nav-link text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary"
                data-path="/du-an">Dự án</a>
            <a href="{{ route('client.factory') }}"
                class="mobile-nav-link text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary"
                data-path="/xuong-san-xuat">Xưởng sản xuất</a>
            <a href="{{ route('client.news.index') }}"
                class="mobile-nav-link text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary"
                data-path="/tin-tuc">Tin tức</a>
            <a href="{{ route('client.contact') }}"
                class="mobile-nav-link text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary"
                data-path="/lien-he">liên hệ</a>
        </div>
        <img src="{{ asset('assets/images/background-decorate.svg') }}" alt="background-decorate"
            class="fixed top-[395px] left-[124px] w-[480px] h-[480px] pointer-events-none opacity-100" />
    </div>
</div>
