<x-admin.layout.app title="Ngói Âm Dương" breadcrumb="Admin › Sản Phẩm › Ngói Âm Dương">
    
    {{-- THÔNG TIN CHUNG --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cấu hình chung</h2>
        </div>
        
        <form method="POST" action="{{ route('admin.ngoi-am-duong.update') }}" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Cột Ảnh chính -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh đại diện chính</label>
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

    {{-- QUẢN LÝ GIÁ TRỊ NỔI BẬT --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Giá trị vượt trội</h2>
        </div>
        
        <div class="p-6">
            {{-- FORM THÊM MỚI GIÁ TRỊ --}}
            <div class="mb-10 p-6 bg-blue-50/30 rounded-xl border border-blue-100 shadow-[0_0_15px_rgba(59,130,246,0.03)]">
                <h3 class="text-sm font-bold text-blue-800 mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    THÊM GIÁ TRỊ MỚI
                </h3>
                
                <form method="POST" action="{{ route('admin.ngoi-am-duong.gia-tri.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <!-- Block Upload Ảnh Mới -->
                        <div class="lg:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Hình ảnh<span class="text-red-500">*</span></label>
                            {{-- Chỉnh khung preview dùng aspect-[3/5] để tương đồng với khung dưới danh sách --}}
                            <div class="w-[300px] h-[460px] mx-auto rounded-xl border-2 border-dashed border-blue-300 bg-white flex items-center justify-center overflow-hidden relative group hover:bg-blue-50/50 transition-colors">
                                <img id="preview-new-giatri" src="https://placehold.co/300x500?text=Chon+Anh" class="w-full h-full object-cover" alt="Preview">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Chọn ảnh tải lên</span>
                                </div>
                                <input type="file" name="image" accept="image/*" required onchange="previewImage(event, 'preview-new-giatri')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>

                        <!-- Block Tiêu đề & Mô tả -->
                        <div class="lg:col-span-2 flex flex-col gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề <span class="text-red-500">*</span></label>
                                <input type="text" name="title" required placeholder="VD: Kháng nước, chống rêu mốc..." class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                            </div>
                            <div class="flex-1 flex flex-col">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả chi tiết <span class="text-red-500">*</span></label>
                                <textarea name="desscription" required placeholder="Nhập nội dung mô tả ngắn gọn về đặc điểm này..." class="w-full flex-1 min-h-[100px] px-4 py-3 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end pt-4 border-t border-blue-100">
                        <button type="submit" class="px-6 py-2.5 flex items-center gap-2 text-sm font-bold text-white rounded-lg bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
                            Thêm giá trị này
                        </button>
                    </div>
                </form>
            </div>

            {{-- DANH SÁCH CÁC GIÁ TRỊ ĐÃ THÊM (CHIA 4 CỘT) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($ngoiAmDuong->giaTri as $giaTri)
                    <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow bg-white flex flex-col group relative">
                        
                        {{-- Ảnh tự động co giãn theo tỷ lệ 3:5 --}}
                        <div class="w-[300px] h-[460px] mx-auto relative bg-gray-100 flex-shrink-0 border-b border-gray-100">
                            <img src="{{ asset('storage/' . $giaTri->image) }}" onerror="this.src='https://placehold.co/300x500?text=Loi+Anh'" class="w-full h-full object-cover">
                            
                            {{-- Overlay Các Nút Thao Tác (Sửa/Xóa) --}}
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-3 backdrop-blur-[2px]">
                                {{-- Nút sửa --}}
                                <button type="button" 
                                    data-title="{{ $giaTri->title }}"
                                    data-desc="{{ $giaTri->desscription }}"
                                    data-img="{{ asset('storage/' . $giaTri->image) }}"
                                    onclick="openEditModal(this, '{{ route('admin.ngoi-am-duong.gia-tri.update', $giaTri) }}')"
                                    class="flex items-center gap-1.5 px-6 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-sm w-32 justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Sửa
                                </button>

                                {{-- Nút xóa --}}
                                <button type="button" 
                                    onclick="openDeleteModal('{{ route('admin.ngoi-am-duong.gia-tri.destroy', $giaTri) }}')"
                                    class="flex items-center gap-1.5 px-6 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm w-32 justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Xóa
                                </button>
                            </div>
                        </div>

                        {{-- Thông tin mô tả bên dưới --}}
                        <div class="p-5 w-full flex flex-col flex-1 text-center">
                            <h4 class="font-bold text-gray-800 text-base mb-2">{{ $giaTri->title }}</h4>
                            <p class="text-sm text-gray-500 line-clamp-4 leading-relaxed">{{ $giaTri->desscription }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Chưa có giá trị nổi bật nào được cấu hình.</p>
                        <p class="text-xs text-gray-400 mt-1">Sử dụng form bên trên để thêm giá trị mới.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL CHỈNH SỬA (SỬA GIÁ TRỊ) --}}
    <div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Cập nhật Giá trị
                </h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Ảnh Sửa -->
                    <div class="lg:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Hình ảnh minh họa</label>
                        <div class="w-[300px] h-[460px] mx-auto rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                            <img id="preview-edit-giatri" src="" class="w-full h-full object-cover" alt="Preview">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Đổi ảnh khác</span>
                            </div>
                            <input type="file" name="image" accept="image/*" onchange="previewImage(event, 'preview-edit-giatri')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                    </div>
                    
                    <!-- Thông tin Sửa -->
                    <div class="lg:col-span-2 flex flex-col gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_title" name="title" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div class="flex-1 flex flex-col">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả chi tiết <span class="text-red-500">*</span></label>
                            <textarea id="edit_description" name="desscription" required class="w-full flex-1 min-h-[150px] px-4 py-3 text-sm border rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all resize-none"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-gray-100">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy bỏ</button>
                    <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL XÓA (XÓA GIÁ TRỊ) --}}
    <div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Xác nhận xóa?</h3>
            <p class="text-sm text-gray-500 mb-6">Hành động này không thể hoàn tác. Dữ liệu và hình ảnh của mục này sẽ bị xóa vĩnh viễn khỏi hệ thống.</p>
            
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Có, Xóa ngay</button>
                </form>
            </div>
        </div>
    </div>


    {{-- SCRIPTS XỬ LÝ ẢNH & MODAL --}}
    @push('scripts')
    <script>
        // Xử lý Preview ảnh khi Upload
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

        // --------- Xử lý Modal Edit ---------
        const editModal = document.getElementById('editModal');
        const editModalInner = editModal.querySelector('.bg-white');

        function openEditModal(btnElement, actionUrl) {
            const title = btnElement.getAttribute('data-title');
            const desc = btnElement.getAttribute('data-desc');
            const imgUrl = btnElement.getAttribute('data-img');

            document.getElementById('editForm').action = actionUrl;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_description').value = desc;
            document.getElementById('preview-edit-giatri').src = imgUrl;

            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
            void editModal.offsetWidth; 
            editModal.classList.remove('opacity-0');
            editModalInner.classList.remove('scale-95');
        }

        function closeEditModal() {
            editModal.classList.add('opacity-0');
            editModalInner.classList.add('scale-95');
            setTimeout(() => {
                editModal.classList.add('hidden');
                editModal.classList.remove('flex');
            }, 300);
        }

        // --------- Xử lý Modal Delete ---------
        const deleteModal = document.getElementById('deleteModal');
        const deleteModalInner = deleteModal.querySelector('.bg-white');

        function openDeleteModal(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            
            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');
            void deleteModal.offsetWidth;
            deleteModal.classList.remove('opacity-0');
            deleteModalInner.classList.remove('scale-95');
        }

        function closeDeleteModal() {
            deleteModal.classList.add('opacity-0');
            deleteModalInner.classList.add('scale-95');
            setTimeout(() => {
                deleteModal.classList.add('hidden');
                deleteModal.classList.remove('flex');
            }, 300);
        }
    </script>
    @endpush
</x-admin.layout.app>