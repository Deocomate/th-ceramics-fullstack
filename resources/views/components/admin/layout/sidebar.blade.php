<aside class="fixed inset-y-0 left-0 z-40 w-64 flex flex-col bg-[#0f0f13]">
    {{-- Brand --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-white/[0.06]">
        <div
            class="w-9 h-9 rounded-xl flex items-center justify-center bg-gradient-to-br from-[#A31D1D] to-[#d94444] shadow-lg shadow-red-900/30">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
        </div>
        <div class="min-w-0">
            <span class="text-white font-bold text-[15px] tracking-tight block leading-tight">TH Ceramics</span>
            <span class="text-gray-500 text-[10px] font-medium uppercase tracking-widest">Admin Panel</span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5 custom-scrollbar">
        {{-- Dashboard --}}
        @php $isDashboard = request()->routeIs('admin.dashboard'); @endphp
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 {{ $isDashboard ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isDashboard ? 'text-[#A31D1D]' : '' }}" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-2a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z" />
            </svg>
            Tổng quan
        </a>

        <!-- HỆ THỐNG CẤU HÌNH -->
        <div class="pt-4 pb-1">
            <p class="px-3 text-[10px] font-semibold uppercase tracking-[0.15em] text-gray-600">
                Hệ thống cấu hình
            </p>
        </div>

        <!-- 1. CẤU HÌNH SECTION CHUNG -->
        @php $isSectionChung = request()->routeIs('admin.gia-tri-vuot-troi.*'); @endphp
        <div>
            <button type="button"
                class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]"
                onclick="toggleSubmenu('submenu-section-chung', this)">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Cấu hình section chung
                </div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isSectionChung ? 'rotate-180' : '' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div id="submenu-section-chung"
                class="grid transition-all duration-300 ease-in-out {{ $isSectionChung ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden">
                    <div class="pl-9 pr-3 space-y-1 pb-1">
                        <a href="{{ route('admin.gia-tri-vuot-troi.index') }}"
                            class="block px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 {{ request()->routeIs('admin.gia-tri-vuot-troi.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">
                            Giá trị vượt trội
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. CẤU HÌNH TRANG ĐƠN -->
        @php $isPageConfig = request()->routeIs(['admin.pages.*']); @endphp
        <div>
            <button type="button"
                class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]"
                onclick="toggleSubmenu('submenu-page-config', this)">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Cấu hình trang đơn
                </div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isPageConfig ? 'rotate-180' : '' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div id="submenu-page-config"
                class="grid transition-all duration-300 ease-in-out {{ $isPageConfig ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden">
                    <div class="pl-9 pr-3 space-y-1 pb-1">
                        <a href="{{ route('admin.trang_chu.edit') }}"
                            class="block px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 {{ request()->routeIs('admin.trang_chu.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">
                            Trang chủ
                        </a>
                        <a href="{{ route('admin.pages.factory.edit') }}"
                            class="block px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 {{ request()->routeIs('admin.pages.factory.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">
                            Trang Xưởng Sản Xuất
                        </a>
                        <a href="{{ route('admin.pages.contact.edit') }}"
                            class="block px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 {{ request()->routeIs('admin.pages.contact.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">
                            Trang Liên Hệ
                        </a>
                        <a href="{{ route('admin.pages.faq.edit') }}"
                            class="block px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 {{ request()->routeIs('admin.pages.faq.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">
                            Trang FAQ
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. CẤU HÌNH TRANG SẢN PHẨM -->
        @php
            $isProductConfig = request()->routeIs([
                'admin.ngoi-am-duong.*',
                'admin.ngoi-hai-van-mieu.*',
                'admin.gach-hoa-thong-gio.*',
                'admin.phu-kien-ngoi.*',
                'admin.gach-trang-tri.*',
                'admin.lan-can-gom-xu.*',
                'admin.gach-co-bat-trang.*',
                'admin.linh-vat-phong-thuy.*',
                'admin.den-gom-su.*',
            ]);
            $products = [
                [
                    'route' => 'admin.ngoi-am-duong.index',
                    'name' => 'Ngói Âm Dương',
                    'active' => 'admin.ngoi-am-duong.*',
                ],
                [
                    'route' => 'admin.ngoi-hai-van-mieu.index',
                    'name' => 'Ngói Hài Văn Miếu',
                    'active' => 'admin.ngoi-hai-van-mieu.*',
                ],
                [
                    'route' => 'admin.gach-hoa-thong-gio.index',
                    'name' => 'Gạch Hoa Thông Gió',
                    'active' => 'admin.gach-hoa-thong-gio.*',
                ],
                [
                    'route' => 'admin.phu-kien-ngoi.index',
                    'name' => 'Phụ Kiện Ngói',
                    'active' => 'admin.phu-kien-ngoi.*',
                ],
                [
                    'route' => 'admin.gach-trang-tri.index',
                    'name' => 'Gạch Trang Trí',
                    'active' => 'admin.gach-trang-tri.*',
                ],
                [
                    'route' => 'admin.lan-can-gom-xu.index',
                    'name' => 'Lan Can Gốm Sứ',
                    'active' => 'admin.lan-can-gom-xu.*',
                ],
                [
                    'route' => 'admin.gach-co-bat-trang.index',
                    'name' => 'Gạch Cổ Bát Tràng',
                    'active' => 'admin.gach-co-bat-trang.*',
                ],
                [
                    'route' => 'admin.linh-vat-phong-thuy.index',
                    'name' => 'Linh Vật Phong Thủy',
                    'active' => 'admin.linh-vat-phong-thuy.*',
                ],
                ['route' => 'admin.den-gom-su.index', 'name' => 'Đèn Gốm Sứ', 'active' => 'admin.den-gom-su.*'],
            ];
        @endphp
        <div>
            <button type="button"
                class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]"
                onclick="toggleSubmenu('submenu-product-config', this)">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Cấu hình trang sản phẩm
                </div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isProductConfig ? 'rotate-180' : '' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div id="submenu-product-config"
                class="grid transition-all duration-300 ease-in-out {{ $isProductConfig ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden">
                    <div class="pl-9 pr-3 space-y-1 pb-1">
                        @foreach ($products as $prod)
                            <a href="{{ route($prod['route']) }}"
                                class="block px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 {{ request()->routeIs($prod['active']) ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">
                                {{ $prod['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. DANH SÁCH SẢN PHẨM CHI TIẾT (Dropdown cấp 1 + cấp 2) -->
        @php
            // Nhóm biến trạng thái cấp 2
            $isNAD = request()->routeIs([
                'admin.ngoi-am-duong-ct.*',
                'admin.mau-sac-ngoi-am-duong-ct.*',
                'admin.dinh-muc-ngoi-am-duong.*',
            ]);
            $isNHC = request()->routeIs([
                'admin.ngoi-hai-co-ct.*',
                'admin.mau-sac-ngoi-hai-co-ct.*',
                'admin.dinh-muc-ngoi-hai-co.*',
            ]);
            $isNHVM = request()->routeIs([
                'admin.ngoi-hai-van-mieu-ct.*',
                'admin.mau-sac-ngoi-hai-van-mieu-ct.*',
                'admin.dinh-muc-ngoi-hai-van-mieu.*',
            ]);
            $isGHTG = request()->routeIs(['admin.gach-hoa-thong-gio-ct.*', 'admin.dinh-muc-gach-hoa-thong-gio.*']);
            $isGTT = request()->routeIs(['admin.gach-trang-tri-ct.*', 'admin.dinh-muc-gach-trang-tri.*']);
            $isGCBT = request()->routeIs(['admin.gach-co-bat-trang-ct.*', 'admin.dinh-muc-gach-co-bat-trang.*']);
            $isLVPT = request()->routeIs(['admin.linh-vat-phong-thuy-ct.*']);
            $isNBN = request()->routeIs(['admin.ngoi-bo-noc-ct.*', 'admin.phan-loai-ngoi-bo-noc-ct.*']);
            $isBNCV = request()->routeIs(['admin.bo-noc-chu-van-ct.*', 'admin.phan-loai-bo-noc-chu-van-ct.*']);

            // Biến trạng thái tổng (cấp 1)
            $isProductDetail =
                $isNAD || $isNHC || $isNHVM || $isGHTG || $isGTT || $isGCBT || $isLVPT || $isNBN || $isBNCV;
        @endphp
        <div>
            <button type="button"
                class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]"
                onclick="toggleSubmenu('submenu-product-detail', this)">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    DS Sản phẩm chi tiết
                </div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isProductDetail ? 'rotate-180' : '' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div id="submenu-product-detail"
                class="grid transition-all duration-300 ease-in-out {{ $isProductDetail ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden">
                    <div class="pl-7 pr-3 pb-2 pt-1">

                        <!-- Ngói Âm Dương (Có menu con) -->
                        <div class="mb-1">
                            <button type="button"
                                class="w-full flex items-center justify-between px-2 py-2 rounded-md text-[11px] font-bold {{ $isNAD ? 'text-gray-200' : 'text-gray-500' }} uppercase tracking-wider hover:text-gray-300 hover:bg-white/[0.04] transition-colors"
                                onclick="toggleSubmenu('submenu-nad', this)">
                                Ngói Âm Dương
                                <svg class="chevron-icon w-3.5 h-3.5 transition-transform duration-300 {{ $isNAD ? 'rotate-180' : '' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="submenu-nad"
                                class="grid transition-all duration-300 ease-in-out {{ $isNAD ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                                <div class="overflow-hidden space-y-1 pl-2 border-l border-white/[0.05] ml-2">
                                    <a href="{{ route('admin.ngoi-am-duong-ct.index') }}"
                                        class="block px-3 py-1.5 rounded-md text-[12px] font-medium transition-all duration-200 {{ request()->routeIs('admin.ngoi-am-duong-ct.*') ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.02]' }}">DS
                                        sản phẩm</a>
                                    <a href="{{ route('admin.mau-sac-ngoi-am-duong-ct.index') }}"
                                        class="block px-3 py-1.5 rounded-md text-[12px] font-medium transition-all duration-200 {{ request()->routeIs('admin.mau-sac-ngoi-am-duong-ct.*') ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.02]' }}">Quản
                                        lý màu sắc</a>
                                    <a href="{{ route('admin.dinh-muc-ngoi-am-duong.index') }}"
                                        class="block px-3 py-1.5 rounded-md text-[12px] font-medium transition-all duration-200 {{ request()->routeIs('admin.dinh-muc-ngoi-am-duong.*') ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.02]' }}">Quản
                                        lý định mức</a>
                                </div>
                            </div>
                        </div>

                        <!-- Ngói Hài Cổ (Có menu con) -->
                        <div class="mb-1">
                            <button type="button"
                                class="w-full flex items-center justify-between px-2 py-2 rounded-md text-[11px] font-bold {{ $isNHC ? 'text-gray-200' : 'text-gray-500' }} uppercase tracking-wider hover:text-gray-300 hover:bg-white/[0.04] transition-colors"
                                onclick="toggleSubmenu('submenu-nhc', this)">
                                Ngói Hài Cổ
                                <svg class="chevron-icon w-3.5 h-3.5 transition-transform duration-300 {{ $isNHC ? 'rotate-180' : '' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="submenu-nhc"
                                class="grid transition-all duration-300 ease-in-out {{ $isNHC ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                                <div class="overflow-hidden space-y-1 pl-2 border-l border-white/[0.05] ml-2">
                                    <a href="{{ route('admin.ngoi-hai-co-ct.index') }}"
                                        class="block px-3 py-1.5 rounded-md text-[12px] font-medium transition-all duration-200 {{ request()->routeIs(['admin.ngoi-hai-co-ct.*', 'admin.mau-sac-ngoi-hai-co-ct.*']) ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.02]' }}">DS
                                        sản phẩm</a>
                                    <a href="{{ route('admin.dinh-muc-ngoi-hai-co.index') }}"
                                        class="block px-3 py-1.5 rounded-md text-[12px] font-medium transition-all duration-200 {{ request()->routeIs('admin.dinh-muc-ngoi-hai-co.*') ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.02]' }}">Quản
                                        lý định mức</a>
                                </div>
                            </div>
                        </div>

                        <!-- Ngói Hài Văn Miếu (Có menu con) -->
                        <div class="mb-1">
                            <button type="button"
                                class="w-full flex items-center justify-between px-2 py-2 rounded-md text-[11px] font-bold {{ $isNHVM ? 'text-gray-200' : 'text-gray-500' }} uppercase tracking-wider hover:text-gray-300 hover:bg-white/[0.04] transition-colors"
                                onclick="toggleSubmenu('submenu-nhvm', this)">
                                Ngói Hài Văn Miếu
                                <svg class="chevron-icon w-3.5 h-3.5 transition-transform duration-300 {{ $isNHVM ? 'rotate-180' : '' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="submenu-nhvm"
                                class="grid transition-all duration-300 ease-in-out {{ $isNHVM ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                                <div class="overflow-hidden space-y-1 pl-2 border-l border-white/[0.05] ml-2">
                                    <a href="{{ route('admin.ngoi-hai-van-mieu-ct.index') }}"
                                        class="block px-3 py-1.5 rounded-md text-[12px] font-medium transition-all duration-200 {{ request()->routeIs(['admin.ngoi-hai-van-mieu-ct.*', 'admin.mau-sac-ngoi-hai-van-mieu-ct.*']) ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.02]' }}">DS
                                        sản phẩm</a>
                                    <a href="{{ route('admin.dinh-muc-ngoi-hai-van-mieu.index') }}"
                                        class="block px-3 py-1.5 rounded-md text-[12px] font-medium transition-all duration-200 {{ request()->routeIs('admin.dinh-muc-ngoi-hai-van-mieu.*') ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.02]' }}">Quản
                                        lý định mức</a>
                                </div>
                            </div>
                        </div>

                        <!-- Gạch Hoa Thông Gió (Có menu con) -->
                        <div class="mb-1">
                            <button type="button"
                                class="w-full flex items-center justify-between px-2 py-2 rounded-md text-[11px] font-bold {{ $isGHTG ? 'text-gray-200' : 'text-gray-500' }} uppercase tracking-wider hover:text-gray-300 hover:bg-white/[0.04] transition-colors"
                                onclick="toggleSubmenu('submenu-ghtg', this)">
                                Gạch Hoa Thông Gió
                                <svg class="chevron-icon w-3.5 h-3.5 transition-transform duration-300 {{ $isGHTG ? 'rotate-180' : '' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="submenu-ghtg"
                                class="grid transition-all duration-300 ease-in-out {{ $isGHTG ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                                <div class="overflow-hidden space-y-1 pl-2 border-l border-white/[0.05] ml-2">
                                    <a href="{{ route('admin.gach-hoa-thong-gio-ct.index') }}"
                                        class="block px-3 py-1.5 rounded-md text-[12px] font-medium transition-all duration-200 {{ request()->routeIs('admin.gach-hoa-thong-gio-ct.*') ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.02]' }}">DS
                                        sản phẩm</a>
                                    <a href="{{ route('admin.dinh-muc-gach-hoa-thong-gio.index') }}"
                                        class="block px-3 py-1.5 rounded-md text-[12px] font-medium transition-all duration-200 {{ request()->routeIs('admin.dinh-muc-gach-hoa-thong-gio.*') ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.02]' }}">Quản
                                        lý định mức</a>
                                </div>
                            </div>
                        </div>

                        <!-- Gạch Trang Trí (Có menu con) -->
                        <div class="mb-1">
                            <button type="button"
                                class="w-full flex items-center justify-between px-2 py-2 rounded-md text-[11px] font-bold {{ $isGTT ? 'text-gray-200' : 'text-gray-500' }} uppercase tracking-wider hover:text-gray-300 hover:bg-white/[0.04] transition-colors"
                                onclick="toggleSubmenu('submenu-gtt', this)">
                                Gạch Trang Trí
                                <svg class="chevron-icon w-3.5 h-3.5 transition-transform duration-300 {{ $isGTT ? 'rotate-180' : '' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="submenu-gtt"
                                class="grid transition-all duration-300 ease-in-out {{ $isGTT ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                                <div class="overflow-hidden space-y-1 pl-2 border-l border-white/[0.05] ml-2">
                                    <a href="{{ route('admin.gach-trang-tri-ct.index') }}"
                                        class="block px-3 py-1.5 rounded-md text-[12px] font-medium transition-all duration-200 {{ request()->routeIs('admin.gach-trang-tri-ct.*') ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.02]' }}">DS
                                        sản phẩm</a>
                                    <a href="{{ route('admin.dinh-muc-gach-trang-tri.index') }}"
                                        class="block px-3 py-1.5 rounded-md text-[12px] font-medium transition-all duration-200 {{ request()->routeIs('admin.dinh-muc-gach-trang-tri.*') ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.02]' }}">Quản
                                        lý định mức</a>
                                </div>
                            </div>
                        </div>

                        <!-- Gạch Cổ Bát Tràng (Có menu con) -->
                        <div class="mb-1">
                            <button type="button"
                                class="w-full flex items-center justify-between px-2 py-2 rounded-md text-[11px] font-bold {{ $isGCBT ? 'text-gray-200' : 'text-gray-500' }} uppercase tracking-wider hover:text-gray-300 hover:bg-white/[0.04] transition-colors"
                                onclick="toggleSubmenu('submenu-gcbt', this)">
                                Gạch Cổ Bát Tràng
                                <svg class="chevron-icon w-3.5 h-3.5 transition-transform duration-300 {{ $isGCBT ? 'rotate-180' : '' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="submenu-gcbt"
                                class="grid transition-all duration-300 ease-in-out {{ $isGCBT ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                                <div class="overflow-hidden space-y-1 pl-2 border-l border-white/[0.05] ml-2">
                                    <a href="{{ route('admin.gach-co-bat-trang-ct.index') }}"
                                        class="block px-3 py-1.5 rounded-md text-[12px] font-medium transition-all duration-200 {{ request()->routeIs('admin.gach-co-bat-trang-ct.*') ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.02]' }}">DS
                                        sản phẩm</a>
                                    <a href="{{ route('admin.dinh-muc-gach-co-bat-trang.index') }}"
                                        class="block px-3 py-1.5 rounded-md text-[12px] font-medium transition-all duration-200 {{ request()->routeIs('admin.dinh-muc-gach-co-bat-trang.*') ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.02]' }}">Quản
                                        lý định mức</a>
                                </div>
                            </div>
                        </div>

                        <!-- Linh Vật Phong Thủy (Chỉ có 1 con -> Chuyển thành link trực tiếp) -->
                        <div class="mb-1">
                            <a href="{{ route('admin.linh-vat-phong-thuy-ct.index') }}"
                                class="block w-full px-2 py-2 rounded-md text-[11px] font-bold uppercase tracking-wider transition-colors {{ $isLVPT ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.04]' }}">
                                Linh Vật Phong Thủy
                            </a>
                        </div>

                        <!-- Ngói Bò Nóc (Chỉ có 1 con -> Chuyển thành link trực tiếp) -->
                        <div class="mb-1">
                            <a href="{{ route('admin.ngoi-bo-noc-ct.index') }}"
                                class="block w-full px-2 py-2 rounded-md text-[11px] font-bold uppercase tracking-wider transition-colors {{ $isNBN ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.04]' }}">
                                Ngói Bò Nóc
                            </a>
                        </div>

                        <!-- Bò Nóc Chữ Vạn (Chỉ có 1 con -> Chuyển thành link trực tiếp) -->
                        <div class="mb-1">
                            <a href="{{ route('admin.bo-noc-chu-van-ct.index') }}"
                                class="block w-full px-2 py-2 rounded-md text-[11px] font-bold uppercase tracking-wider transition-colors {{ $isBNCV ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.04]' }}">
                                Bò Nóc Chữ Vạn
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Superadmin section --}}
        @if (auth()->user()?->isSuperAdmin())
            <div class="pt-4 pb-1">
                <p class="px-3 text-[10px] font-semibold uppercase tracking-[0.15em] text-gray-600">
                    Quản trị viên
                </p>
            </div>
            @php $isUsers = request()->routeIs('admin.users.*'); @endphp
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 {{ $isUsers ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isUsers ? 'text-[#A31D1D]' : '' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Danh sách Admin
            </a>
        @endif
    </nav>

    {{-- User info + Logout --}}
    <div class="px-3 py-4 border-t border-white/[0.06]">
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-white/[0.04] mb-2">
            <div
                class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold flex-shrink-0 bg-gradient-to-br from-[#A31D1D] to-[#d94444]">
                {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-white text-[13px] font-medium truncate">{{ auth()->user()?->name }}</p>
                <p class="text-gray-500 text-[11px] truncate capitalize">{{ auth()->user()?->role }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.auth.logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] text-gray-500 hover:text-gray-300 hover:bg-white/[0.04] transition-all duration-200">
                <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Đăng xuất
            </button>
        </form>
    </div>
</aside>

@push('scripts')
    <script>
        // Xử lý logic Animation mượt mà bằng CSS Grid transition cho dropdown (Dùng chung cho cả Cấp 1 & Cấp 2)
        function toggleSubmenu(id, btn) {
            const wrapper = document.getElementById(id);
            const icon = btn.querySelector('.chevron-icon');

            if (wrapper.classList.contains('grid-rows-[1fr]')) {
                wrapper.classList.remove('grid-rows-[1fr]', 'opacity-100', 'mt-1');
                wrapper.classList.add('grid-rows-[0fr]', 'opacity-0', 'mt-0');
                icon.classList.remove('rotate-180');
            } else {
                wrapper.classList.remove('grid-rows-[0fr]', 'opacity-0', 'mt-0');
                wrapper.classList.add('grid-rows-[1fr]', 'opacity-100', 'mt-1');
                icon.classList.add('rotate-180');
            }
        }
    </script>
@endpush
