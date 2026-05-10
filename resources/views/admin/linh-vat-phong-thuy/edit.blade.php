<x-admin.layout.app title="Linh Vật Phong Thủy" breadcrumb="Admin › Sản Phẩm › Linh Vật Phong Thủy">
    
    {{-- CẤU HÌNH HÌNH ẢNH & THƯ VIỆN --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cấu Hình Hình Ảnh & Thư Viện</h2>
        </div>

        <form method="POST" action="{{ route('admin.linh-vat-phong-thuy.update') }}" enctype="multipart/form-data" class="p-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh nền</label>
                        <div class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                            <img id="preview-main" src="{{ asset('storage/' . $linhVatPhongThuy->thumbnail_main) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-contain">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                            </div>
                            <input type="file" name="thumbnail_main" accept="image/*" onchange="previewImage(event, 'preview-main')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('thumbnail_main') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="video" class="block text-sm font-semibold text-gray-700 mb-2">Đường dẫn Video YouTube</label>
                        <input type="url" id="video" name="video" value="{{ old('video', $linhVatPhongThuy->video) }}" class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                        @error('video') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-col h-full border border-gray-200 rounded-xl p-4 bg-gray-50/50">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Thư viện ảnh (Chọn nhiều ảnh cùng lúc)</label>
                    <div class="relative mb-4">
                        <input type="file" id="multipleImagesInput" name="new_images[]" multiple accept="image/*" class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white" onchange="handleMultipleFiles(event)">
                    </div>
                    @error('new_images.*') <p class="mb-4 text-xs text-red-600">{{ $message }}</p> @enderror

                    <div class="h-[450px] bg-white border border-gray-200 rounded-xl p-4 overflow-y-auto shadow-inner flex flex-col">
                        <div id="multiple-preview-container" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                            <div id="empty-preview-state" class="col-span-full h-full min-h-[380px] flex flex-col items-center justify-center text-center text-gray-400 text-xs font-medium gap-2">
                                <span>Chưa có ảnh nào được chọn tải lên</span>
                            </div>
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

    {{-- DANH SÁCH ẢNH ĐÃ LƯU --}}
    @if (count($linhVatPhongThuy->anh) > 0)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Thư viện ảnh công đoạn chế tác</h2>
                <span class="text-xs text-gray-500 font-medium">Tổng số: {{ count($linhVatPhongThuy->anh) }} ảnh</span>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                    @foreach ($linhVatPhongThuy->anh as $anh)
                        <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                            <img src="{{ asset('storage/' . $anh->image) }}" class="w-full h-full object-contain">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <form action="{{ route('admin.linh-vat-phong-thuy.anh.destroy', $anh) }}" method="POST" onsubmit="return confirm('Xóa ảnh thư viện này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">Xóa ảnh</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- QUẢN LÝ LINH VẬT --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Danh sách Linh Vật</h2>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="mb-8 flex items-start gap-3 px-4 py-3 rounded text-sm text-red-800 bg-red-50 border border-red-200 shadow-sm">
                    <div>
                        <strong class="font-semibold block mb-1">Có lỗi xảy ra:</strong>
                        <ul class="list-disc ml-4">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="mb-10 p-6 bg-blue-50/30 rounded-xl border border-blue-100">
                <h3 class="text-sm font-bold text-blue-800 mb-5">THÊM LINH VẬT MỚI</h3>

                <form method="POST" action="{{ route('admin.linh-vat-phong-thuy.linh-vat.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Hình ảnh<span class="text-red-500">*</span></label>
                            <div class="w-[300px] h-[460px] mx-auto rounded-xl border-2 border-dashed border-blue-300 bg-white flex items-center justify-center overflow-hidden relative group">
                                <img id="preview-new-lv" src="https://placehold.co/300x500?text=Chon+Anh" class="w-full h-full object-contain">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Chọn ảnh tải lên</span>
                                </div>
                                <input type="file" name="image" accept="image/*" required onchange="previewImage(event, 'preview-new-lv')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>

                        <div class="lg:col-span-2 flex flex-col gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề <span class="text-red-500">*</span></label>
                                <input type="text" name="title" required placeholder="Tối đa 50 ký tự" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                            </div>
                            <div class="flex-1 flex flex-col">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả chi tiết <span class="text-red-500">*</span></label>
                                <textarea name="description" required class="w-full flex-1 min-h-[100px] px-4 py-3 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end pt-4 border-t border-blue-100">
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white rounded-lg bg-blue-600 hover:bg-blue-700 shadow-sm">Thêm linh vật</button>
                    </div>
                </form>
            </div>

            {{-- DANH SÁCH LINH VẬT ĐÃ LƯU --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($linhVatPhongThuy->linhVat as $item)
                    <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow bg-white flex flex-col group relative">
                        <div class="w-[300px] h-[460px] mx-auto mt-4 relative bg-gray-100 flex-shrink-0 rounded-xl overflow-hidden border border-gray-100">
                            <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-contain">
                            
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-3 backdrop-blur-[2px]">
                                <button type="button" 
                                    data-title="{{ $item->title }}" data-desc="{{ $item->description }}" data-img="{{ asset('storage/' . $item->image) }}"
                                    onclick="openEditModal(this, '{{ route('admin.linh-vat-phong-thuy.linh-vat.update', $item) }}')"
                                    class="w-32 px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 shadow-sm">Sửa</button>

                                <button type="button" 
                                    onclick="openDeleteModal('{{ route('admin.linh-vat-phong-thuy.linh-vat.destroy', $item) }}')"
                                    class="w-32 px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 shadow-sm">Xóa</button>
                            </div>
                        </div>

                        <div class="p-5 w-full flex flex-col flex-1 text-center">
                            <h4 class="font-bold text-gray-800 text-base mb-2 truncate">{{ $item->title }}</h4>
                            <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed break-all">{{ $item->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MODAL SỬA & XÓA --}}
    <div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-lg">Cập nhật Linh Vật</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500">Đóng</button>
            </div>
            
            <div class="p-6 overflow-y-auto">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Hình ảnh</label>
                            <div class="w-full aspect-[3/5] max-w-[280px] mx-auto rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                                <img id="preview-edit-img" src="" class="w-full h-full object-contain">
                                <input type="file" name="image" accept="image/*" onchange="previewImage(event, 'preview-edit-img')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>
                        <div class="lg:col-span-2 flex flex-col gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề <span class="text-red-500">*</span></label>
                                <input type="text" id="edit_title" name="title" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500">
                            </div>
                            <div class="flex-1 flex flex-col">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả chi tiết <span class="text-red-500">*</span></label>
                                <textarea id="edit_description" name="description" required class="w-full flex-1 min-h-[150px] px-4 py-3 text-sm border rounded-lg border-gray-300 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-gray-100">
                        <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg">Hủy bỏ</button>
                        <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg shadow-sm">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center transform scale-95 transition-transform">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Xác nhận xóa?</h3>
            <p class="text-sm text-gray-500 mb-6">Dữ liệu sẽ bị xóa vĩnh viễn khỏi hệ thống.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg">Hủy</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 rounded-lg shadow-sm">Xóa ngay</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewImage(event, targetId) {
            const file = event.target.files[0];
            if (file) document.getElementById(targetId).src = URL.createObjectURL(file);
        }

        // Multiple file upload preview logic
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
                            <img src="${e.target.result}" class="w-full h-full object-contain">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <button type="button" onclick="removeFile(${index})" class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center shadow-sm">X</button>
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

        // Modal logic
        const editModal = document.getElementById('editModal');
        const editModalInner = editModal.querySelector('.bg-white');
        function openEditModal(btn, actionUrl) {
            document.getElementById('editForm').action = actionUrl;
            document.getElementById('edit_title').value = btn.getAttribute('data-title');
            document.getElementById('edit_description').value = btn.getAttribute('data-desc');
            document.getElementById('preview-edit-img').src = btn.getAttribute('data-img');
            editModal.classList.remove('hidden'); editModal.classList.add('flex');
            void editModal.offsetWidth; editModal.classList.remove('opacity-0'); editModalInner.classList.remove('scale-95');
        }
        function closeEditModal() {
            editModal.classList.add('opacity-0'); editModalInner.classList.add('scale-95');
            setTimeout(() => { editModal.classList.add('hidden'); editModal.classList.remove('flex'); }, 300);
        }

        const deleteModal = document.getElementById('deleteModal');
        const deleteModalInner = deleteModal.querySelector('.bg-white');
        function openDeleteModal(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            deleteModal.classList.remove('hidden'); deleteModal.classList.add('flex');
            void deleteModal.offsetWidth; deleteModal.classList.remove('opacity-0'); deleteModalInner.classList.remove('scale-95');
        }
        function closeDeleteModal() {
            deleteModal.classList.add('opacity-0'); deleteModalInner.classList.add('scale-95');
            setTimeout(() => { deleteModal.classList.add('hidden'); deleteModal.classList.remove('flex'); }, 300);
        }
    </script>
    @endpush
</x-admin.layout.app>