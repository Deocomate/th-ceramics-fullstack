{{-- resources/views/components/admin/layout/sidebar.blade.php --}}
<aside class="fixed inset-y-0 left-0 z-40 w-64 flex flex-col bg-[#0f0f13]">
    {{-- Brand --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-white/[0.06]">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-gradient-to-br from-[#A31D1D] to-[#d94444] shadow-lg shadow-red-900/30">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
        </div>
        <div class="min-w-0">
            <span class="text-white font-bold text-[15px] tracking-tight block leading-tight">TH Ceramics</span>
            <span class="text-gray-500 text-[10px] font-medium uppercase tracking-widest">Admin Panel</span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 custom-scrollbar">
        {{-- Dashboard --}}
        @php $isDashboard = request()->routeIs('admin.dashboard'); @endphp
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 {{ $isDashboard ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isDashboard ? 'text-[#A31D1D]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-2a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z" />
            </svg>
            Tổng quan
        </a>

        <!-- ================= BÁN HÀNG ================= -->
        <div class="pt-4 pb-1">
            <p class="px-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-500">Quản lý bán hàng</p>
        </div>

        <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 {{ request()->routeIs('admin.orders.*') ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.orders.*') ? 'text-[#A31D1D]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            Đơn hàng
            @unless ($isEcommerceEnabled ?? true)
                <span class="ml-auto text-[9px] text-gray-500 italic">(đã tắt)</span>
            @endunless
        </a>

        <a href="{{ route('admin.consultation-requests.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 {{ request()->routeIs('admin.consultation-requests.*') ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.consultation-requests.*') ? 'text-[#A31D1D]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16h6m2 5H7a2 2 0 01-2-2V7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Yêu cầu tư vấn
            @php $pendingConsult = \App\Models\ConsultationRequest::where('status', 'pending')->count(); @endphp
            @if ($pendingConsult > 0)
                <span class="ml-auto bg-[#A31D1D] text-white text-[10px] rounded-full px-1.5 py-0.5">{{ $pendingConsult }}</span>
            @endif
        </a>

        <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 {{ request()->routeIs('admin.customers.*') ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.customers.*') ? 'text-[#A31D1D]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m8-6.13a4 4 0 11-8 0 4 4 0 018 0zm-4 8a4 4 0 00-4-4m4 4a4 4 0 014-4" />
            </svg>
            Khách hàng
        </a>

        <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 {{ request()->routeIs('admin.coupons.*') ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.coupons.*') ? 'text-[#A31D1D]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
            </svg>
            Mã giảm giá
        </a>


        <!-- ================= SẢN PHẨM ================= -->
        <div class="pt-4 pb-1">
            <p class="px-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-500">Danh mục sản phẩm</p>
        </div>

        {{-- 1. Ngói Âm Dương --}}
        @php $isNAD = request()->routeIs(['admin.ngoi-am-duong.*', 'admin.ngoi-am-duong-ct.*', 'admin.mau-sac-ngoi-am-duong-ct.*', 'admin.dinh-muc-ngoi-am-duong.*']); @endphp
        <div>
            <button type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]" onclick="toggleSubmenu('menu-nad', this)">
                <div class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-red-800"></span>Ngói Âm Dương</div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isNAD ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="menu-nad" class="grid transition-all duration-300 ease-in-out {{ $isNAD ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden pl-7 pr-3 space-y-1 pb-1">
                    <a href="{{ route('admin.ngoi-am-duong.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.ngoi-am-duong.index') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Cấu hình trang</a>
                    <a href="{{ route('admin.ngoi-am-duong-ct.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.ngoi-am-duong-ct.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Sản phẩm chi tiết</a>
                    <a href="{{ route('admin.mau-sac-ngoi-am-duong-ct.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.mau-sac-ngoi-am-duong-ct.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Quản lý màu sắc</a>
                    <a href="{{ route('admin.dinh-muc-ngoi-am-duong.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.dinh-muc-ngoi-am-duong.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Định mức thi công</a>
                </div>
            </div>
        </div>

        {{-- 2. Ngói Hài --}}
        @php $isNgoiHai = request()->routeIs(['admin.ngoi-hai-van-mieu.*', 'admin.ngoi-hai-van-mieu-ct.*', 'admin.mau-sac-ngoi-hai-van-mieu-ct.*', 'admin.dinh-muc-ngoi-hai-van-mieu.*', 'admin.ngoi-hai-co-ct.*', 'admin.mau-sac-ngoi-hai-co-ct.*', 'admin.dinh-muc-ngoi-hai-co.*']); @endphp
        <div>
            <button type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]" onclick="toggleSubmenu('menu-ngoihai', this)">
                <div class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-red-700"></span>Ngói Hài</div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isNgoiHai ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="menu-ngoihai" class="grid transition-all duration-300 ease-in-out {{ $isNgoiHai ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden pl-7 pr-3 space-y-1 pb-1">
                    <a href="{{ route('admin.ngoi-hai-van-mieu.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.ngoi-hai-van-mieu.index') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Cấu hình trang chính</a>
                    <div class="px-3 py-1 mt-2 text-[10px] font-bold text-gray-600 uppercase">Ngói Hài Văn Miếu</div>
                    <a href="{{ route('admin.ngoi-hai-van-mieu-ct.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs(['admin.ngoi-hai-van-mieu-ct.*', 'admin.mau-sac-ngoi-hai-van-mieu-ct.*']) ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Danh sách SP & Màu</a>
                    <a href="{{ route('admin.dinh-muc-ngoi-hai-van-mieu.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.dinh-muc-ngoi-hai-van-mieu.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Định mức thi công</a>
                    <div class="px-3 py-1 mt-2 text-[10px] font-bold text-gray-600 uppercase">Ngói Hài Cổ</div>
                    <a href="{{ route('admin.ngoi-hai-co-ct.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs(['admin.ngoi-hai-co-ct.*', 'admin.mau-sac-ngoi-hai-co-ct.*']) ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Danh sách SP & Màu</a>
                    <a href="{{ route('admin.dinh-muc-ngoi-hai-co.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.dinh-muc-ngoi-hai-co.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Định mức thi công</a>
                </div>
            </div>
        </div>

        {{-- 3. Gạch Hoa Thông Gió --}}
        @php $isGHTG = request()->routeIs(['admin.gach-hoa-thong-gio.*', 'admin.gach-hoa-thong-gio-ct.*', 'admin.dinh-muc-gach-hoa-thong-gio.*']); @endphp
        <div>
            <button type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]" onclick="toggleSubmenu('menu-ghtg', this)">
                <div class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-red-600"></span>Gạch Hoa Thông Gió</div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isGHTG ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="menu-ghtg" class="grid transition-all duration-300 ease-in-out {{ $isGHTG ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden pl-7 pr-3 space-y-1 pb-1">
                    <a href="{{ route('admin.gach-hoa-thong-gio.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.gach-hoa-thong-gio.index') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Cấu hình trang</a>
                    <a href="{{ route('admin.gach-hoa-thong-gio-ct.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.gach-hoa-thong-gio-ct.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Sản phẩm chi tiết</a>
                    <a href="{{ route('admin.dinh-muc-gach-hoa-thong-gio.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.dinh-muc-gach-hoa-thong-gio.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Định mức thi công</a>
                </div>
            </div>
        </div>

        {{-- 4. Gạch Trang Trí --}}
        @php $isGTT = request()->routeIs(['admin.gach-trang-tri.*', 'admin.gach-trang-tri-ct.*', 'admin.dinh-muc-gach-trang-tri.*']); @endphp
        <div>
            <button type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]" onclick="toggleSubmenu('menu-gtt', this)">
                <div class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-red-500"></span>Gạch Trang Trí</div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isGTT ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="menu-gtt" class="grid transition-all duration-300 ease-in-out {{ $isGTT ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden pl-7 pr-3 space-y-1 pb-1">
                    <a href="{{ route('admin.gach-trang-tri.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.gach-trang-tri.index') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Cấu hình trang</a>
                    <a href="{{ route('admin.gach-trang-tri-ct.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.gach-trang-tri-ct.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Sản phẩm chi tiết</a>
                    <a href="{{ route('admin.dinh-muc-gach-trang-tri.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.dinh-muc-gach-trang-tri.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Định mức thi công</a>
                </div>
            </div>
        </div>

        {{-- 5. Gạch Cổ Bát Tràng --}}
        @php $isGCBT = request()->routeIs(['admin.gach-co-bat-trang.*', 'admin.gach-co-bat-trang-ct.*', 'admin.dinh-muc-gach-co-bat-trang.*']); @endphp
        <div>
            <button type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]" onclick="toggleSubmenu('menu-gcbt', this)">
                <div class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-orange-700"></span>Gạch Cổ Bát Tràng</div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isGCBT ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="menu-gcbt" class="grid transition-all duration-300 ease-in-out {{ $isGCBT ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden pl-7 pr-3 space-y-1 pb-1">
                    <a href="{{ route('admin.gach-co-bat-trang.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.gach-co-bat-trang.index') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Cấu hình trang</a>
                    <a href="{{ route('admin.gach-co-bat-trang-ct.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.gach-co-bat-trang-ct.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Sản phẩm chi tiết</a>
                    <a href="{{ route('admin.dinh-muc-gach-co-bat-trang.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.dinh-muc-gach-co-bat-trang.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Định mức thi công</a>
                </div>
            </div>
        </div>

        {{-- 6. Phụ Kiện Ngói --}}
        @php
            $pknCategory = request()->query('category_type');
            $isPKN = request()->routeIs(['admin.phu-kien-ngoi.*', 'admin.phu-kien-ngoi-ct.*', 'admin.phan-loai-phu-kien-ngoi-ct.*']);
            $isPKNBoNoc = $isPKN && $pknCategory === 'bo_noc' && ! request()->routeIs('admin.phu-kien-ngoi.index');
            $isPKNChuVan = $isPKN && $pknCategory === 'chu_van' && ! request()->routeIs('admin.phu-kien-ngoi.index');
        @endphp
        <div>
            <button type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]" onclick="toggleSubmenu('menu-pkn', this)">
                <div class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-orange-500"></span>Phụ Kiện Ngói</div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isPKN ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="menu-pkn" class="grid transition-all duration-300 ease-in-out {{ $isPKN ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden pl-7 pr-3 space-y-1 pb-1">
                    <a href="{{ route('admin.phu-kien-ngoi.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.phu-kien-ngoi.index') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Cấu hình trang chính</a>
                    <div class="px-3 py-1 mt-2 text-[10px] font-bold text-gray-600 uppercase">Ngói Bò Nóc</div>
                    <a href="{{ route('admin.phu-kien-ngoi-ct.index', ['category_type' => 'bo_noc']) }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ $isPKNBoNoc ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Danh sách SP & Phân loại</a>
                    <div class="px-3 py-1 mt-2 text-[10px] font-bold text-gray-600 uppercase">Bò Nóc Chữ Vạn</div>
                    <a href="{{ route('admin.phu-kien-ngoi-ct.index', ['category_type' => 'chu_van']) }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ $isPKNChuVan ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Danh sách SP & Phân loại</a>
                </div>
            </div>
        </div>

        {{-- 7. Lan Can Gốm Sứ --}}
        @php $isLCGS = request()->routeIs(['admin.lan-can-gom-xu.*', 'admin.lan-can-gom-su-ct.*', 'admin.phan-loai-lan-can-gom-su-ct.*']); @endphp
        <div>
            <button type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]" onclick="toggleSubmenu('menu-lcgs', this)">
                <div class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-yellow-500"></span>Lan Can Gốm Sứ</div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isLCGS ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="menu-lcgs" class="grid transition-all duration-300 ease-in-out {{ $isLCGS ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden pl-7 pr-3 space-y-1 pb-1">
                    <a href="{{ route('admin.lan-can-gom-xu.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.lan-can-gom-xu.index') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Cấu hình trang</a>
                    <a href="{{ route('admin.lan-can-gom-su-ct.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs(['admin.lan-can-gom-su-ct.*', 'admin.phan-loai-lan-can-gom-su-ct.*']) ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Sản phẩm & Phân loại</a>
                </div>
            </div>
        </div>

        {{-- 8. Đèn Vườn Gốm Sứ --}}
        @php $isDVS = request()->routeIs(['admin.den-gom-su.*', 'admin.den-vuon-gom-su-ct.*', 'admin.phan-loai-den-vuon-gom-su-ct.*']); @endphp
        <div>
            <button type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]" onclick="toggleSubmenu('menu-dvs', this)">
                <div class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-yellow-300"></span>Đèn Vườn Gốm Sứ</div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isDVS ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="menu-dvs" class="grid transition-all duration-300 ease-in-out {{ $isDVS ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden pl-7 pr-3 space-y-1 pb-1">
                    <a href="{{ route('admin.den-gom-su.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.den-gom-su.index') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Cấu hình trang</a>
                    <a href="{{ route('admin.den-vuon-gom-su-ct.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs(['admin.den-vuon-gom-su-ct.*', 'admin.phan-loai-den-vuon-gom-su-ct.*']) ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Sản phẩm & Phân loại</a>
                </div>
            </div>
        </div>

        {{-- 9. Linh Vật Phong Thủy --}}
        @php $isLVPT = request()->routeIs(['admin.linh-vat-phong-thuy.*', 'admin.linh-vat-phong-thuy-ct.*']); @endphp
        <div>
            <button type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]" onclick="toggleSubmenu('menu-lvpt', this)">
                <div class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-cyan-600"></span>Linh Vật Phong Thủy</div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isLVPT ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="menu-lvpt" class="grid transition-all duration-300 ease-in-out {{ $isLVPT ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden pl-7 pr-3 space-y-1 pb-1">
                    <a href="{{ route('admin.linh-vat-phong-thuy.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.linh-vat-phong-thuy.index') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Cấu hình trang</a>
                    <a href="{{ route('admin.linh-vat-phong-thuy-ct.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.linh-vat-phong-thuy-ct.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Sản phẩm chi tiết</a>
                </div>
            </div>
        </div>

        <!-- ================= NỘI DUNG (CMS) ================= -->
        <div class="pt-4 pb-1">
            <p class="px-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-500">Quản lý nội dung</p>
        </div>

        {{-- Tin Tức --}}
        @php $isNews = request()->routeIs(['admin.danh-muc-tin-tuc.*', 'admin.tin-tuc.*']); @endphp
        <div>
            <button type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]" onclick="toggleSubmenu('menu-news', this)">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 7H20" /></svg>
                    Tin tức
                </div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isNews ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="menu-news" class="grid transition-all duration-300 ease-in-out {{ $isNews ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden pl-9 pr-3 space-y-1 pb-1">
                    <a href="{{ route('admin.danh-muc-tin-tuc.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.danh-muc-tin-tuc.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Danh mục</a>
                    <a href="{{ route('admin.tin-tuc.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.tin-tuc.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Bài viết</a>
                </div>
            </div>
        </div>

        {{-- Dự Án --}}
        @php $isProject = request()->routeIs(['admin.danh-muc-du-an.*', 'admin.du-an.*', 'admin.trang-du-an.*']); @endphp
        <div>
            <button type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]" onclick="toggleSubmenu('menu-project', this)">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                    Dự án
                </div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isProject ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="menu-project" class="grid transition-all duration-300 ease-in-out {{ $isProject ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden pl-9 pr-3 space-y-1 pb-1">
                    <a href="{{ route('admin.danh-muc-du-an.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.danh-muc-du-an.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Danh mục</a>
                    <a href="{{ route('admin.du-an.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.du-an.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Dự án</a>
                    <a href="{{ route('admin.trang-du-an.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.trang-du-an.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Cấu hình trang</a>
                </div>
            </div>
        </div>

        {{-- Các Nội Dung Khác --}}
        @php $isMiscContent = request()->routeIs(['admin.thi-cong.*', 'admin.catalog.*']); @endphp
        <div>
            <button type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]" onclick="toggleSubmenu('menu-misc', this)">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    Nội dung bổ sung
                </div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isMiscContent ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="menu-misc" class="grid transition-all duration-300 ease-in-out {{ $isMiscContent ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden pl-9 pr-3 space-y-1 pb-1">
                    <a href="{{ route('admin.thi-cong.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.thi-cong.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Video thi công</a>
                    <a href="{{ route('admin.catalog.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.catalog.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Catalogs</a>
                </div>
            </div>
        </div>


        <!-- ================= GIAO DIỆN & CẤU HÌNH ================= -->
        <div class="pt-4 pb-1">
            <p class="px-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-500">Giao diện & Cấu hình</p>
        </div>

        {{-- Cấu Hình Trang Đơn --}}
        @php $isPages = request()->routeIs(['admin.trang_chu.*', 'admin.pages.*']); @endphp
        <div>
            <button type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]" onclick="toggleSubmenu('menu-pages', this)">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Cấu hình trang
                </div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isPages ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="menu-pages" class="grid transition-all duration-300 ease-in-out {{ $isPages ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden pl-9 pr-3 space-y-1 pb-1">
                    <a href="{{ route('admin.trang_chu.edit') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.trang_chu.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Trang Chủ</a>
                    <a href="{{ route('admin.pages.ve_chung_toi.edit') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.pages.ve_chung_toi.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Trang Về Chúng Tôi</a>
                    <a href="{{ route('admin.pages.factory.edit') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.pages.factory.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Trang Xưởng Sản Xuất</a>
                    <a href="{{ route('admin.pages.contact.edit') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.pages.contact.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Trang Liên Hệ</a>
                    <a href="{{ route('admin.pages.faq.edit') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.pages.faq.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Trang FAQ</a>
                    <a href="{{ route('admin.pages.faqs.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.pages.faqs.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Danh Sách FAQ</a>
                </div>
            </div>
        </div>

        {{-- Thành phần chung (Dùng cho nhiều trang) --}}
        @php $isShared = request()->routeIs(['admin.gia-tri-vuot-troi.*', 'admin.giai-thuong-thanh-tuu.*']); @endphp
        <div>
            <button type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]" onclick="toggleSubmenu('menu-shared', this)">
                <div class="flex items-center gap-3">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    Khối thành phần chung
                </div>
                <svg class="chevron-icon w-4 h-4 transition-transform duration-300 {{ $isShared ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="menu-shared" class="grid transition-all duration-300 ease-in-out {{ $isShared ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 mt-0' }}">
                <div class="overflow-hidden pl-9 pr-3 space-y-1 pb-1">
                    <a href="{{ route('admin.gia-tri-vuot-troi.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.gia-tri-vuot-troi.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Giá trị vượt trội</a>
                    <a href="{{ route('admin.giai-thuong-thanh-tuu.index') }}" class="block px-3 py-2 rounded-lg text-[12px] font-medium transition-all {{ request()->routeIs('admin.giai-thuong-thanh-tuu.*') ? 'text-white bg-white/[0.08]' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.02]' }}">Giải thưởng & Thành tựu</a>
                </div>
            </div>
        </div>

        <!-- ================= HỆ THỐNG ================= -->
        @if (auth()->user()?->isSuperAdmin())
            <div class="pt-4 pb-1">
                <p class="px-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-500">Hệ thống</p>
            </div>
            @php $isUsers = request()->routeIs('admin.users.*'); @endphp
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-200 {{ $isUsers ? 'text-white bg-white/[0.08]' : 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isUsers ? 'text-[#A31D1D]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Danh sách Quản trị viên
            </a>
        @endif

    </nav>

    {{-- User info + Logout --}}
    <div class="px-3 py-4 border-t border-white/[0.06]">
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-white/[0.04] mb-2">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold flex-shrink-0 bg-gradient-to-br from-[#A31D1D] to-[#d94444]">
                {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-white text-[13px] font-medium truncate">{{ auth()->user()?->name }}</p>
                <p class="text-gray-500 text-[11px] truncate capitalize">{{ auth()->user()?->role }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.auth.logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] text-gray-500 hover:text-gray-300 hover:bg-white/[0.04] transition-all duration-200">
                <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Đăng xuất
            </button>
        </form>
    </div>
</aside>

@push('scripts')
    <script>
        // Xử lý logic Animation mượt mà bằng CSS Grid transition cho dropdown
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
