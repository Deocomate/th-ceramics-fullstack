/* ===== resources/views/admin/du-an/edit.blade.php ===== */
@section('preview_url', route('client.projects.detail', $duAn->slug))

<x-admin.layouts.app title="Cập nhật Dự Án" breadcrumb="Admin › Dự Án › Chỉnh sửa">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cập nhật Dự Án: {{ $duAn->ten_du_an }}</h2>
            <span class="text-xs font-mono text-gray-500 bg-gray-200 px-2 py-1 rounded">Slug: {{ $duAn->slug }}</span>
        </div>
        <form method="POST" action="{{ route('admin.du-an.update', $duAn->du_an_id) }}" enctype="multipart/form-data" class="p-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- CỘT THÔNG TIN CHUNG -->
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tên dự án <span class="text-red-500">*</span></label>
                        <input type="text" name="ten_du_an" value="{{ old('ten_du_an', $duAn->ten_du_an) }}" required class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                        <select name="danh_muc_du_an_id" required class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($danhMucs as $dm)
                                <option value="{{ $dm->danh_muc_du_an_id }}" {{ $duAn->danh_muc_du_an_id == $dm->danh_muc_du_an_id ? 'selected' : '' }}>{{ $dm->ten_danh_muc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Địa điểm <span class="text-red-500">*</span></label>
                            <input type="text" name="dia_diem" value="{{ old('dia_diem', $duAn->dia_diem) }}" required class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Năm thực hiện</label>
                            <input type="number" name="nam" value="{{ old('nam', $duAn->nam) }}" class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Sản phẩm sử dụng <span class="text-red-500">*</span></label>
                        <input type="text" name="san_pham" value="{{ old('san_pham', $duAn->san_pham) }}" required class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
                    </div>
                </div>

                <!-- CỘT THÊM HÌNH ẢNH MỚI -->
                <div class="flex flex-col h-full border border-gray-200 rounded-xl p-6 bg-gray-50/50">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Thêm Hình Ảnh Mới</label>
                    <div class="relative mb-4">
                        <input type="file" id="multipleImagesInput" name="new_images[]" multiple accept="image/*" class="w-full text-sm border border-gray-300 rounded-lg p-1.5 cursor-pointer bg-white" onchange="handleMultipleFiles(event)">
                    </div>
                    @error('new_images.*') <p class="mb-4 text-xs text-red-600">{{ $message }}</p> @enderror
                    <div class="flex-1 bg-white border border-gray-200 rounded-xl p-4 overflow-y-auto shadow-inner flex flex-col min-h-[250px]">
                        <div id="multiple-preview-container" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                            <div id="empty-preview-state" class="col-span-full h-full min-h-[180px] flex items-center justify-center text-gray-400 text-xs">Chưa chọn thêm ảnh nào</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 mt-8 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.du-an.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg">Hủy bỏ</a>
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm" style="background:#A31D1D;">Lưu Thay Đổi</button>
            </div>
        </form>
    </div>

    <!-- DANH SÁCH ẢNH HIỆN TẠI -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Hình ảnh dự án hiện tại</h2>
        </div>
        <div class="p-6">
            @if(is_array($duAn->images) && count($duAn->images) > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($duAn->images as $path)
                        <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 bg-gray-100">
                            <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-contain">
                            @if($loop->first)
                                <div class="absolute top-2 left-2 bg-[#A31D1D] text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">Ảnh bìa</div>
                            @endif
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                <button type="button" onclick="openDeleteImageModal('{{ $path }}')" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">Xóa ảnh này</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm text-center py-6">Dự án này chưa có hình ảnh chi tiết nào.</p>
            @endif
        </div>
    </div>

    {{-- MODAL XÓA ẢNH --}}
    <div id="deleteImageModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center transform scale-95 transition-transform duration-300">
            <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2 mt-4">Xác nhận xóa?</h3>
            <p class="text-sm text-gray-500 mb-6">Ảnh này sẽ bị xóa khỏi danh sách ảnh của dự án.</p>
            <div class="flex justify-center gap-3 mt-6">
                <button type="button" onclick="closeDeleteImageModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Hủy</button>
                <form id="deleteImageForm" method="POST" action="{{ route('admin.du-an.image.destroy', $duAn->du_an_id) }}" class="flex-1">
                    @csrf @method('DELETE')
                    <input type="hidden" name="image_path" id="deleteImagePathInput" value="">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">Có, Xóa</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
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
            previewContainer.querySelectorAll('.image-preview-item').forEach(i => i.remove());
            if (selectedFiles.length === 0) {
                emptyState.style.display = 'flex';
            } else {
                emptyState.style.display = 'none';
                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'image-preview-item relative aspect-square rounded-lg overflow-hidden bg-gray-100 group shadow-sm border border-gray-200';
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-contain">
                                         <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity backdrop-blur-[2px]">
                                            <button type="button" onclick="removeFile(${index})" class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors shadow-sm" title="Bỏ ảnh này">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                         </div>`;
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
            const dt = new DataTransfer();
            selectedFiles.forEach(f => dt.items.add(f));
            multipleImagesInput.files = dt.files;
        }

        // ====== LOGIC MODAL XÓA ẢNH ======
        const deleteImageModal = document.getElementById('deleteImageModal');
        const deleteImageModalInner = deleteImageModal.querySelector('.bg-white');

        function openDeleteImageModal(imagePath) {
            document.getElementById('deleteImagePathInput').value = imagePath;
            deleteImageModal.classList.remove('hidden');
            deleteImageModal.classList.add('flex');
            // Timeout nhỏ để trigger CSS transition
            setTimeout(() => {
                deleteImageModal.classList.remove('opacity-0');
                deleteImageModalInner.classList.remove('scale-95');
            }, 10);
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
