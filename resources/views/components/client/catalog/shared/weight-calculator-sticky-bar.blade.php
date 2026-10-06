{{--
    Sticky CTA to the weight calculator. Visible by default so iOS Safari still
    shows it if JS is delayed. Centering uses inset + mx-auto (no transform),
    because WebKit can drop `position: fixed` when the same node has `transform`.
--}}
<div
    class="pointer-events-none fixed inset-x-0 bottom-20 z-[90] mx-auto flex w-[90%] max-w-[460px] justify-center sm:bottom-24 md:bottom-8"
    data-weight-calculator-bar
    data-target="#cach-tinh-khoi-luong"
>
    <div
        class="pointer-events-auto flex w-full items-center gap-3 rounded-full border border-primary/15 bg-white px-3 py-2 shadow-[0_12px_32px_rgba(15,23,42,0.16)]">
        <a href="#cach-tinh-khoi-luong"
            class="flex min-w-0 flex-1 items-center gap-3 no-underline"
            data-weight-bar-scroll>
            <span
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary text-white"
                aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.5 12h15m0 0-5.25-5.25M19.5 12l-5.25 5.25" />
                </svg>
            </span>
            <span class="min-w-0">
                <span class="block text-sm font-semibold leading-tight text-gray-900">Tính khối lượng</span>
                <span class="block truncate text-xs text-gray-500">Ước tính số lượng sản phẩm cần dùng</span>
            </span>
        </a>
        <button type="button"
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
            data-weight-bar-dismiss
            aria-label="Ẩn thanh tính khối lượng">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" class="h-4 w-4" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
