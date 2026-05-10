<x-admin.layout.app title="Thêm mã giảm giá" breadcrumb="Admin › Mã giảm giá › Thêm mới">

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Thông tin mã giảm giá mới</h2>
        </div>
        <form method="POST" action="{{ route('admin.coupons.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- CỘT 1: THÔNG TIN CƠ BẢN --}}
                <div class="space-y-5">

                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
                        @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Code --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mã giảm giá <span class="text-red-500">*</span></label>
                        <input type="text" name="code" value="{{ old('code') }}" required
                               oninput="this.value = this.value.toUpperCase()"
                               placeholder="VD: SUMMER2026"
                               class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none font-mono uppercase">
                        @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Discount Type --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Loại giảm giá <span class="text-red-500">*</span></label>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="discount_type" value="percent"
                                       {{ old('discount_type') === 'percent' ? 'checked' : '' }}
                                       class="w-4 h-4 text-[#A31D1D] focus:ring-[#A31D1D]">
                                <span class="text-sm text-gray-700">Phần trăm (%)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="discount_type" value="fixed"
                                       {{ old('discount_type') === 'fixed' ? 'checked' : '' }}
                                       class="w-4 h-4 text-[#A31D1D] focus:ring-[#A31D1D]">
                                <span class="text-sm text-gray-700">Số tiền cố định (VNĐ)</span>
                            </label>
                        </div>
                        @error('discount_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Discount Value --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Giá trị giảm <span class="text-red-500">*</span></label>
                        <input type="number" name="discount_value" value="{{ old('discount_value') }}" required
                               step="0.01" min="0.01"
                               class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
                        @error('discount_value') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Max Discount Amount (only for percent) --}}
                    <div id="max-discount-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Giảm tối đa (VNĐ)</label>
                        <input type="number" name="max_discount_amount" value="{{ old('max_discount_amount') }}"
                               min="0"
                               placeholder="VD: 50000"
                               class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
                        <p class="mt-1 text-xs text-gray-400">Chỉ áp dụng cho loại giảm theo phần trăm.</p>
                        @error('max_discount_amount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- CỘT 2: CÀI ĐẶT BỔ SUNG --}}
                <div class="space-y-5">

                    {{-- Min Order Value --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Giá trị đơn tối thiểu (VNĐ)</label>
                        <input type="number" name="min_order_value" value="{{ old('min_order_value') }}"
                               min="0"
                               placeholder="VD: 1000000"
                               class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
                        @error('min_order_value') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Applicable Product Types --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Loại sản phẩm áp dụng</label>
                        <p class="text-xs text-gray-400 mb-3">Nếu không chọn loại nào, mã sẽ áp dụng cho tất cả sản phẩm.</p>
                        <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto p-3 border border-gray-200 rounded-lg bg-gray-50">
                            @foreach($productTypes as $type => $label)
                                <label class="flex items-center gap-2 cursor-pointer text-sm">
                                    <input type="checkbox" name="applicable_product_types[]" value="{{ $type }}"
                                           {{ in_array($type, old('applicable_product_types', [])) ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#A31D1D] focus:ring-[#A31D1D] rounded">
                                    <span class="text-gray-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('applicable_product_types') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        @error('applicable_product_types.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Usage Limit --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Giới hạn lượt dùng</label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit') }}"
                               min="1"
                               placeholder="Để trống nếu không giới hạn"
                               class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
                        @error('usage_limit') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Date Range --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ngày bắt đầu <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" required
                                   class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
                            @error('start_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ngày kết thúc</label>
                            <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                                   class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
                            @error('end_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Banner Image --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ảnh banner</label>
                        <input type="file" name="banner_image" accept="image/*"
                               class="w-full text-sm border border-gray-300 rounded-lg p-1.5 cursor-pointer bg-white">
                        <div class="mt-3">
                            <img id="image-preview" src="#" alt="Xem trước ảnh"
                                 class="hidden max-h-40 rounded-lg border border-gray-200 object-contain">
                        </div>
                        @error('banner_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Toggles --}}
                    <div class="space-y-3 pt-2 border-t border-gray-100">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="show_banner" value="0">
                            <input type="checkbox" name="show_banner" value="1"
                                   {{ old('show_banner') ? 'checked' : '' }}
                                   class="w-4 h-4 text-[#A31D1D] focus:ring-[#A31D1D] rounded">
                            <span class="text-sm text-gray-700">Hiển thị banner</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', '1') === '1' ? 'checked' : '' }}
                                   class="w-4 h-4 text-[#A31D1D] focus:ring-[#A31D1D] rounded">
                            <span class="text-sm text-gray-700">Kích hoạt mã giảm giá</span>
                        </label>
                    </div>

                </div>

            </div>

            {{-- Actions --}}
            <div class="pt-6 mt-8 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.coupons.index') }}"
                   class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Hủy
                </a>
                <button type="submit"
                        class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors"
                        style="background:#A31D1D;">
                    Lưu mã giảm giá
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const typeRadios = document.querySelectorAll('input[name="discount_type"]');
        const maxDiscountGroup = document.getElementById('max-discount-group');
        const discountValueInput = document.querySelector('input[name="discount_value"]');

        function toggleMaxDiscount() {
            const selected = document.querySelector('input[name="discount_type"]:checked');
            maxDiscountGroup.style.display = (selected && selected.value === 'percent') ? 'block' : 'none';
            if (selected && selected.value === 'percent') {
                discountValueInput.setAttribute('max', '99.9');
            } else {
                discountValueInput.removeAttribute('max');
            }
        }

        typeRadios.forEach(r => r.addEventListener('change', toggleMaxDiscount));
        toggleMaxDiscount();

        // Image preview
        const imgInput = document.querySelector('input[name="banner_image"]');
        const preview = document.getElementById('image-preview');
        if (imgInput) {
            imgInput.addEventListener('change', () => {
                const file = imgInput.files[0];
                if (file) {
                    preview.src = URL.createObjectURL(file);
                    preview.classList.remove('hidden');
                }
            });
        }
    });
    </script>
    @endpush

</x-admin.layout.app>
