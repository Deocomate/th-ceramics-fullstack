<div class="flex flex-col gap-2 border-b border-gray-100 px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-5" data-admin-product-search>
    <label class="relative block w-full sm:max-w-md">
        <span class="sr-only">Tìm sản phẩm theo tên hoặc mã</span>
        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="m16 16 4 4"/></svg>
        <input type="search" autocomplete="off" data-admin-product-search-input placeholder="Tìm theo tên hoặc mã sản phẩm..." class="w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-9 pr-10 text-sm outline-none transition focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
        <button type="button" data-admin-product-search-clear aria-label="Xóa từ khóa tìm kiếm" title="Xóa tìm kiếm" class="absolute right-2 top-1/2 hidden h-7 w-7 -translate-y-1/2 items-center justify-center rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12M18 6 6 18"/></svg>
        </button>
    </label>
    <p class="text-xs text-gray-500" data-admin-product-search-count role="status" aria-live="polite"></p>
</div>
