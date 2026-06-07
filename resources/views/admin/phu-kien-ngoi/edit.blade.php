<x-admin.layouts.app title="Phụ Kiện Ngói" breadcrumb="Admin › Sản Phẩm › Phụ Kiện Ngói">
    @php
        $assetUrl = fn (?string $path, ?string $fallback = null) => \App\Support\AssetPath::url($path, $fallback);
    @endphp

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cấu hình Trang Phụ Kiện Ngói</h2>
        </div>

        <form method="POST" action="{{ route('admin.phu-kien-ngoi.update') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
                <div class="space-y-6 bg-gray-50/60 rounded-xl border border-gray-100 p-5">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">1. Banner chính</h3>
                        <p class="text-xs text-gray-500 mt-1">Ảnh và hai dòng chữ phụ hiển thị trên hero Phụ Kiện Ngói.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh banner toàn trang</label>
                        <div class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group">
                            <img id="preview-main" src="{{ $assetUrl($phuKienNgoi->thumbnail_main, 'assets/images/pk-banner.png') }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-contain" alt="Ảnh banner">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                            </div>
                            <input type="file" name="thumbnail_main" accept="image/*" onchange="previewImage(event, 'preview-main')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('thumbnail_main') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dòng chữ phụ 1</label>
                        <input type="text" name="banner_text_1" value="{{ old('banner_text_1', $phuKienNgoi->banner_text_1) }}" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all" placeholder="Nâng niu nét chạm trổ, sắt son cùng thời gian">
                        @error('banner_text_1') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dòng chữ phụ 2</label>
                        <input type="text" name="banner_text_2" value="{{ old('banner_text_2', $phuKienNgoi->banner_text_2) }}" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all" placeholder="Phụ kiện Thanh Hải: Điểm nhấn tâm linh...">
                        @error('banner_text_2') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-6 bg-gray-50/60 rounded-xl border border-gray-100 p-5">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">2. Khối sản phẩm nổi bật</h3>
                        <p class="text-xs text-gray-500 mt-1">Cấu hình ảnh lớn và tiêu đề brush cho hai section sản phẩm đầu trang.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tên danh mục 1</label>
                                <input type="text" name="sec1_title" value="{{ old('sec1_title', $phuKienNgoi->sec1_title) }}" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all" placeholder="NGÓI BỜ NÓC">
                                @error('sec1_title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh nổi bật 1</label>
                                <div class="aspect-square w-full rounded-xl border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group">
                                    <img id="preview-sec1" src="{{ $assetUrl($phuKienNgoi->sec1_image, 'assets/images/bo-noc.png') }}" onerror="this.src='https://placehold.co/400x400?text=Chua+co+anh'" class="w-full h-full object-contain" alt="Ảnh section 1">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                                    </div>
                                    <input type="file" name="sec1_image" accept="image/*" onchange="previewImage(event, 'preview-sec1')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                </div>
                                @error('sec1_image') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tên danh mục 2</label>
                                <input type="text" name="sec2_title" value="{{ old('sec2_title', $phuKienNgoi->sec2_title) }}" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all" placeholder="BỜ NÓC CHỮ VẠN">
                                @error('sec2_title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh nổi bật 2</label>
                                <div class="aspect-square w-full rounded-xl border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group">
                                    <img id="preview-sec2" src="{{ $assetUrl($phuKienNgoi->sec2_image, 'assets/images/dao-kim.png') }}" onerror="this.src='https://placehold.co/400x400?text=Chua+co+anh'" class="w-full h-full object-contain" alt="Ảnh section 2">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                                    </div>
                                    <input type="file" name="sec2_image" accept="image/*" onchange="previewImage(event, 'preview-sec2')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                </div>
                                @error('sec2_image') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col h-full border border-gray-200 rounded-xl p-4 bg-gray-50/50">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Thư viện ảnh Dấu Ấn Công Trình</label>
                <p class="text-xs text-gray-500 mb-4">Các ảnh này sẽ được thêm vào gallery khi bạn lưu và được dùng cho section công trình ngoài client.</p>

                <div class="relative mb-4">
                    <input type="file" id="multipleImagesInput" name="new_images[]" multiple accept="image/*" class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white" onchange="handleMultipleFiles(event)">
                </div>
                @error('new_images.*') <p class="mb-4 text-xs text-red-600">{{ $message }}</p> @enderror

                <div class="h-[320px] bg-white border border-gray-200 rounded-xl p-4 overflow-y-auto shadow-inner flex flex-col">
                    <div id="multiple-preview-container" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                        <div id="empty-preview-state" class="col-span-full h-full min-h-[250px] flex flex-col items-center justify-center text-center text-gray-400 text-xs font-medium gap-2">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Chưa có ảnh nào được chọn tải lên</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 mt-8 flex justify-end border-t border-gray-100">
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                    Lưu cấu hình & Upload ảnh
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Danh sách hình ảnh phụ kiện hiện tại</h2>
            @if(is_array($phuKienNgoi->images))
                <span class="text-xs text-gray-500 font-medium">Tổng số: {{ count($phuKienNgoi->images) }} ảnh</span>
            @endif
        </div>

        <div class="p-6">
            @if(is_array($phuKienNgoi->images) && count($phuKienNgoi->images) > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                    @foreach($phuKienNgoi->images as $path)
                        <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                            <img src="{{ $assetUrl($path) }}" class="w-full h-full object-contain" alt="Ảnh phụ kiện">
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <button type="button" onclick="openDeleteImageModal('{{ $path }}')" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                                    Xóa ảnh
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm text-center py-6">Chưa có hình ảnh phụ kiện nào được thêm vào hệ thống.</p>
            @endif
        </div>
    </div>

    <div id="deleteImageModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Xác nhận xóa?</h3>
            <p class="text-sm text-gray-500 mb-6">Hành động này không thể hoàn tác. Ảnh sẽ bị xóa vĩnh viễn khỏi hệ thống.</p>

            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteImageModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="deleteImageForm" method="POST" action="{{ route('admin.phu-kien-ngoi.image.destroy') }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="image_path" id="deleteImagePathInput" value="">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Có, Xóa ngay</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewImage(event, targetId) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById(targetId).src = URL.createObjectURL(file);
            }
        }

        let selectedFiles = [];
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
                return;
            }

            emptyState.style.display = 'none';

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'image-preview-item relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100';

                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-contain">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                            <button type="button" onclick="removeFile(${index})" class="w-8 h-8 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors flex items-center justify-center shadow-sm" title="Xóa ảnh này">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    `;
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFileInput();
            renderPreviews();
        }

        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            multipleImagesInput.files = dataTransfer.files;
        }

        const deleteImageModal = document.getElementById('deleteImageModal');
        const deleteImageModalInner = deleteImageModal.querySelector('.bg-white');

        function openDeleteImageModal(imagePath) {
            document.getElementById('deleteImagePathInput').value = imagePath;
            deleteImageModal.classList.remove('hidden');
            deleteImageModal.classList.add('flex');
            void deleteImageModal.offsetWidth;
            deleteImageModal.classList.remove('opacity-0');
            deleteImageModalInner.classList.remove('scale-95');
        }

        function closeDeleteImageModal() {
            deleteImageModal.classList.add('opacity-0');
            deleteImageModalInner.classList.add('scale-95');
            setTimeout(() => {
                deleteImageModal.classList.add('hidden');
                deleteImageModal.classList.remove('flex');
            }, 300);
        }
    </script>
    @endpush
</x-admin.layouts.app>
