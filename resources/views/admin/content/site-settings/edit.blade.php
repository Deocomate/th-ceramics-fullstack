<x-admin.layouts.app title="Cài đặt website" breadcrumb="Hệ thống › Cài đặt website">
    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-8 pb-10 max-w-4xl">
        @csrf @method('PUT')

        <!-- E-commerce mode toggle -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Chế độ bán hàng</h2>
            </div>
            <div class="p-6">
                <input type="hidden" name="is_ecommerce_enabled" value="0">
                <label class="flex items-start gap-4 cursor-pointer">
                    <input type="checkbox" name="is_ecommerce_enabled" value="1"
                        class="mt-1 w-5 h-5 text-[#A31D1D] border-gray-300 rounded focus:ring-[#A31D1D]"
                        @checked(old('is_ecommerce_enabled', $settings->is_ecommerce_enabled ?? true))>
                    <div>
                        <span class="text-sm font-semibold text-gray-800">Chế độ bán hàng trực tuyến (Giỏ hàng &amp; Thanh toán)</span>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                            Khi tắt, website chuyển sang chế độ trưng bày B2B: ẩn giỏ hàng, chặn thanh toán, và nút sản phẩm mở form yêu cầu tư vấn / báo giá.
                        </p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Content protection toggles -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Bảo vệ nội dung</h2>
            </div>
            <div class="p-6 space-y-5">
                <input type="hidden" name="is_content_protection_enabled" value="0">
                <label class="flex items-start gap-4 cursor-pointer">
                    <input type="checkbox" name="is_content_protection_enabled" value="1"
                        class="mt-1 w-5 h-5 text-[#A31D1D] border-gray-300 rounded focus:ring-[#A31D1D]"
                        @checked(old('is_content_protection_enabled', $settings->is_content_protection_enabled ?? false))>
                    <div>
                        <span class="text-sm font-semibold text-gray-800">Chặn thao tác sao chép ảnh và video</span>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                            Khi bật, khách xem website không dùng được chuột phải trên toàn trang, cũng không kéo thả hay nhấn giữ được để lưu ảnh và video.
                        </p>
                    </div>
                </label>

                <input type="hidden" name="is_devtools_guard_enabled" value="0">
                <label class="flex items-start gap-4 cursor-pointer">
                    <input type="checkbox" name="is_devtools_guard_enabled" value="1"
                        class="mt-1 w-5 h-5 text-[#A31D1D] border-gray-300 rounded focus:ring-[#A31D1D]"
                        @checked(old('is_devtools_guard_enabled', $settings->is_devtools_guard_enabled ?? false))>
                    <div>
                        <span class="text-sm font-semibold text-gray-800">Phát hiện công cụ nhà phát triển</span>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                            Khi bật, website phát hiện khách mở công cụ nhà phát triển (DevTools) và chuyển sang trang thông báo. Chuột phải cũng bị chặn trên toàn trang để khách không mở được Inspect.
                        </p>
                    </div>
                </label>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                Lưu cài đặt
            </button>
        </div>
    </form>
</x-admin.layouts.app>
