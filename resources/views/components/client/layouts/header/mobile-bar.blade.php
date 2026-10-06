@props(['cartCount' => 0])

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
            @if ($isEcommerceEnabled)
            <x-client.commerce.shared.mini-cart :count="$cartCount ?? 0" icon-class="w-[18px] h-[18px]" />
            @endif
            @auth
                <a href="{{ ($isEcommerceEnabled ?? true) ? route('client.customer-service.show', 'trang-thai-don-hang') : route('client.auth.profile') }}"
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
