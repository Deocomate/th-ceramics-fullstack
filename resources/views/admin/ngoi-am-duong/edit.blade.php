<x-admin.layout.app title="Ngói Âm Dương" breadcrumb="Admin › Sản Phẩm › Ngói Âm Dương">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cấu hình chung</h2>
        </div>
        <form method="POST" action="{{ route('admin.ngoi-am-duong.update') }}" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Cột Ảnh chính -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh nền</label>
                    <div class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                        <img id="preview-main" src="{{ asset('storage/' . $ngoiAmDuong->thumbnail_main) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-cover" alt="Ảnh chính">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                        </div>
                        <input type="file" name="thumbnail_main" accept="image/*" onchange="previewImage(event, 'preview-main')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    @error('thumbnail_main') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <!-- Cột Ảnh phụ 1 -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh phụ 1</label>
                    <div class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                        <img id="preview-thumb1" src="{{ asset('storage/' . $ngoiAmDuong->thumbnail1) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-cover" alt="Ảnh phụ 1">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                        </div>
                        <input type="file" name="thumbnail1" accept="image/*" onchange="previewImage(event, 'preview-thumb1')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    @error('thumbnail1') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <!-- Cột Ảnh phụ 2 -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh phụ 2</label>
                    <div class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                        <img id="preview-thumb2" src="{{ asset('storage/' . $ngoiAmDuong->thumbnail2) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-cover" alt="Ảnh phụ 2">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                        </div>
                        <input type="file" name="thumbnail2" accept="image/*" onchange="previewImage(event, 'preview-thumb2')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    @error('thumbnail2') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="pt-2">
                <label for="video" class="block text-sm font-semibold text-gray-700 mb-2">Đường dẫn Video YouTube (Tùy chọn)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </span>
                    <input type="url" id="video" name="video" value="{{ old('video', $ngoiAmDuong->video) }}" placeholder="https://youtube.com/watch?v=..." class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                </div>
                @error('video') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
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
                document.getElementById(targetId).src = objectUrl;
                document.getElementById(targetId).onload = function() {
                    URL.revokeObjectURL(objectUrl);
                }
            }
        }
    </script>
    @endpush
</x-admin.layout.app>