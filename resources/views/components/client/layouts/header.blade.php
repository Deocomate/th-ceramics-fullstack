<header id="site-header"
    class="bg-primary sticky top-0 text-white h-[58px] xl:h-[81px] z-[100] transition-all duration-300">
    <div class="hidden xl:block w-[85%] max-w-[1320px] mx-auto h-full">
        <nav class="flex items-center justify-between h-full">
            <!-- Logo -->
            <div class="flex-shrink-0 mr-[8%]">
                <a href="/" class="block">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-[55px] w-[55px]" />
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden xl:flex items-center 2xl:gap-10 xl:gap-6 gap-6 flex-1 justify-end mr-8"
                id="desktop-menu">
                <a href="{{ route('client.home') }}"
                    class="nav-link whitespace-nowrap shrink-0 text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary transition-colors"
                    data-path="/">trang chủ</a>
                <a href="{{ route('client.about') }}"
                    class="nav-link whitespace-nowrap shrink-0 text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary transition-colors"
                    data-path="/ve-chung-toi">về chúng tôi</a>
                <div class="relative group">
                    <a href="{{ route('client.products.ngoi-am-duong.index') }}"
                        class="nav-link flex items-center gap-[5px] whitespace-nowrap shrink-0 text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary transition-colors"
                        data-path="/san-pham/">
                        <span>sản phẩm</span>
                        <svg viewBox="0 0 7 7" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-[7px] h-[7px] fill-current">
                            <path d="M6.05225 1.87792L3.50017 3.91959L0.948088 1.87792C0.88826 1.83005 0.819587 1.79442 0.745991 1.77308C0.672395 1.75174 0.595317 1.74511 0.519158 1.75356C0.442998 1.76201 0.369249 1.78538 0.30212 1.82233C0.234992 1.85928 0.175799 1.90909 0.127921 1.96892C0.0800432 2.02875 0.0444182 2.09742 0.0230801 2.17102C0.00174206 2.24462 -0.00489129 2.32169 0.00355885 2.39785C0.012009 2.47401 0.0353771 2.54776 0.072329 2.61489C0.109281 2.68202 0.159093 2.74121 0.218921 2.78909L3.13559 5.12242C3.23905 5.20526 3.36763 5.25039 3.50017 5.25039C3.63271 5.25039 3.76129 5.20526 3.86475 5.12242L6.78142 2.78909C6.90225 2.6924 6.97972 2.55166 6.99678 2.39785C7.01385 2.24404 6.96911 2.08975 6.87242 1.96892C6.82454 1.90909 6.76535 1.85928 6.69822 1.82233C6.63109 1.78538 6.55734 1.76201 6.48118 1.75356C6.32737 1.7365 6.17308 1.78123 6.05225 1.87792Z" />
                        </svg>
                    </a>
                    <!-- Desktop Dropdown -->
                    <div
                        class="absolute top-full left-0 mt-0 w-[240px] bg-white shadow-xl rounded-b opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 border-t-2 border-secondary">
                        <ul class="py-2 flex flex-col">
                            <li>
                                <a href="{{ route('client.products.ngoi-am-duong.index') }}"
                                    class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                                    data-path="/san-pham/ngoi-am-duong">Ngói Âm Dương</a>
                            </li>
                            <li>
                                <a href="{{ route('client.products.ngoi-hai-van-mieu.index') }}"
                                    class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                                    data-path="/san-pham/ngoi-hai-van-mieu">Ngói Hài Văn Miếu</a>
                            </li>
                            <li>
                                <a href="{{ route('client.products.gach-hoa-thong-gio.index') }}"
                                    class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                                    data-path="/san-pham/gach-hoa-thong-gio">Gạch Hoa Thông Gió</a>
                            </li>
                            <li>
                                <a href="{{ route('client.products.phu-kien-ngoi.index') }}"
                                    class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                                    data-path="/san-pham/phu-kien-ngoi">Phụ Kiện Ngói</a>
                            </li>
                            <li>
                                <a href="{{ route('client.products.gach-trang-tri.index') }}"
                                    class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                                    data-path="/san-pham/gach-trang-tri">Gạch Trang Trí</a>
                            </li>
                            <li>
                                <a href="{{ route('client.products.lan-can-gom-su.index') }}"
                                    class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                                    data-path="/san-pham/lan-can-gom-su">Lan Can Gốm Sứ</a>
                            </li>
                            <li>
                                <a href="{{ route('client.products.gach-co-bat-trang.index') }}"
                                    class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                                    data-path="/san-pham/gach-co-bat-trang">Gạch Cổ Bát Tràng</a>
                            </li>
                            <li>
                                <a href="{{ route('client.products.linh-vat-phong-thuy.index') }}"
                                    class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                                    data-path="/san-pham/linh-vat-phong-thuy">Linh Vật Phong Thủy</a>
                            </li>
                            <li>
                                <a href="{{ route('client.products.den-gom-su.index') }}"
                                    class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                                    data-path="/san-pham/den-gom-su">Đèn Gốm Sứ</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="contents xl:flex items-center 2xl:gap-10 xl:gap-6 gap-6" data-nav-trailing>
                    <a href="{{ route('client.projects.index') }}"
                        class="nav-link whitespace-nowrap shrink-0 text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary transition-colors"
                        data-path="/du-an">Dự án</a>
                    <a href="{{ route('client.factory') }}"
                        class="nav-link whitespace-nowrap shrink-0 text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary transition-colors"
                        data-path="/xuong-san-xuat">Xưởng sản xuất</a>
                    <a href="{{ route('client.news.index') }}"
                        class="nav-link whitespace-nowrap shrink-0 text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary transition-colors"
                        data-path="/tin-tuc">Tin tức</a>
                    <a href="{{ route('client.contact') }}"
                        class="nav-link whitespace-nowrap shrink-0 text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary transition-colors"
                        data-path="/lien-he">liên hệ</a>
                </div>
            </div>

            <!-- Desktop Icons -->
            <div class="hidden xl:flex items-center gap-6">
                <div class="relative flex items-center" data-expandable-search>
                    {{-- Pill: collapsed by default, expands left on open --}}
                    <div data-search-pill
                        data-open-classes="w-[500px] max-w-[42vw] opacity-100 pointer-events-auto"
                        class="relative flex items-center h-[44px] w-0 max-w-0 opacity-0 pointer-events-none overflow-hidden rounded-[42px] border-2 border-[#ab6520] bg-[#43443f] backdrop-blur-[5px] shadow-[0px_4px_4px_0px_rgba(0,0,0,0.25)] transition-all duration-300">
                        <input type="search" autocomplete="off" data-search-input
                            placeholder="Nhập nội dung tìm kiếm..."
                            class="min-w-0 w-full bg-transparent border-0 outline-none pl-6 pr-12 text-[18px] text-white placeholder:italic placeholder:font-extralight placeholder:text-[18px] placeholder:text-[#b7b8b3]" />
                        <button type="button" data-search-submit aria-label="Tìm kiếm"
                            class="absolute right-4 flex items-center justify-center hover:opacity-80 transition-opacity">
                            <img src="{{ asset('assets/images/search.svg') }}" alt="" class="w-5 h-5" />
                        </button>
                        <div data-search-dropdown
                            class="hidden absolute left-0 top-full mt-3 w-full max-h-[70vh] overflow-y-auto rounded-sm border border-neutral-1 bg-white text-primary shadow-2xl z-[70]"></div>
                    </div>

                    {{-- Standalone trigger icon (shown when collapsed) --}}
                    <button type="button" data-search-toggle aria-label="Mở tìm kiếm"
                        class="hover:text-secondary transition-colors">
                        <img src="{{ asset('assets/images/search.svg') }}" alt="search" class="w-5 h-5" />
                    </button>
                </div>
                <x-client.shared.mini-cart :count="$cartCount ?? 0" />
                @auth
                    <a href="{{ route('client.customer-service.show', 'trang-thai-don-hang') }}"
                        class="hover:text-secondary transition-colors" aria-label="User">
                        <img src="{{ asset('assets/images/user.svg') }}" alt="user" class="w-5 h-5" />
                    </a>
                @else
                    <a href="{{ route('client.auth.login') }}"
                        class="hover:text-secondary transition-colors" aria-label="User">
                        <img src="{{ asset('assets/images/user.svg') }}" alt="user" class="w-5 h-5" />
                    </a>
                @endauth
            </div>
        </nav>
    </div>

    <!-- Mobile Header -->
    <div class="xl:hidden h-full bg-[linear-gradient(90deg,_#2F302C_0%,_#262720_100%)]">
        <nav class="relative flex items-center justify-center h-full">
            <button id="mobile-menu-button"
                class="absolute left-[23px] text-white hover:text-secondary transition-colors" aria-label="Toggle menu">
                <img src="{{ asset('assets/images/menu.svg') }}" alt="menu" class="w-[18px] h-[18px]" />
            </button>

            <a href="/" class="block">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-[44px] w-[44px]" />
            </a>

            <div class="absolute right-[23px] flex items-center gap-[14px]">
                <x-client.shared.mini-cart :count="$cartCount ?? 0" icon-class="w-[18px] h-[18px]" />
                @auth
                    <a href="{{ route('client.customer-service.show', 'trang-thai-don-hang') }}"
                        class="hover:text-secondary transition-colors" aria-label="User">
                        <img src="{{ asset('assets/images/user.svg') }}" alt="user" class="w-[18px] h-[18px]" />
                    </a>
                @else
                    <a href="{{ route('client.auth.login') }}"
                        class="hover:text-secondary transition-colors" aria-label="User">
                        <img src="{{ asset('assets/images/user.svg') }}" alt="user" class="w-[18px] h-[18px]" />
                    </a>
                @endauth
            </div>
        </nav>
    </div>

    <!-- Mobile Navigation -->
    <div id="mobile-menu" class="hidden fixed inset-0 bg-primary z-50 overflow-y-auto">
        <div class="w-[85%] max-w-[1320px] mx-auto py-6 relative min-h-full flex flex-col">
            <div class="flex justify-between items-center mb-8 shrink-0">
                <a href="/" class="block">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-[90px] w-[90px]" />
                </a>
                <div class="flex items-center gap-5">
                    <x-client.shared.mini-cart :count="$cartCount ?? 0" />
                    @auth
                        <a href="{{ route('client.customer-service.show', 'trang-thai-don-hang') }}"
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
</header>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // 0. Sticky header scroll effect (home page only)
            const siteHeader = document.getElementById("site-header");
            if (siteHeader && siteHeader.classList.contains("fixed")) {
                const onScroll = () => {
                    if (window.scrollY > 20) {
                        siteHeader.classList.remove("bg-opacity-60");
                        siteHeader.classList.add("bg-opacity-100", "shadow-md");
                    } else {
                        siteHeader.classList.add("bg-opacity-60");
                        siteHeader.classList.remove("bg-opacity-100", "shadow-md");
                    }
                };
                window.addEventListener("scroll", onScroll, {
                    passive: true
                });
                onScroll();
            }

            // Header search
            const searchEndpoint = @json(route('client.search.quick'));
            const searchCategories = @json($categories ?? []);
            const expandableSearchRoots = document.querySelectorAll("[data-expandable-search]");
            const mobileMenuSearchRoots = document.querySelectorAll("[data-mobile-menu-search]");

            const normalizeSearch = (value) => String(value || "")
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .replace(/đ/g, "d")
                .replace(/Đ/g, "D")
                .toLowerCase();

            const escapeHtml = (value) => String(value ?? "")
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");

            const swapClasses = (element, classes, enabled) => {
                classes.split(" ").filter(Boolean).forEach((className) => {
                    element.classList.toggle(className, enabled);
                });
            };

            const filterCategories = (keyword) => {
                const normalizedKeyword = normalizeSearch(keyword);

                if (!normalizedKeyword) return [];

                return searchCategories
                    .filter((category) => {
                        const haystack = normalizeSearch([
                            category.name,
                            ...(category.keywords || []),
                        ].join(" "));

                        return haystack.includes(normalizedKeyword);
                    })
                    .slice(0, 5);
            };

            const renderSearchDropdown = (dropdown, categories, products, state = "ready") => {
                const hasCategories = categories.length > 0;
                const hasProducts = products.length > 0;

                if (!hasCategories && !hasProducts && state === "ready") {
                    dropdown.innerHTML = `
                        <div class="px-4 py-5 text-sm text-primary/70">Không tìm thấy kết quả phù hợp.</div>
                    `;
                    dropdown.classList.remove("hidden");
                    return;
                }

                const categoryHtml = hasCategories ? `
                    <div class="border-b border-neutral-1">
                        <div class="px-4 pt-3 pb-2 text-[11px] font-bold uppercase tracking-[0.08em] text-primary/50">Danh mục</div>
                        <div class="pb-2">
                            ${categories.map((category) => `
                                <a href="${escapeHtml(category.url)}" class="block px-4 py-2 text-sm font-semibold hover:bg-neutral-2 hover:text-secondary transition-colors">
                                    ${escapeHtml(category.name)}
                                </a>
                            `).join("")}
                        </div>
                    </div>
                ` : "";

                const productHtml = hasProducts ? `
                    <div>
                        <div class="px-4 pt-3 pb-2 text-[11px] font-bold uppercase tracking-[0.08em] text-primary/50">Sản phẩm</div>
                        <div class="pb-2">
                            ${products.map((product) => `
                                <a href="${escapeHtml(product.url)}" class="flex items-center gap-3 px-4 py-2 hover:bg-neutral-2 transition-colors">
                                    <img src="${escapeHtml(product.image)}" alt="${escapeHtml(product.name)}" class="h-12 w-12 shrink-0 rounded-sm object-cover bg-neutral-1" loading="lazy" />
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-sm font-semibold text-primary">${escapeHtml(product.name)}</span>
                                        <span class="mt-1 flex items-center gap-2 text-xs text-primary/60">
                                            <span class="truncate">${escapeHtml(product.code || product.category || "")}</span>
                                            <span class="font-semibold text-secondary">${escapeHtml(product.price_formatted || "")}</span>
                                        </span>
                                    </span>
                                </a>
                            `).join("")}
                        </div>
                    </div>
                ` : "";

                const stateHtml = state === "loading" ? `
                    <div class="px-4 py-3 text-sm text-primary/60">Đang tìm sản phẩm...</div>
                ` : state === "error" ? `
                    <div class="px-4 py-3 text-sm text-red-600">Không thể tải gợi ý sản phẩm.</div>
                ` : "";

                dropdown.innerHTML = `${categoryHtml}${productHtml}${stateHtml}`;
                dropdown.classList.remove("hidden");
            };

            const openSearch = (root) => {
                const input = root.querySelector("[data-search-input]");
                if (!input) return;

                const target = root.querySelector("[data-search-pill]") || input;
                const openClasses = target.dataset.openClasses || "w-48 opacity-100 pointer-events-auto";
                root.dataset.searchOpen = "true";
                target.classList.remove("w-0", "max-w-0", "opacity-0", "pointer-events-none");
                swapClasses(target, openClasses, true);
                root.querySelector("[data-search-toggle]")?.classList.add("hidden");
                document.querySelector("[data-nav-trailing]")?.classList.add("!hidden");
                
                // Remove overflow-hidden after transition to allow dropdown to show
                setTimeout(() => {
                    if (root.dataset.searchOpen === "true") {
                        target.classList.remove("overflow-hidden");
                    }
                }, 300);
                
                input.focus();
            };

            const closeSearch = (root) => {
                const input = root.querySelector("[data-search-input]");
                const dropdown = root.querySelector("[data-search-dropdown]");
                if (!input) return;

                const target = root.querySelector("[data-search-pill]") || input;
                const openClasses = target.dataset.openClasses || "w-48 opacity-100 pointer-events-auto";
                root.dataset.searchOpen = "false";
                
                // Add overflow-hidden immediately when closing to hide content during transition
                target.classList.add("overflow-hidden");
                
                swapClasses(target, openClasses, false);
                target.classList.add("w-0", "max-w-0", "opacity-0", "pointer-events-none");
                input.value = "";
                dropdown?.classList.add("hidden");
                root.querySelector("[data-search-toggle]")?.classList.remove("hidden");
                document.querySelector("[data-nav-trailing]")?.classList.remove("!hidden");
            };

            const bindSearchInput = (root, dropdown, input) => {
                let searchTimer = null;
                let searchToken = 0;

                input.addEventListener("input", () => {
                    const keyword = input.value.trim();
                    const token = ++searchToken;
                    window.clearTimeout(searchTimer);

                    if (!keyword) {
                        dropdown.classList.add("hidden");
                        return;
                    }

                    const categories = filterCategories(keyword);

                    if (keyword.length < 2) {
                        if (categories.length > 0) {
                            renderSearchDropdown(dropdown, categories, [], "ready");
                        } else {
                            dropdown.classList.add("hidden");
                        }
                        return;
                    }

                    renderSearchDropdown(dropdown, categories, [], "loading");

                    searchTimer = window.setTimeout(async () => {
                        try {
                            const response = await fetch(`${searchEndpoint}?q=${encodeURIComponent(keyword)}`, {
                                headers: { "Accept": "application/json" },
                            });

                            if (!response.ok) throw new Error("Search request failed");

                            const payload = await response.json();
                            if (token !== searchToken) return;

                            renderSearchDropdown(dropdown, categories, payload.products || [], "ready");
                        } catch (error) {
                            if (token !== searchToken) return;
                            renderSearchDropdown(dropdown, categories, [], "error");
                        }
                    }, 300);
                });
            };

            const resetMenuSearch = (root) => {
                const input = root.querySelector("[data-search-input]");
                const dropdown = root.querySelector("[data-search-dropdown]");
                if (input) input.value = "";
                dropdown?.classList.add("hidden");
            };

            expandableSearchRoots.forEach((root) => {
                const input = root.querySelector("[data-search-input]");
                const toggle = root.querySelector("[data-search-toggle]");
                const dropdown = root.querySelector("[data-search-dropdown]");
                const submit = root.querySelector("[data-search-submit]");

                if (!input || !toggle || !dropdown) return;

                toggle.addEventListener("click", (event) => {
                    event.preventDefault();
                    openSearch(root);
                });

                submit?.addEventListener("click", (event) => {
                    event.preventDefault();
                    input.focus();
                });

                input.addEventListener("keydown", (event) => {
                    if (event.key === "Escape") {
                        closeSearch(root);
                    }
                });

                bindSearchInput(root, dropdown, input);
            });

            mobileMenuSearchRoots.forEach((root) => {
                const input = root.querySelector("[data-search-input]");
                const submit = root.querySelector("[data-search-submit]");
                const dropdown = root.querySelector("[data-search-dropdown]");

                if (!input || !dropdown) return;

                submit?.addEventListener("click", (event) => {
                    event.preventDefault();
                    input.focus();
                });

                input.addEventListener("keydown", (event) => {
                    if (event.key === "Escape") {
                        event.target.value = "";
                        dropdown.classList.add("hidden");
                    }
                });

                bindSearchInput(root, dropdown, input);
            });

            document.addEventListener("click", (event) => {
                expandableSearchRoots.forEach((root) => {
                    if (root.dataset.searchOpen === "true" && !root.contains(event.target)) {
                        closeSearch(root);
                    }
                });

                mobileMenuSearchRoots.forEach((root) => {
                    const dropdown = root.querySelector("[data-search-dropdown]");
                    if (dropdown && !dropdown.classList.contains("hidden") && !root.contains(event.target)) {
                        dropdown.classList.add("hidden");
                    }
                });
            });

            // 1. Highlight Active Link
            const currentPath = window.location.pathname;

            // Normalize path (ensure trailing slash for comparison if needed, or matched exactly)
            // Simple matching:
            const desktopLinks = document.querySelectorAll(".nav-link");
            const desktopDropdownLinks = document.querySelectorAll(
                ".desktop-dropdown-link",
            );
            const mobileLinks = document.querySelectorAll(".mobile-nav-link");
            const mobileProductsToggle = document.getElementById(
                "mobile-products-toggle",
            );

            const setActive = (link) => {
                if (link.classList.contains("nav-link")) {
                    // Desktop: Remove default light text and add underline
                    link.classList.remove("text-[#FFFAF3]");
                    link.classList.add("underline", "underline-offset-8", "decoration-2");
                } else if (
                    link.classList.contains("mobile-nav-link") ||
                    link.classList.contains("mobile-nav-toggle")
                ) {
                    // Mobile: Remove default light text
                    link.classList.remove("text-[#FFFAF3]", "text-white", "text-white/90");
                } else if (link.classList.contains("desktop-dropdown-link")) {
                    // Desktop Dropdown: Remove hover text color if any, but specifically remove black text
                    link.classList.remove("text-[#212121]");
                }

                // Add Active styling - Orange text
                link.classList.add("text-secondary");
            };

            [
                ...desktopLinks,
                ...desktopDropdownLinks,
                ...mobileLinks,
                mobileProductsToggle,
            ]
            .filter(Boolean)
                .forEach((link) => {
                    const linkPath = link.getAttribute("data-path");
                    if (
                        currentPath === linkPath ||
                        (currentPath === "/index.html" && linkPath === "/") ||
                        (linkPath !== "/" && currentPath.startsWith(linkPath))
                    ) {
                        setActive(link);
                    }
                });

            // 2. Mobile Menu Toggle
            const mobileMenuBtn = document.getElementById("mobile-menu-button");
            const closeMenuBtn = document.getElementById("close-menu-button");
            const mobileMenu = document.getElementById("mobile-menu");

            const toggleMenu = () => {
                const isOpening = mobileMenu.classList.contains("hidden");
                mobileMenu.classList.toggle("hidden");

                if (isOpening) {
                    document.body.style.overflow = "hidden";
                    const menuSearchInput = document.querySelector("[data-mobile-menu-search] [data-search-input]");
                    menuSearchInput?.focus();
                } else {
                    document.body.style.overflow = "";
                    mobileMenuSearchRoots.forEach(resetMenuSearch);
                }
            };

            mobileMenuBtn?.addEventListener("click", toggleMenu);
            closeMenuBtn?.addEventListener("click", toggleMenu);

            // Close menu when clicking a link
            mobileLinks.forEach((link) => {
                link.addEventListener("click", () => {
                    if (!mobileMenu.classList.contains("hidden")) toggleMenu();
                });
            });

            // 3. Mobile Products Submenu Toggle
            const bindMobileSubmenuToggle = (toggleButton, submenuId, iconId) => {
                if (!toggleButton) return;

                toggleButton.addEventListener("click", (e) => {
                    e.preventDefault();
                    const submenu = document.getElementById(submenuId);
                    const icon = document.getElementById(iconId);

                    if (!submenu) return;

                    if (submenu.classList.contains("hidden")) {
                        submenu.classList.remove("hidden");
                        submenu.classList.add("flex");
                        if (icon) icon.classList.add("rotate-180");
                    } else {
                        submenu.classList.add("hidden");
                        submenu.classList.remove("flex");
                        if (icon) icon.classList.remove("rotate-180");
                    }
                });
            };

            bindMobileSubmenuToggle(
                mobileProductsToggle,
                "mobile-products-submenu",
                "mobile-products-icon",
            );
        });

        // Auto-detect home route and apply fixed transparent header
        (function() {
            const header = document.getElementById("site-header");
            if (!header) return;
            const isHome = window.location.pathname === "/" || window.location.pathname === "/index.html";
            if (isHome) {
                header.classList.remove("sticky");
                header.classList.add("fixed", "top-0", "left-0", "right-0", "bg-opacity-60");
            }
        })();
    </script>
@endpush
