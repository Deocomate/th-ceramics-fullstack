<x-admin.layouts.app title="Cấu hình trang Dự án" breadcrumb="Admin › Dự án › Cấu hình trang">
    @php
        $mediaUrl = function (?string $path, string $fallback = 'https://placehold.co/600x400?text=Chua+co+anh') {
            if (empty($path)) {
                return $fallback;
            }

            if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            if (\Illuminate\Support\Str::startsWith($path, 'assets/')) {
                return asset($path);
            }

            return asset('storage/' . $path);
        };
    @endphp

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Banner promo cuối trang Dự án</h2>
        </div>

        <form method="POST" action="{{ route('admin.trang-du-an.update') }}" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-3">
                <input type="hidden" name="promo_enabled" value="0">
                <input type="checkbox" id="promo_enabled" name="promo_enabled" value="1"
                    {{ old('promo_enabled', $trangDuAn->promo_enabled) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-[#A31D1D] focus:ring-[#A31D1D]">
                <label for="promo_enabled" class="text-sm font-semibold text-gray-700">Hiển thị banner promo</label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label for="promo_title" class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề (mỗi dòng một hàng)</label>
                        <textarea id="promo_title" name="promo_title" rows="4" required
                            class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">{{ old('promo_title', $trangDuAn->promo_title) }}</textarea>
                        @error('promo_title') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="promo_cta_label" class="block text-sm font-semibold text-gray-700 mb-2">Nhãn nút CTA</label>
                        <input type="text" id="promo_cta_label" name="promo_cta_label" value="{{ old('promo_cta_label', $trangDuAn->promo_cta_label) }}" required
                            class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                        @error('promo_cta_label') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="promo_cta_url" class="block text-sm font-semibold text-gray-700 mb-2">Liên kết CTA</label>
                        <input type="text" id="promo_cta_url" name="promo_cta_url" value="{{ old('promo_cta_url', $trangDuAn->promo_cta_url) }}"
                            placeholder="/san-pham/gach-hoa-thong-gio hoặc https://..."
                            class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                        @error('promo_cta_url') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh promo</label>
                    <div class="aspect-[67/45] w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                        <img id="preview-promo" src="{{ $mediaUrl($trangDuAn->promo_image) }}" class="w-full h-full object-cover" alt="Ảnh promo">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                        </div>
                        <input type="file" name="promo_image" accept="image/*" onchange="previewSingle(this, 'preview-promo')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    @error('promo_image') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white rounded-lg" style="background:#A31D1D;">Lưu cấu hình</button>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
