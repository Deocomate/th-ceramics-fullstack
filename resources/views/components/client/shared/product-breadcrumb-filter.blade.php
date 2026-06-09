@props(['currentLabel', 'showTypeFilter' => false])

<div class="w-[85%] max-w-[1320px] mx-auto pb-3 md:pb-6 relative z-10" data-aos="fade-up">
    <!-- Breadcrumb Part -->
    <nav class="flex items-center gap-1 font-archivo text-base font-semibold uppercase tracking-wider"
        aria-label="Breadcrumb">
        <a href="{{ route('client.home') }}"
            class="text-[#A5A5A5] hover:text-[#2E2F2A] transition-colors leading-[55px] font-semibold text-[16px] font-archivo">TRANG
            CHỦ</a>
        <span class="text-[#A5A5A5] leading-[55px] font-semibold text-[16px] font-archivo">/</span>
        <span class="text-[#2E2F2A] leading-[55px] font-semibold text-[16px] font-archivo">{{ $currentLabel }}</span>
    </nav>

    <!-- Divider Line -->
    <hr class="border-t border-[#2E2F2A]/10 w-full" />

    <!-- Filter Part -->
    <div class="">
        <button type="button"
            class="product-filter-trigger flex items-center gap-[13px] text-[#2E2F2A] hover:text-secondary transition-colors font-normal uppercase tracking-wider text-[16px] font-archivo leading-[55px]"
            aria-expanded="{{ request()->hasAny(['search', 'sort', 'type']) ? 'true' : 'false' }}"
            aria-controls="product-filter-panel">
            <div style="width: 36px; height: 36px; padding: 6px; background: rgba(217, 217, 217, 0); box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25); border-radius: 5px; outline: 1px #2E2F2A solid; outline-offset: -1px;"
                class="flex items-center justify-center">
                <div style="width: 24px; height: 24px; position: relative; overflow: hidden;"
                    class="flex items-center justify-center">
                    <svg style="width: 15.59px; height: 18.46px;" fill="none" stroke="#2E2F2A" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                </div>
            </div>
            BỘ LỌC SẢN PHẨM
        </button>

        <form id="product-filter-panel" method="GET" action="{{ url()->current() }}"
            class="{{ request()->hasAny(['search', 'sort', 'type']) ? 'grid' : 'hidden' }} mt-5 grid-cols-1 {{ $showTypeFilter ? 'md:grid-cols-[1fr_220px_220px_auto_auto]' : 'md:grid-cols-[1fr_220px_auto_auto]' }} gap-3 md:gap-4">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Tìm sản phẩm"
                class="w-full border border-black/20 bg-transparent px-4 py-3 text-sm text-primary outline-none focus:border-secondary font-archivo" />
            <select name="sort"
                class="w-full border border-black/20 bg-transparent px-4 py-3 text-sm text-primary outline-none focus:border-secondary font-archivo">
                <option value="">Sắp xếp mặc định</option>
                <option value="price_asc" @selected(request('sort') === 'price_asc')>Giá tăng dần</option>
                <option value="price_desc" @selected(request('sort') === 'price_desc')>Giá giảm dần</option>
                <option value="name_asc" @selected(request('sort') === 'name_asc')>Tên A-Z</option>
            </select>
            @if ($showTypeFilter)
                <select name="type"
                    class="w-full border border-black/20 bg-transparent px-4 py-3 text-sm text-primary outline-none focus:border-secondary font-archivo">
                    <option value="">Tất cả phân khu</option>
                    <option value="bat" @selected(request('type') === 'bat')>Gạch Bát</option>
                    <option value="that" @selected(request('type') === 'that')>Gạch Thất & Xây</option>
                    <option value="the" @selected(request('type') === 'the')>Gạch Thẻ</option>
                </select>
            @endif
            <button type="submit"
                class="px-6 py-3 bg-secondary text-white text-sm font-bold uppercase transition-opacity hover:opacity-90 font-archivo">
                Lọc
            </button>
            <a href="{{ url()->current() }}"
                class="px-6 py-3 border border-black/20 text-primary text-sm font-bold uppercase text-center flex items-center justify-center transition-colors hover:bg-black/5 font-archivo">
                Xóa
            </a>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".product-filter-trigger").forEach(function(button) {
                button.addEventListener("click", function() {
                    var panel = document.getElementById(button.getAttribute("aria-controls"));
                    if (!panel) return;
                    var isHidden = panel.classList.contains("hidden");
                    panel.classList.toggle("hidden", !isHidden);
                    panel.classList.toggle("grid", isHidden);
                    button.setAttribute("aria-expanded", isHidden ? "true" : "false");
                });
            });
        });
    </script>
@endpush
