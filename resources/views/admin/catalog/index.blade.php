<x-admin.layout.app title="Quản lý Catalog" breadcrumb="Admin › Cấu Hình Chung › Catalog">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Danh Sách Catalog</h2>
            <p class="text-xs text-gray-400 mt-0.5">Tổng cộng {{ $catalogs->total() }} mục</p>
        </div>
        <div class="p-6">
            @if ($errors->any())
                <div class="mb-8 flex items-start gap-3 px-4 py-3 rounded text-sm text-red-800 bg-red-50 border border-red-200 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <strong class="font-semibold block mb-1">Thao tác không thành công. Vui lòng kiểm tra lại:</strong>
                        <ul class="list-disc ml-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="mb-10 p-6 bg-blue-50/30 rounded-xl border border-blue-100 shadow-[0_0_15px_rgba(59,130,246,0.03)]">
                <h3 class="text-sm font-bold text-blue-800 mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    THÊM CATALOG MỚI
                </h3>
                <form method="POST" action="{{ route('admin.catalog.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                        <div class="lg:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh đại diện <span class="text-red-500">*</span></label>
                            <div class="w-full aspect-[3/4] mx-auto rounded-xl border-2 border-dashed border-blue-300 bg-white flex items-center justify-center overflow-hidden relative group hover:bg-blue-50/50 transition-colors">
                                <img id="preview-new-catalog" src="https://placehold.co/600x800?text=Chon+Anh" class="w-full h-full object-cover" alt="Preview">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Chọn ảnh</span>
                                </div>
                                <input type="file" name="anh_dai_dien" accept="image/*" required onchange="previewImage(event, 'preview-new-catalog')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>
                        <div class="lg:col-span-3 flex flex-col gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề (Tùy chọn)</label>
                                <input type="text" name="tieu_de" placeholder="VD: Catalog Gạch Ngói 2024..." class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">File Catalog (PDF, DOCX, ZIP...) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="file" name="file" accept=".pdf,.doc,.docx,.zip,.rar" required class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white transition-all">
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Dung lượng tối đa: 20MB.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end pt-4 border-t border-blue-100">
                        <button type="submit" class="px-6 py-2.5 flex items-center gap-2 text-sm font-bold text-white rounded-lg bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
                            Thêm Catalog
                        </button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($catalogs as $item)
                    <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow bg-white flex flex-col group relative">
                        <div class="w-full aspect-[3/4] relative bg-gray-100 flex-shrink-0 overflow-hidden border-b border-gray-100">
                            <img src="{{ asset('storage/' . $item->anh_dai_dien) }}" onerror="this.src='https://placehold.co/600x800?text=Loi+Anh'" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-3 backdrop-blur-[2px]">
                                <button type="button" 
                                    data-id="{{ $item->catalog_id }}"
                                    data-name="{{ $item->tieu_de }}"
                                    data-img="{{ asset('storage/' . $item->anh_dai_dien) }}"
                                    data-file="{{ $item->file ? asset('storage/' . $item->file) : '' }}"
                                    onclick="openEditModal(this, '{{ route('admin.catalog.update', $item->catalog_id) }}')"
                                    class="flex items-center gap-1.5 px-6 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-sm w-36 justify-center">
                                    Sửa
                                </button>
                                <button type="button" 
                                    onclick="openDeleteModal('{{ route('admin.catalog.destroy', $item->catalog_id) }}')"
                                    class="flex items-center gap-1.5 px-6 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm w-36 justify-center">
                                    Xóa
                                </button>
                            </div>
                        </div>
                        <div class="p-4 w-full flex flex-col flex-1 bg-white items-center text-center">
                            <h4 class="font-bold text-gray-800 text-sm mb-2 truncate w-full" title="{{ $item->tieu_de }}">{{ $item->tieu_de ?? 'Chưa có tiêu đề' }}</h4>
                            @if($item->file)
                                <a href="{{ asset('storage/' . $item->file) }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:underline bg-blue-50 px-3 py-1.5 rounded-full inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Tải file hiện tại
                                </a>
                            @else
                                <span class="text-xs text-red-500 italic">Không có file đính kèm</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                        <p class="text-sm font-medium text-gray-500">Chưa có catalog nào được cấu hình.</p>
                    </div>
                @endforelse
            </div>

            {{-- PHÂN TRANG --}}
            @if($catalogs->hasPages())
                <div class="mt-8 border-t border-gray-100 pt-6">
                    {{ $catalogs->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL CHỈNH SỬA --}}
    <div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center shrink-0">
                <h3 class="font-bold text-gray-800 text-lg">Cập nhật Catalog</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh đại diện</label>
                            <div class="w-full aspect-[3/4] max-w-[200px] mx-auto rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                                <img id="preview-edit-img" src="" class="w-full h-full object-cover" alt="Preview">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Đổi ảnh</span>
                                </div>
                                <input type="file" name="anh_dai_dien" accept="image/*" onchange="previewImage(event, 'preview-edit-img')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>
                        <div class="md:col-span-2 space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề (Tùy chọn)</label>
                                <input type="text" id="edit_name" name="tieu_de" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Đổi File Mới (Tùy chọn)</label>
                                <input type="file" name="file" accept=".pdf,.doc,.docx,.zip,.rar" class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white transition-all">
                                <p class="text-xs text-gray-500 mt-2">Dung lượng tối đa 20MB. Để trống nếu không muốn thay đổi file cũ.</p>
                                <div class="mt-2 text-sm" id="edit_file_link"></div>
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

    {{-- MODAL XÓA --}}
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
            document.getElementById('editForm').action = actionUrl;
            document.getElementById('edit_name').value = btnElement.getAttribute('data-name');
            document.getElementById('preview-edit-img').src = btnElement.getAttribute('data-img');
            
            const fileUrl = btnElement.getAttribute('data-file');
            const fileLinkContainer = document.getElementById('edit_file_link');
            if(fileUrl) {
                fileLinkContainer.innerHTML = `File hiện tại: <a href="${fileUrl}" target="_blank" class="text-blue-600 hover:underline">Xem file</a>`;
            } else {
                fileLinkContainer.innerHTML = `<span class="text-red-500 italic">Chưa có file hiện tại</span>`;
            }
            
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