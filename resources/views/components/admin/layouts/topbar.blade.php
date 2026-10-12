{{-- Module tabs, site status and the title of the current page --}}
@props(['nav', 'title', 'breadcrumb' => null])

@php
    $focus = 'focus:outline-none focus-visible:ring-2 focus-visible:ring-[#A31D1D]';
    $trail = $nav['trail'] !== [] ? implode(' › ', $nav['trail']) : $breadcrumb;
@endphp

<header class="sticky top-0 z-30 bg-white border-b border-gray-200">
    <div class="flex items-center justify-between gap-4 px-6 border-b border-gray-100">
        <nav aria-label="Nhóm chức năng" class="flex items-center gap-1 overflow-x-auto">
            @foreach ($nav['modules'] as $module)
                <a href="{{ $module['url'] }}" @if ($module['active']) aria-current="true" @endif
                    class="flex items-center gap-2 px-3 py-3.5 border-b-2 text-[13px] font-medium whitespace-nowrap transition-colors duration-200 {{ $focus }} {{ $module['active'] ? 'border-[#A31D1D] text-[#A31D1D]' : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300' }}">
                    <x-admin.layouts.nav-icon :name="$module['icon']" />
                    {{ $module['label'] }}
                    @if ($module['badge'] > 0)
                        <span class="bg-[#A31D1D] text-white text-[10px] rounded-full px-1.5 py-0.5">{{ $module['badge'] }}</span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('admin.settings.edit') }}" title="Đổi trong Cài đặt website"
                class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full border text-xs font-medium transition-colors duration-200 {{ $focus }} {{ $isEcommerceEnabled ? 'border-green-200 bg-green-50 text-green-800 hover:bg-green-100' : 'border-gray-200 bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $isEcommerceEnabled ? 'bg-green-600' : 'bg-gray-400' }}"></span>
                Bán hàng online: {{ $isEcommerceEnabled ? 'Bật' : 'Tắt' }}
            </a>
            <span class="text-xs text-gray-500">{{ now()->format('d/m/Y') }}</span>
        </div>
    </div>

    <div class="px-6 py-3">
        @if ($trail)
            <p class="text-xs text-gray-500">{{ $trail }}</p>
        @endif
        <h1 class="text-base font-semibold text-gray-800 mt-0.5">{{ $title }}</h1>
    </div>
</header>
