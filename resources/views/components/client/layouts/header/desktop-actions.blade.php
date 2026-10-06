@props(['cartCount' => 0])

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
</div>
