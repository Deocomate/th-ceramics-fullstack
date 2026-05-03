<x-admin.layout.app title="Định Mức Gạch Cổ Bát Tràng" breadcrumb="Admin › DS Sản phẩm chi tiết › Gạch Cổ Bát Tràng › Định Mức">
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

    <!-- FORM THÊM MỚI -->
    <div class="mb-8 p-6 bg-blue-50/30 rounded-xl border border-blue-100 shadow-sm">
        <h3 class="text-sm font-bold text-blue-800 mb-5 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> 
            THÊM ĐỊNH MỨC MỚI
        </h3>
        <form action="{{ route('admin.dinh-muc-gach-co-bat-trang.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-5 items-end">
                <div class="md:col-span-2 lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Loại Gạch <span class="text-red-500">*</span></label>
                    <input type="text" name="brick_type" required placeholder="VD: Gạch Cổ Bát Tràng Vuông" class="w-full px-3 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Số lượng (Viên/m²)</label>
                    <input type="number" name="value" min="0" placeholder="VD: 25" class="w-full px-3 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                </div>
                <div class="h-[42px]">
                    <button type="submit" class="w-full h-full text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                        Lưu Định Mức
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- DANH SÁCH BẢNG -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Bảng Định Mức Hiện Tại</h2>
            <span class="text-xs text-gray-500">Tổng cộng: {{ count($dinhMucs) }} bản ghi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold w-1/2">Loại Gạch</th>
                        <th class="px-6 py-4 font-semibold text-center">Số lượng (Viên/m²)</th>
                        <th class="px-6 py-4 font-semibold text-right w-32">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($dinhMucs as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $item->brick_type }}</td>
                            <td class="px-6 py-4 text-center font-bold text-blue-700">{{ $item->value ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button type="button" 
                                        data-type="{{ $item->brick_type }}" data-value="{{ $item->value }}"
                                        onclick="openEditModal(this, '{{ route('admin.dinh-muc-gach-co-bat-trang.update', $item->dinh_muc_gach_co_bat_trang_id) }}')" 
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button type="button" 
                                        onclick="openDeleteModal('{{ route('admin.dinh-muc-gach-co-bat-trang.destroy', $item->dinh_muc_gach_co_bat_trang_id) }}')" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">Chưa có định mức nào. Hãy thêm mới ngay!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Sửa Định Mức</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-6">
                @csrf @method('PUT')
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Loại Gạch <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_type" name="brick_type" required class="w-full px-3 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 outline-none">
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Số lượng (Viên/m²)</label>
                    <input type="number" id="edit_value" name="value" min="0" class="w-full px-3 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 outline-none">
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
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
        const editModal = document.getElementById('editModal');
        function openEditModal(btn, actionUrl) {
            document.getElementById('editForm').action = actionUrl;
            document.getElementById('edit_type').value = btn.getAttribute('data-type');
            document.getElementById('edit_value').value = btn.getAttribute('data-value');
            
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