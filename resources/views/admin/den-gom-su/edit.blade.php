<x-admin.layout.app title="Đèn Gốm Sứ" breadcrumb="Admin › Sản Phẩm › Đèn Gốm Sứ">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cấu Hình Hình Ảnh Đèn Gốm Sứ</h2>
        </div>

        <form method="POST" action="{{ route('admin.den-gom-su.update') }}" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf @method('PUT')

            <!-- HÀNG 1: Ảnh Chính & Video (1 hàng) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh đại diện chính</label>
                        <div class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors">
                            <img id="preview-main" src="{{ asset('storage/' . $denGomSu->thumbnail_main) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-cover">
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
                            <input type="url" id="video" name="video" value="{{ old('video', $denGomSu->video) }}" placeholder="https://youtube.com/watch?v=..." class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Dùng để thay thế nếu thiết kế có chèn video thay cho ảnh.</p>
                        @error('video') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- HÀNG 2: Ảnh 1 và Ảnh 2 cùng hàng -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Block 1 (Không có tiêu đề) -->
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow flex flex-col justify-end">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Hình ảnh 1</label>
                        <div class="aspect-video w-full rounded-lg border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group hover:bg-gray-50 transition-colors">
                            <img id="preview-1" src="{{ asset('storage/' . $denGomSu->image1) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                            </div>
                            <input type="file" name="image1" accept="image/*" onchange="previewImage(event, 'preview-1')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('image1') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Block 2 (Có tiêu đề) -->
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tiêu đề cho Ảnh 2</label>
                        <input type="text" name="title2" value="{{ old('title2', $denGomSu->title2) }}" placeholder="Tối đa 30 ký tự..." class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                        @error('title2') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Hình ảnh 2</label>
                        <div class="aspect-video w-full rounded-lg border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group hover:bg-gray-50 transition-colors">
                            <img id="preview-2" src="{{ asset('storage/' . $denGomSu->image2) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                            </div>
                            <input type="file" name="image2" accept="image/*" onchange="previewImage(event, 'preview-2')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('image2') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- HÀNG 3: Ảnh 3 và Ảnh 4 cùng hàng -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Block 3 (Có tiêu đề) -->
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tiêu đề cho Ảnh 3</label>
                        <input type="text" name="title3" value="{{ old('title3', $denGomSu->title3) }}" placeholder="Tối đa 30 ký tự..." class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                        @error('title3') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Hình ảnh 3</label>
                        <div class="aspect-video w-full rounded-lg border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group hover:bg-gray-50 transition-colors">
                            <img id="preview-3" src="{{ asset('storage/' . $denGomSu->image3) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                            </div>
                            <input type="file" name="image3" accept="image/*" onchange="previewImage(event, 'preview-3')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('image3') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Block 4 (Không có tiêu đề) -->
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow flex flex-col justify-end">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Hình ảnh 4</label>
                        <div class="aspect-video w-full rounded-lg border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group hover:bg-gray-50 transition-colors">
                            <img id="preview-4" src="{{ asset('storage/' . $denGomSu->image4) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                            </div>
                            <input type="file" name="image4" accept="image/*" onchange="previewImage(event, 'preview-4')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('image4') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- THÊM ẢNH THƯ VIỆN -->
            <div class="flex flex-col h-full border border-gray-200 rounded-xl p-6 bg-gray-50/50">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Thư viện ảnh chung (Chọn nhiều ảnh cùng lúc)</label>
                <p class="text-xs text-gray-500 mb-4">Các ảnh này sẽ được thêm vào thư viện sản phẩm khi bạn lưu.</p>
                
                <div class="relative mb-4">
                    <input type="file" id="multipleImagesInput" name="new_images[]" multiple accept="image/*"
                        class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white"
                        onchange="handleMultipleFiles(event)">
                </div>
                @error('new_images.*') <p class="mb-4 text-xs text-red-600">{{ $message }}</p> @enderror
                
                <div class="h-[300px] bg-white border border-gray-200 rounded-xl p-4 overflow-y-auto shadow-inner flex flex-col">
                    <div id="multiple-preview-container" class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-3">
                        <div id="empty-preview-state" class="col-span-full h-full min-h-[220px] flex flex-col items-center justify-center text-center text-gray-400 text-xs font-medium gap-2">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Chưa có ảnh nào được chọn tải lên</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end border-t border-gray-100">
                <button type="submit"
                    class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors"
                    style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'"
                    onmouseout="this.style.background='#A31D1D'">
                    Lưu cấu hình & Upload ảnh
                </button>
            </div>
        </form>
    </div>

    {{-- DANH SÁCH ẢNH THƯ VIỆN ĐÃ LƯU --}}
    @if (count($denGomSu->anh) > 0)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Thư viện ảnh đã lưu</h2>
                <span class="text-xs text-gray-500 font-medium">Tổng số: {{ count($denGomSu->anh) }} ảnh</span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                    @foreach ($denGomSu->anh as $anh)
                        <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                            <img src="{{ asset('storage/' . $anh->image) }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                <form action="{{ route('admin.den-gom-su.anh.destroy', $anh) }}" method="POST" onsubmit="return confirm('Xóa ảnh thư viện này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 shadow-sm transition-colors">
                                        Xóa ảnh
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            // Preview các file ảnh tĩnh lẻ
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

            // Xử lý File Multiple Upload cho Thư viện ảnh chung
            let selectedFiles =[];
            const multipleImagesInput = document.getElementById('multipleImagesInput');
            const previewContainer = document.getElementById('multiple-preview-container');
            const emptyState = document.getElementById('empty-preview-state');

            function handleMultipleFiles(event) {
                const files = Array.from(event.target.files);
                if (files.length > 0) {
                    selectedFiles = selectedFiles.concat(files);
                    updateFileInput();
                    renderPreviews();
                }
            }

            function renderPreviews() {
                const existingPreviews = previewContainer.querySelectorAll('.image-preview-item');
                existingPreviews.forEach(item => item.remove());

                if (selectedFiles.length === 0) {
                    emptyState.style.display = 'flex';
                } else {
                    emptyState.style.display = 'none';
                    selectedFiles.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'image-preview-item relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100';
                            
                            div.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                    <button type="button" onclick="removeFile(${index})" class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors shadow-sm" title="Xóa ảnh này">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            `;
                            previewContainer.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            }

            function removeFile(index) {
                selectedFiles.splice(index, 1);
                updateFileInput();
                renderPreviews();
            }

            function updateFileInput() {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                multipleImagesInput.files = dataTransfer.files;
            }
        </script>
    @endpush
</x-admin.layout.app>