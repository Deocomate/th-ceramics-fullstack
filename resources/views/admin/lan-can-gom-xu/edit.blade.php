<x-admin.layout.app title="Lan Can Gốm Sứ" breadcrumb="Admin › Sản Phẩm › Lan Can Gốm Sứ">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cấu hình Lan Can Gốm Sứ</h2>
        </div>
        <form method="POST" action="{{ route('admin.lan-can-gom-xu.update') }}" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh nền</label>
                    <div class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                        <img id="preview-main" src="{{ asset('storage/' . $lanCanGomXu->thumbnail_main) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-contain" alt="Ảnh chính">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                        </div>
                        <input type="file" name="thumbnail_main" accept="image/*" onchange="previewImage(event, 'preview-main')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    @error('thumbnail_main') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="video" class="block text-sm font-semibold text-gray-700 mb-2">Đường dẫn Video YouTube (Tùy chọn)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </span>
                        <input type="url" id="video" name="video" value="{{ old('video', $lanCanGomXu->video) }}" placeholder="https://youtube.com/watch?v=..." class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                    </div>
                    @error('video') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-gray-200">

            <div>
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-6">Danh Mục Sản Phẩm 1 (Layout: Lưới trái - Ảnh phải)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh nổi bật</label>
                        <div class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                            @php $s1img = $lanCanGomXu->section_1_image; @endphp
                            <img id="preview-section-1" src="{{ $s1img ? asset('storage/' . $s1img) : '' }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-contain {{ $s1img ? '' : 'hidden' }}" alt="Section 1 Image">
                            <span id="preview-section-1-placeholder" class="text-gray-400 text-sm {{ $s1img ? 'hidden' : '' }}">Chưa có ảnh</span>
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                            </div>
                            <input type="file" name="section_1_image" accept="image/*" onchange="previewImage(event, 'preview-section-1'); handlePlaceholder(event, 'preview-section-1', 'preview-section-1-placeholder')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('section_1_image') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="section_1_title" class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề Brush</label>
                        <input type="text" id="section_1_title" name="section_1_title" value="{{ old('section_1_title', $lanCanGomXu->section_1_title) }}" placeholder="VD: LAN CAN GIỌT LỆ" class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                        @error('section_1_title') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <div>
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-6">Danh Mục Sản Phẩm 2 (Layout: Ảnh trái - Lưới phải)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh nổi bật</label>
                        <div class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                            @php $s2img = $lanCanGomXu->section_2_image; @endphp
                            <img id="preview-section-2" src="{{ $s2img ? asset('storage/' . $s2img) : '' }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-contain {{ $s2img ? '' : 'hidden' }}" alt="Section 2 Image">
                            <span id="preview-section-2-placeholder" class="text-gray-400 text-sm {{ $s2img ? 'hidden' : '' }}">Chưa có ảnh</span>
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                            </div>
                            <input type="file" name="section_2_image" accept="image/*" onchange="previewImage(event, 'preview-section-2'); handlePlaceholder(event, 'preview-section-2', 'preview-section-2-placeholder')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('section_2_image') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="section_2_title" class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề Brush</label>
                        <input type="text" id="section_2_title" name="section_2_title" value="{{ old('section_2_title', $lanCanGomXu->section_2_title) }}" placeholder="VD: LAN CAN BẦU" class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                        @error('section_2_title') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                    Lưu cấu hình
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function previewImage(event, targetId) {
            const file = event.target.files[0];
            if (file) {
                const objectUrl = URL.createObjectURL(file);
                const el = document.getElementById(targetId);
                el.src = objectUrl;
                el.classList.remove('hidden');
                el.onload = function() {
                    URL.revokeObjectURL(objectUrl);
                }
            }
        }

        function handlePlaceholder(event, imgId, placeholderId) {
            const file = event.target.files[0];
            const placeholder = document.getElementById(placeholderId);
            if (file && placeholder) {
                placeholder.classList.add('hidden');
            }
        }
    </script>
    @endpush
</x-admin.layout.app>
