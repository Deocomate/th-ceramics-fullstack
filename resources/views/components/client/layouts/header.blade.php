@props(['categories' => null, 'cartCount' => 0])

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

            <x-client.layouts.header.desktop-nav />
            <x-client.layouts.header.desktop-actions :cart-count="$cartCount ?? 0" />
        </nav>
    </div>

    <!-- Mobile Header -->
    <x-client.layouts.header.mobile-bar :cart-count="$cartCount ?? 0" />

    <!-- Mobile Navigation -->
    <x-client.layouts.header.mobile-menu :cart-count="$cartCount ?? 0" />
</header>

<x-client.layouts.header.scripts :categories="$categories" />
