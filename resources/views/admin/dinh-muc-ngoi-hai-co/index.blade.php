<x-admin.layout.app title="Định Mức Ngói Hài Cổ" breadcrumb="Admin › DS Sản phẩm chi tiết › Ngói Hài Cổ › Định Mức">
    
    @if ($errors->any())
        <div class="mb-6 flex items-start gap-3 px-4 py-3 rounded-lg text-sm text-red-800 bg-red-50 border border-red-200 shadow-sm">
            <svg class="w-5 h-5 flex-shrink-0 text-red-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div>
                <strong class="font-semibold block mb-1">Vui lòng kiểm tra lại:</strong>
                <ul class="list-disc ml-4 text-red-700">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- FORM THÊM MỚI -->
    <div class="mb-8 p-6 bg-blue-50/40 rounded-xl border border-blue-100 shadow-[0_0_15px_rgba(59,130,246,0.03)] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-bl-full -z-10"></div>
        <h3 class="text-sm font-bold text-blue-800 mb-5 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> 
            THÊM ĐỊNH MỨC MỚI
        </h3>
        <form action="{{ route('admin.dinh-muc-ngoi-hai-co.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 items-end">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Loại Mái <span class="text-red-500">*</span></label>
                    <input type="text" name="roof_type" required placeholder="VD: Mái Bê Tông" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Trên mái gỗ (Viên/m²) <span class="text-red-500">*</span></label>
                    <input type="number" name="ngoi_tren_mai_go" required min="0" placeholder="0" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Trên mái bê tông (Viên/m²) <span class="text-red-500">*</span></label>
                    <input type="number" name="ngoi_tren_mai_be_tong" required min="0" placeholder="0" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                </div>
                <div class="h-[42px]">
                    <button type="submit" class="w-full h-full text-sm font-bold text-white rounded-lg shadow-sm transition-colors flex items-center justify-center gap-2" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Lưu Định Mức
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- BẢNG DANH SÁCH -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Bảng Định Mức Hiện Tại</h2>
            <span class="text-xs text-gray-500 font-medium">Tổng cộng: {{ count($dinhMucs) }} bản ghi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Loại Mái</th>
                        <th class="px-6 py-4 font-semibold text-center">Trên mái Gỗ (Viên/m²)</th>
                        <th class="px-6 py-4 font-semibold text-center">Trên mái Bê Tông (Viên/m²)</th>
                        <th class="px-6 py-4 font-semibold text-right w-24">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($dinhMucs as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $item->roof_type }}</td>
                            <td class="px-6 py-4 text-center font-bold text-gray-700">{{ $item->ngoi_tren_mai_go }}</td>
                            <td class="px-6 py-4 text-center font-bold text-gray-700">{{ $item->ngoi_tren_mai_be_tong }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button type="button" title="Sửa định mức"
                                        data-roof="{{ $item->roof_type }}" 
                                        data-go="{{ $item->ngoi_tren_mai_go }}" 
                                        data-betong="{{ $item->ngoi_tren_mai_be_tong }}"
                                        onclick="openEditModal(this, '{{ route('admin.dinh-muc-ngoi-hai-co.update', $item->dinh_muc_ngoi_hai_co_id) }}')" 
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button type="button" title="Xóa định mức"
                                        onclick="openDeleteModal('{{ route('admin.dinh-muc-ngoi-hai-co.destroy', $item->dinh_muc_ngoi_hai_co_id) }}')" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">Chưa có định mức nào. Hãy thêm mới ngay!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Sửa Định Mức
                </h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-6">
                @csrf @method('PUT')
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Loại Mái <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_roof" name="roof_type" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Trên mái gỗ <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_go" name="ngoi_tren_mai_go" required min="0" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Trên mái bê tông <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_betong" name="ngoi_tren_mai_be_tong" required min="0" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-5 border-t border-gray-100">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy bỏ</button>
                    <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors">Lưu cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DELETE -->
    <div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Xác nhận xóa?</h3>
            <p class="text-sm text-gray-500 mb-6">Định mức này sẽ bị xóa vĩnh viễn khỏi hệ thống. Bạn không thể hoàn tác.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm transition-colors">Có, Xóa ngay</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const editModal = document.getElementById('editModal');
        const editModalInner = editModal.querySelector('.bg-white');

        function openEditModal(btn, actionUrl) {
            document.getElementById('editForm').action = actionUrl;
            
            // Lấy data từ nút bấm gán vào input
            document.getElementById('edit_roof').value = btn.getAttribute('data-roof');
            document.getElementById('edit_go').value = btn.getAttribute('data-go');
            document.getElementById('edit_betong').value = btn.getAttribute('data-betong');
            
            editModal.classList.remove('hidden'); 
            editModal.classList.add('flex');
            void editModal.offsetWidth; // Trigger reflow
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
            void deleteModal.offsetWidth; // Trigger reflow
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