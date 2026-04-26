<x-admin.layout.app title="Ngói Hài Văn Miếu" breadcrumb="Admin › Sản Phẩm › Ngói Hài Văn Miếu">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cấu hình Ngói Hài Văn Miếu</h2>
        </div>
        
        <form method="POST" action="{{ route('admin.ngoi-hai-van-mieu.update') }}" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf @method('PUT')
            
            <!-- Ảnh chính -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh nền</label>
                <div class="aspect-[21/9] max-w-4xl w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                    <img id="preview-main" src="{{ asset('storage/' . $ngoiHaiVanMieu->thumbnail_main) }}" onerror="this.src='https://placehold.co/1200x500?text=Chua+co+anh'" class="w-full h-full object-cover" alt="Ảnh chính">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="text-white text-sm font-medium px-4 py-2 bg-black/50 rounded-lg">Click để tải ảnh mới lên</span>
                    </div>
                    <input type="file" name="thumbnail_main" accept="image/*" onchange="previewImage(event, 'preview-main')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                </div>
                @error('thumbnail_main') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            
            <hr class="border-gray-100">

            <!-- Các Block Nội dung -->
            <div>
                <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide">Các dự án / Hình ảnh tiêu biểu</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Block 1 -->
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tiêu đề dự án 1 <span class="text-red-500">*</span></label>
                            <input type="text" name="title1" value="{{ old('title1', $ngoiHaiVanMieu->title1) }}" required class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                            @error('title1') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Hình ảnh dự án 1</label>
                            <div class="aspect-video w-full rounded-lg border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group">
                                <img id="preview-1" src="{{ asset('storage/' . $ngoiHaiVanMieu->thumbnail1) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium">Thay ảnh</span>
                                </div>
                                <input type="file" name="thumbnail1" accept="image/*" onchange="previewImage(event, 'preview-1')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                            @error('thumbnail1') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Block 2 -->
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tiêu đề dự án 2 <span class="text-red-500">*</span></label>
                            <input type="text" name="title2" value="{{ old('title2', $ngoiHaiVanMieu->title2) }}" required class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                            @error('title2') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Hình ảnh dự án 2</label>
                            <div class="aspect-video w-full rounded-lg border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group">
                                <img id="preview-2" src="{{ asset('storage/' . $ngoiHaiVanMieu->thumbnail2) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium">Thay ảnh</span>
                                </div>
                                <input type="file" name="thumbnail2" accept="image/*" onchange="previewImage(event, 'preview-2')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                            @error('thumbnail2') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Block 3 -->
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tiêu đề dự án 3 <span class="text-red-500">*</span></label>
                            <input type="text" name="title3" value="{{ old('title3', $ngoiHaiVanMieu->title3) }}" required class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                            @error('title3') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Hình ảnh dự án 3</label>
                            <div class="aspect-video w-full rounded-lg border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group">
                                <img id="preview-3" src="{{ asset('storage/' . $ngoiHaiVanMieu->thumbnail3) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium">Thay ảnh</span>
                                </div>
                                <input type="file" name="thumbnail3" accept="image/*" onchange="previewImage(event, 'preview-3')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                            @error('thumbnail3') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <div>
                <label for="video" class="block text-sm font-semibold text-gray-700 mb-2">Đường dẫn Video YouTube (Tùy chọn)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </span>
                    <input type="url" id="video" name="video" value="{{ old('video', $ngoiHaiVanMieu->video) }}" placeholder="https://youtube.com/watch?v=..." class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-lg outline-none transition-colors border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                </div>
                @error('video') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            
            <div class="pt-4 flex justify-end border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                    Lưu cấu hình
                </button>
            </div>
        </form>
    </div>

    {{-- Script xử lý Image Preview --}}
    @push('scripts')
    <script>
        function previewImage(event, targetId) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(targetId).src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
    @endpush
</x-admin.layout.app>