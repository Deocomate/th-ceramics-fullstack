@php
    $duAn = $duAn ?? null;
    $isEdit = isset($duAn);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- CỘT THÔNG TIN CHUNG -->
    <div class="space-y-5">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tên dự án <span class="text-red-500">*</span></label>
            <input type="text" name="ten_du_an" value="{{ old('ten_du_an', $duAn->ten_du_an ?? '') }}" required class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
            <select name="danh_muc_du_an_id" required class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
                <option value="">-- Chọn danh mục --</option>
                @foreach($danhMucs as $dm)
                    <option value="{{ $dm->danh_muc_du_an_id }}" {{ (string)old('danh_muc_du_an_id', $duAn->danh_muc_du_an_id ?? '') === (string)$dm->danh_muc_du_an_id ? 'selected' : '' }}>{{ $dm->ten_danh_muc }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Địa điểm <span class="text-red-500">*</span></label>
                <input type="text" name="dia_diem" value="{{ old('dia_diem', $duAn->dia_diem ?? '') }}" required class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Năm thực hiện</label>
                <input type="number" name="nam" value="{{ old('nam', $duAn->nam ?? '') }}" placeholder="VD: 2024" class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Sản phẩm sử dụng <span class="text-red-500">*</span></label>
            <input type="text" name="san_pham" value="{{ old('san_pham', $duAn->san_pham ?? '') }}" required placeholder="VD: Ngói Âm Dương Nâu Đen..." class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
        </div>
    </div>

    <!-- CỘT HÌNH ẢNH -->
    <div class="flex flex-col h-full border border-gray-200 rounded-xl p-6 bg-gray-50/50">
        @if($isEdit)
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
        @else
            <label class="block text-sm font-semibold text-gray-700 mb-2">Hình ảnh dự án <span class="text-red-500">*</span></label>
            <p class="text-xs text-gray-500 mb-4">Chọn 1 hoặc nhiều ảnh của dự án (Ảnh đầu tiên sẽ làm ảnh bìa).</p>
            <div class="relative mb-4">
                <input type="file" id="multipleImagesInput" name="images[]" multiple required accept="image/*" class="w-full text-sm border border-gray-300 rounded-lg p-1.5 cursor-pointer bg-white" onchange="handleMultipleFiles(event)">
            </div>
            @error('images') <p class="mb-4 text-xs text-red-600">{{ $message }}</p> @enderror
            <div class="flex-1 bg-white border border-gray-200 rounded-xl p-4 overflow-y-auto shadow-inner flex flex-col min-h-[250px]">
                <div id="multiple-preview-container" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                    <div id="empty-preview-state" class="col-span-full h-full min-h-[180px] flex flex-col items-center justify-center text-center text-gray-400 text-xs gap-2">
                        <span>Chưa có ảnh nào</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="pt-6 mt-8 flex justify-end gap-3 border-t border-gray-100">
    <a href="{{ route('admin.du-an.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg">{{ $isEdit ? 'Hủy bỏ' : 'Hủy' }}</a>
    <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm" style="background:#A31D1D;">{{ $isEdit ? 'Lưu Thay Đổi' : 'Lưu Dự Án' }}</button>
</div>

@push('scripts')
<script>
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
        if (window.AdminImageOptimizer && typeof window.AdminImageOptimizer.rememberFiles === 'function') {
            window.AdminImageOptimizer.rememberFiles(multipleImagesInput);
        }
    }
</script>
@endpush
