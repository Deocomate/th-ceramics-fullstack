<header id="site-header"
    class="bg-primary sticky top-0 text-white h-[58px] xl:h-[108px] z-50 transition-all duration-300">
    <div class="hidden xl:block w-[85%] max-w-[1320px] mx-auto h-full">
        <nav class="flex items-center justify-between h-full">
            <!-- Logo -->
            <div class="flex-shrink-0 mr-[8%]">
                <a href="/" class="block">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-[78px] w-[78px]" />
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
                        <img src="{{ asset('assets/images/icon-caret-down.svg') }}" alt="caret"
                            class="w-[7px] h-[7px]" />
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

            <!-- Desktop Icons -->
            <div class="hidden xl:flex items-center gap-6">
                <button class="hover:text-secondary transition-colors" aria-label="Search">
                    <img src="{{ asset('assets/images/search.svg') }}" alt="search" class="w-5 h-5" />
                </button>
                <a href="{{ route('client.cart.index') }}" class="hover:text-secondary transition-colors"
                    aria-label="Cart">
                    <img src="{{ asset('assets/images/cart-2.svg') }}" alt="cart" class="w-5 h-5" />
                </a>
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
                <button class="hover:text-secondary transition-colors" aria-label="Search">
                    <img src="{{ asset('assets/images/search.svg') }}" alt="search" class="w-[18px] h-[18px]" />
                </button>
                <a href="{{ route('client.cart.index') }}" class="hover:text-secondary transition-colors"
                    aria-label="Cart">
                    <img src="{{ asset('assets/images/cart-2.svg') }}" alt="cart" class="w-[18px] h-[18px]" />
                </a>
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
                    <button class="hover:text-secondary transition-colors" aria-label="Search">
                        <img src="{{ asset('assets/images/search.svg') }}" alt="search" class="w-5 h-5" />
                    </button>
                    <a href="{{ route('client.cart.index') }}" class="hover:text-secondary transition-colors"
                        aria-label="Cart">
                        <img src="{{ asset('assets/images/cart-2.svg') }}" alt="cart" class="w-5 h-5" />
                    </a>
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
            <div class="flex flex-col space-y-6 flex-grow z-10">
                <a href="{{ route('client.home') }}"
                    class="mobile-nav-link text-white font-semibold text-lg uppercase hover:text-secondary"
                    data-path="/">trang chủ</a>
                <a href="{{ route('client.about') }}"
                    class="mobile-nav-link text-white font-semibold text-lg uppercase hover:text-secondary"
                    data-path="/ve-chung-toi">về chúng tôi</a>
                <div class="flex flex-col">
                    <button id="mobile-products-toggle"
                        class="mobile-nav-toggle flex items-center gap-[6px] text-white font-semibold text-lg uppercase hover:text-secondary w-full text-left"
                        data-path="/san-pham/">
                        <span>sản phẩm</span>
                        <img src="{{ asset('assets/images/icon-caret-down.svg') }}" alt="caret"
                            id="mobile-products-icon"
                            class="w-[10px] h-[10px] transition-transform duration-300 transform" />
                    </button>
                    <div id="mobile-products-submenu"
                        class="hidden flex-col pl-4 mt-4 space-y-4 border-l-2 border-white/20 ml-2">
                        <a href="{{ route('client.products.ngoi-am-duong.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/ngoi-am-duong">Ngói Âm Dương</a>
                        <a href="{{ route('client.products.ngoi-hai-van-mieu.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/ngoi-hai-van-mieu">Ngói Hài Văn Miếu</a>
                        <a href="{{ route('client.products.gach-hoa-thong-gio.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/gach-hoa-thong-gio">Gạch Hoa Thông Gió</a>
                        <a href="{{ route('client.products.phu-kien-ngoi.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/phu-kien-ngoi">Phụ Kiện Ngói</a>
                        <a href="{{ route('client.products.gach-trang-tri.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/gach-trang-tri">Gạch Trang Trí</a>
                        <a href="{{ route('client.products.lan-can-gom-su.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/lan-can-gom-su">Lan Can Gốm Sứ</a>
                        <a href="{{ route('client.products.gach-co-bat-trang.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/gach-co-bat-trang">Gạch Cổ Bát Tràng</a>
                        <a href="{{ route('client.products.linh-vat-phong-thuy.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/linh-vat-phong-thuy">Linh vật Phong Thủy</a>
                        <a href="{{ route('client.products.den-gom-su.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/den-gom-su">Đèn Gốm Sứ</a>
                    </div>
                </div>

                <div class="flex flex-col">
                    <button id="mobile-spct-toggle"
                        class="mobile-nav-toggle flex items-center gap-[6px] text-white font-semibold text-lg uppercase hover:text-secondary w-full text-left"
                        data-path="/san-pham/">
                        <span>SPCT</span>
                        <img src="{{ asset('assets/images/icon-caret-down.svg') }}" alt="caret"
                            id="mobile-spct-icon"
                            class="w-[10px] h-[10px] transition-transform duration-300 transform" />
                    </button>
                    <!-- Quick access detail pages dropdown MOBILE -->
                    <div id="mobile-spct-submenu"
                        class="hidden flex-col pl-4 mt-4 space-y-4 border-l-2 border-white/20 ml-2">
                        <a href="{{ route('client.products.ngoi-am-duong.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/ngoi-am-duong/">Ngói Âm Dương</a>
                        <a href="{{ route('client.products.ngoi-hai-van-mieu.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/ngoi-hai-van-mieu/">Ngói Hài Văn Miếu</a>
                        <a href="{{ route('client.products.gach-hoa-thong-gio.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/gach-hoa-thong-gio/">Gạch Hoa Thông Gió</a>
                        <a href="{{ route('client.products.gach-trang-tri.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/gach-trang-tri/">Gạch Trang Trí</a>
                        <a href="{{ route('client.products.gach-co-bat-trang.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/gach-co-bat-trang/">Gạch Cổ Bát Tràng</a>
                        <a href="{{ route('client.products.linh-vat-phong-thuy.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/linh-vat-phong-thuy/">Linh Vật Phong Thủy</a>
                        <a href="{{ route('client.products.den-gom-su.index') }}"
                            class="mobile-nav-link text-white/90 text-sm font-semibold uppercase hover:text-secondary"
                            data-path="/san-pham/den-gom-su/">Đèn Gốm Sứ</a>
                    </div>
                </div>
                <a href="{{ route('client.projects.index') }}"
                    class="mobile-nav-link text-white font-semibold text-lg uppercase hover:text-secondary"
                    data-path="/du-an">Dự án</a>
                <a href="{{ route('client.factory') }}"
                    class="mobile-nav-link text-white font-semibold text-lg uppercase hover:text-secondary"
                    data-path="/xuong-san-xuat">Xưởng sản xuất</a>
                <a href="{{ route('client.news.index') }}"
                    class="mobile-nav-link text-white font-semibold text-lg uppercase hover:text-secondary"
                    data-path="/tin-tuc">Tin tức</a>
                <a href="{{ route('client.contact') }}"
                    class="mobile-nav-link text-white font-semibold text-lg uppercase hover:text-secondary"
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
            const mobileSpctToggle = document.getElementById("mobile-spct-toggle");

            const setActive = (link) => {
                if (link.classList.contains("nav-link")) {
                    // Desktop: Remove default light text and add underline
                    link.classList.remove("text-[#FFFAF3]");
                    link.classList.add("underline", "underline-offset-8", "decoration-2");
                } else if (
                    link.classList.contains("mobile-nav-link") ||
                    link.classList.contains("mobile-nav-toggle")
                ) {
                    // Mobile: Remove default white text
                    link.classList.remove("text-white", "text-white/90");
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
                mobileSpctToggle,
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
                mobileMenu.classList.toggle("hidden");
                // Prevent body scroll when menu is open
                if (!mobileMenu.classList.contains("hidden")) {
                    document.body.style.overflow = "hidden";
                } else {
                    document.body.style.overflow = "";
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

            // 3. Mobile Products/SPCT Submenu Toggle
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
            bindMobileSubmenuToggle(
                mobileSpctToggle,
                "mobile-spct-submenu",
                "mobile-spct-icon",
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
