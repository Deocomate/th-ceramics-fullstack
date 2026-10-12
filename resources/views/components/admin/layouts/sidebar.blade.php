{{-- Lists the items of the module chosen on the topbar; the tree lives in config/admin_navigation.php --}}
@props(['nav'])

@php
    $module = $nav['current'];
    $focus = 'focus:outline-none focus-visible:ring-2 focus-visible:ring-[#d94444]';
    $itemBase = "flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors duration-200 {$focus}";
    $childBase = "block px-3 py-2 rounded-lg text-[12px] font-medium transition-colors duration-200 {$focus}";
    $on = 'text-white bg-white/[0.08]';
    $off = 'text-gray-400 hover:text-gray-200 hover:bg-white/[0.04]';
@endphp

<aside class="fixed inset-y-0 left-0 z-40 w-64 flex flex-col bg-[#0f0f13]">
    {{-- Brand --}}
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-5 py-5 border-b border-white/[0.06] {{ $focus }}">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-gradient-to-br from-[#A31D1D] to-[#d94444] shadow-lg shadow-red-900/30">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
        </div>
        <div class="min-w-0">
            <span class="text-white font-bold text-[15px] tracking-tight block leading-tight">TH Ceramics</span>
            <span class="text-gray-400 text-[10px] font-medium uppercase tracking-widest">Admin Panel</span>
        </div>
    </a>

    {{-- Navigation of the current module --}}
    <nav aria-label="{{ $module['label'] }}" class="flex-1 overflow-y-auto px-3 py-4 space-y-1 custom-scrollbar">
        <p class="px-3 pb-2 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">{{ $module['label'] }}</p>

        @foreach ($module['items'] as $item)
            @if ($item['type'] === 'heading')
                <p class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-500">{{ $item['label'] }}</p>
            @elseif ($item['type'] === 'group')
                @php $groupId = 'nav-group-'.$loop->index; @endphp
                <div>
                    <button type="button" class="w-full justify-between {{ $itemBase }} {{ $off }}" aria-expanded="{{ $item['open'] ? 'true' : 'false' }}" aria-controls="{{ $groupId }}" onclick="toggleSubmenu('{{ $groupId }}', this)">
                        <span class="flex items-center gap-3 text-left">
                            @isset($item['icon'])
                                <x-admin.layouts.nav-icon :name="$item['icon']" />
                            @else
                                <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $item['dot'] ?? 'bg-gray-500' }}"></span>
                            @endisset
                            {{ $item['label'] }}
                        </span>
                        <svg class="chevron-icon w-4 h-4 flex-shrink-0 transition-transform duration-300 {{ $item['open'] ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div id="{{ $groupId }}" class="grid transition-all duration-300 ease-in-out {{ $item['open'] ? 'grid-rows-[1fr] opacity-100 mt-1' : 'grid-rows-[0fr] opacity-0 invisible mt-0' }}">
                        <div class="overflow-hidden {{ isset($item['icon']) ? 'pl-9' : 'pl-7' }} pr-3 space-y-1 pb-1">
                            @foreach ($item['children'] as $child)
                                @if ($child['type'] === 'heading')
                                    <p class="px-3 pt-2 pb-1 text-[10px] font-bold text-gray-500 uppercase">{{ $child['label'] }}</p>
                                @else
                                    <a href="{{ $child['url'] }}" @if ($child['active']) aria-current="page" @endif class="{{ $childBase }} {{ $child['active'] ? $on : $off }}">{{ $child['label'] }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ $item['url'] }}" @if ($item['active']) aria-current="page" @endif class="{{ $itemBase }} {{ $item['active'] ? $on : $off }}">
                    <x-admin.layouts.nav-icon :name="$item['icon']" class="{{ $item['active'] ? 'text-[#A31D1D]' : '' }}" />
                    {{ $item['label'] }}
                    @if ($item['badge'] > 0)
                        <span class="ml-auto bg-[#A31D1D] text-white text-[10px] rounded-full px-1.5 py-0.5">{{ $item['badge'] }}</span>
                    @elseif ($item['hint'])
                        <span class="ml-auto text-[10px] text-gray-400 italic">({{ $item['hint'] }})</span>
                    @endif
                </a>
            @endif
        @endforeach

        {{-- The dashboard has no items of its own, so it lists the way into every other module --}}
        @if ($module['key'] === 'dashboard')
            <p class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-500">Nhóm chức năng</p>
            @foreach ($nav['modules'] as $other)
                @continue($other['key'] === 'dashboard')
                <a href="{{ $other['url'] }}" class="{{ $itemBase }} {{ $off }}">
                    <x-admin.layouts.nav-icon :name="$other['icon']" />
                    {{ $other['label'] }}
                    @if ($other['badge'] > 0)
                        <span class="ml-auto bg-[#A31D1D] text-white text-[10px] rounded-full px-1.5 py-0.5">{{ $other['badge'] }}</span>
                    @endif
                </a>
            @endforeach
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
                <p class="text-gray-400 text-[11px] truncate capitalize">{{ auth()->user()?->role }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.auth.logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] text-gray-400 hover:text-gray-200 hover:bg-white/[0.04] transition-colors duration-200 {{ $focus }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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
            const isOpen = wrapper.classList.contains('grid-rows-[1fr]');

            wrapper.classList.toggle('grid-rows-[1fr]', !isOpen);
            wrapper.classList.toggle('opacity-100', !isOpen);
            wrapper.classList.toggle('mt-1', !isOpen);
            wrapper.classList.toggle('grid-rows-[0fr]', isOpen);
            wrapper.classList.toggle('opacity-0', isOpen);
            wrapper.classList.toggle('invisible', isOpen);
            wrapper.classList.toggle('mt-0', isOpen);
            icon.classList.toggle('rotate-180', !isOpen);
            btn.setAttribute('aria-expanded', String(!isOpen));
        }
    </script>
@endpush
