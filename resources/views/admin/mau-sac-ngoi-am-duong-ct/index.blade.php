<x-admin.layout.app title="Màu Sắc Ngói Âm Dương" breadcrumb="Admin › DS Sản phẩm chi tiết › Ngói Âm Dương › Màu Sắc">
    
    @if ($errors->any())
        <div class="mb-6 flex items-start gap-3 px-4 py-3 rounded text-sm text-red-800 bg-red-50 border border-red-200 shadow-sm">
            <div>
                <strong class="font-semibold block mb-1">Vui lòng kiểm tra lại:</strong>
                <ul class="list-disc ml-4">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- CỘT 1: FORM THÊM MỚI -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Thêm Màu Sắc Mới</h3>
                </div>
                <form action="{{ route('admin.mau-sac-ngoi-am-duong-ct.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Hình ảnh màu <span class="text-red-500">*</span></label>
                            <div class="aspect-square w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors">
                                <img id="preview-new" src="https://placehold.co/400x400?text=Chon+Anh" class="w-full h-full object-contain">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Chọn ảnh</span>
                                </div>
                                <input type="file" name="image" accept="image/*" required onchange="previewImage(event, 'preview-new')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tên màu sắc <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required placeholder="VD: Đỏ đun, Xanh ngọc..." class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <button type="submit" class="w-full py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                            Lưu Màu Sắc
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- CỘT 2: DANH SÁCH MÀU SẮC -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Danh Sách Màu Sắc ({{ count($mauSacs) }})</h3>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
                    @forelse($mauSacs as $mauSac)
                        <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow bg-white flex flex-col group relative">
                            <div class="aspect-square w-full relative bg-gray-100 flex-shrink-0">
                                <img src="{{ asset('storage/' . $mauSac->image) }}" class="w-full h-full object-contain">
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 backdrop-blur-[2px]">
                                    <button type="button" 
                                        data-name="{{ $mauSac->name }}" 
                                        data-img="{{ asset('storage/' . $mauSac->image) }}"
                                        onclick="openEditModal(this, '{{ route('admin.mau-sac-ngoi-am-duong-ct.update', $mauSac->mau_sac_ngoi_am_duong_ct_id) }}')"
                                        class="px-4 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 shadow-sm w-24">Sửa</button>
                                    <button type="button" 
                                        onclick="openDeleteModal('{{ route('admin.mau-sac-ngoi-am-duong-ct.destroy', $mauSac->mau_sac_ngoi_am_duong_ct_id) }}')"
                                        class="px-4 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 shadow-sm w-24">Xóa</button>
                                </div>
                            </div>
                            <div class="p-3 text-center border-t border-gray-100">
                                <h4 class="font-bold text-gray-800 text-sm truncate">{{ $mauSac->name }}</h4>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                            <p class="text-sm font-medium text-gray-500">Chưa có màu sắc nào.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Sửa Màu Sắc</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hình ảnh</label>
                    <div class="aspect-square w-full rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden relative group">
                        <img id="preview-edit" src="" class="w-full h-full object-contain">
                        <input type="file" name="image" accept="image/*" onchange="previewImage(event, 'preview-edit')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tên màu sắc <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_name" name="name" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 outline-none transition-all">
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg">Hủy</button>
                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DELETE -->
    <div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-2 mt-4">Xác nhận xóa?</h3>
            <p class="text-sm text-gray-500 mb-6">Dữ liệu sẽ bị xóa vĩnh viễn khỏi hệ thống.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg">Hủy</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 rounded-lg">Xóa ngay</button>
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

        const editModal = document.getElementById('editModal');
        function openEditModal(btn, actionUrl) {
            document.getElementById('editForm').action = actionUrl;
            document.getElementById('edit_name').value = btn.getAttribute('data-name');
            document.getElementById('preview-edit').src = btn.getAttribute('data-img');
            editModal.classList.remove('hidden'); editModal.classList.add('flex');
            setTimeout(() => { editModal.classList.remove('opacity-0'); editModal.children[0].classList.remove('scale-95'); }, 10);
        }
        function closeEditModal() {
            editModal.classList.add('opacity-0'); editModal.children[0].classList.add('scale-95');
            setTimeout(() => { editModal.classList.add('hidden'); editModal.classList.remove('flex'); }, 300);
        }

        const deleteModal = document.getElementById('deleteModal');
        function openDeleteModal(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            deleteModal.classList.remove('hidden'); deleteModal.classList.add('flex');
            setTimeout(() => { deleteModal.classList.remove('opacity-0'); deleteModal.children[0].classList.remove('scale-95'); }, 10);
        }
        function closeDeleteModal() {
            deleteModal.classList.add('opacity-0'); deleteModal.children[0].classList.add('scale-95');
            setTimeout(() => { deleteModal.classList.add('hidden'); deleteModal.classList.remove('flex'); }, 300);
        }
    </script>
    @endpush
</x-admin.layout.app>