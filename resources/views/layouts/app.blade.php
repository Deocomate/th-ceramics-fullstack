<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Th Ceramics')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-900">
    <!-- Navigation (Optional) -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0">
                    <a href="{{ route('client.home') }}" class="text-2xl font-bold text-amber-600">
                        Th Ceramics
                    </a>
                </div>
                
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('client.home') }}" class="text-slate-700 hover:text-amber-600 transition">Trang chủ</a>
                    <a href="{{ route('client.about') }}" class="text-slate-700 hover:text-amber-600 transition">Về chúng tôi</a>
                    <a href="{{ route('client.contact') }}" class="text-slate-700 hover:text-amber-600 transition">Liên hệ</a>
                    
                    @auth
                        <div class="relative group">
                            <button class="text-slate-700 hover:text-amber-600 transition flex items-center gap-2">
                                {{ auth()->user()->name }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </button>
                            <div class="hidden group-hover:block absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg">
                                <form action="{{ route('client.auth.logout') }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-slate-700 hover:bg-slate-50">
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('client.auth.login') }}" class="text-slate-700 hover:text-amber-600 transition">Đăng nhập</a>
                        <a href="{{ route('client.auth.register') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg transition">Đăng ký</a>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <button class="md:hidden p-2 rounded-lg hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer (Optional) -->
    <footer class="bg-slate-900 text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">Th Ceramics</h3>
                    <p class="text-slate-400">Chuyên sản xuất gốm sứ trang trí, ngói và linh vật phong thủy chất lượng cao.</p>
                </div>
                <div>
                    <h3 class="font-bold mb-4">Sản Phẩm</h3>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="#" class="hover:text-white transition">Ngói Âm Dương</a></li>
                        <li><a href="#" class="hover:text-white transition">Gạch Hoa Thông Gió</a></li>
                        <li><a href="#" class="hover:text-white transition">Linh Vật Phong Thủy</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold mb-4">Dịch Vụ</h3>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="#" class="hover:text-white transition">Tư vấn thiết kế</a></li>
                        <li><a href="#" class="hover:text-white transition">Giao hàng toàn quốc</a></li>
                        <li><a href="#" class="hover:text-white transition">Bảo hành 5 năm</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold mb-4">Liên Hệ</h3>
                    <ul class="space-y-2 text-slate-400">
                        <li>Điện thoại: (84) 1234 567 890</li>
                        <li>Email: info@thceramics.com</li>
                        <li>Địa chỉ: Hà Nội, Việt Nam</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-700 pt-8">
                <p class="text-center text-slate-400">
                    &copy; {{ date('Y') }} Th Ceramics. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
