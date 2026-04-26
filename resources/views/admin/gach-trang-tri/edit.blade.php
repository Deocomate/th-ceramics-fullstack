<x-admin.layout.app title="Gạch Trang Trí" breadcrumb="Admin › Sản Phẩm › Gạch Trang Trí">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cấu hình Gạch Trang Trí</h2>
        </div>
        
        <form method="POST" action="{{ route('admin.gach-trang-tri.update') }}" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh đại diện chính</label>
                    <div class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                        <img id="preview-main" src="{{ asset('storage/' . $gachTrangTri->thumbnail_main) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-cover" alt="Ảnh chính">
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
                        <input type="url" id="video" name="video" value="{{ old('video', $gachTrangTri->video) }}" placeholder="https://youtube.com/watch?v=..." class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                    </div>
                    @error('video') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                    Lưu cấu hình
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Dấu Ấn Gạch Trang Trí</h2>
        </div>
        
        <div class="p-6">
            <div class="mb-10 p-6 bg-blue-50/30 rounded-xl border border-blue-100 shadow-[0_0_15px_rgba(59,130,246,0.03)]">
                <h3 class="text-sm font-bold text-blue-800 mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    THÊM DẤU ẤN MỚI
                </h3>
                
                <form method="POST" action="{{ route('admin.gach-trang-tri.dau-an.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Hình nền (Background)<span class="text-red-500">*</span></label>
                            <div class="w-full aspect-[4/3] mx-auto rounded-xl border-2 border-dashed border-blue-300 bg-white flex items-center justify-center overflow-hidden relative group hover:bg-blue-50/50 transition-colors">
                                <img id="preview-new-bg" src="https://placehold.co/800x600?text=Chon+Background" class="w-full h-full object-cover" alt="Preview">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Chọn ảnh tải lên</span>
                                </div>
                                <input type="file" name="background" accept="image/*" required onchange="previewImage(event, 'preview-new-bg')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>

                        <div class="lg:col-span-2 flex flex-col gap-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Vị trí <span class="text-red-500">*</span></label>
                                    <input type="text" name="location" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                                </div>
                            </div>
                            <div class="flex-1 flex flex-col">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả chi tiết <span class="text-red-500">*</span></label>
                                <textarea name="description" required class="w-full flex-1 min-h-[100px] px-4 py-3 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end pt-4 border-t border-blue-100">
                        <button type="submit" class="px-6 py-2.5 flex items-center gap-2 text-sm font-bold text-white rounded-lg bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
                            Thêm dấu ấn
                        </button>
                    </div>
                </form>
            </div>

            {{-- DANH SÁCH DẤU ẤN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-6">
                @forelse($gachTrangTri->dauAn as $dauAn)
                    <!-- Thẻ Card với bo góc lớn và tỷ lệ dọc 4:5 -->
                    <div class="group relative overflow-hidden shadow-sm hover:shadow-xl transition-all aspect-[4/5] cursor-pointer" style="border-radius: 40px;">
                        
                        <!-- 1. Background Image -->
                        <img src="{{ asset('storage/' . $dauAn->background) }}" 
                             onerror="this.src='https://placehold.co/800x1000?text=Loi+Anh'" 
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                             alt="{{ $dauAn->title }}">
                        
                        <!-- 2. Gradient Overlay y hệt ảnh -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent pointer-events-none"></div>

                        <!-- 3. Text Content -->
                        <div class="absolute bottom-8 left-8 right-8 text-white z-10 pointer-events-none">
                            <h3 class="font-bold text-xl lg:text-2xl mb-3 leading-tight uppercase">
                                {{ $dauAn->title }}
                            </h3>
                            <p class="text-sm md:text-base text-gray-100 mb-1">
                                <span class="font-bold text-white">Địa điểm:</span> {{ $dauAn->location }}
                            </p>
                            <p class="text-sm md:text-base text-gray-100 line-clamp-2">
                                <span class="font-bold text-white">Sản phẩm:</span> {{ $dauAn->description }}
                            </p>
                        </div>

                        <!-- 4. Admin Actions Overlay (Sửa / Xóa) -->
                        <div class="absolute inset-0 z-20 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-4 backdrop-blur-[2px]">
                            <button type="button" 
                                data-title="{{ $dauAn->title }}"
                                data-location="{{ $dauAn->location }}"
                                data-desc="{{ $dauAn->description }}"
                                data-bg="{{ asset('storage/' . $dauAn->background) }}"
                                onclick="openEditModal(this, '{{ route('admin.gach-trang-tri.dau-an.update', $dauAn) }}')"
                                class="flex items-center gap-2 px-8 py-3 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-sm w-36 justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Sửa
                            </button>
                            <button type="button" 
                                onclick="openDeleteModal('{{ route('admin.gach-trang-tri.dau-an.destroy', $dauAn) }}')"
                                class="flex items-center gap-2 px-8 py-3 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 transition-colors shadow-sm w-36 justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Xóa
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Chưa có dấu ấn nào được cấu hình.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center shrink-0">
                <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Cập nhật Dấu Ấn
                </h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Hình nền (Background)</label>
                            <div class="w-full aspect-[4/3] mx-auto rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                                <img id="preview-edit-bg" src="" class="w-full h-full object-cover" alt="Preview">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Đổi ảnh</span>
                                </div>
                                <input type="file" name="background" accept="image/*" onchange="previewImage(event, 'preview-edit-bg')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>
                        
                        <div class="lg:col-span-2 flex flex-col gap-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề <span class="text-red-500">*</span></label>
                                    <input type="text" id="edit_title" name="title" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Vị trí <span class="text-red-500">*</span></label>
                                    <input type="text" id="edit_location" name="location" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                                </div>
                            </div>
                            <div class="flex-1 flex flex-col">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả chi tiết <span class="text-red-500">*</span></label>
                                <textarea id="edit_description" name="description" required class="w-full flex-1 min-h-[100px] px-4 py-3 text-sm border rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all resize-none"></textarea>
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
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Xác nhận xóa?</h3>
            <p class="text-sm text-gray-500 mb-6">Hành động này không thể hoàn tác. Dữ liệu sẽ bị xóa vĩnh viễn khỏi hệ thống.</p>
            
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf @method('DELETE')
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
                const objectUrl = URL.createObjectURL(file);
                document.getElementById(targetId).src = objectUrl;
                document.getElementById(targetId).onload = function() {
                    URL.revokeObjectURL(objectUrl);
                }
            }
        }

        const editModal = document.getElementById('editModal');
        const editModalInner = editModal.querySelector('.bg-white');

        function openEditModal(btnElement, actionUrl) {
            const title = btnElement.getAttribute('data-title');
            const location = btnElement.getAttribute('data-location');
            const desc = btnElement.getAttribute('data-desc');
            const bgUrl = btnElement.getAttribute('data-bg');

            document.getElementById('editForm').action = actionUrl;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_location').value = location;
            document.getElementById('edit_description').value = desc;
            document.getElementById('preview-edit-bg').src = bgUrl;

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