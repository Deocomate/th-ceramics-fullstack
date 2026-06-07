<x-admin.layouts.app title="Danh Mục Dự Án" breadcrumb="Admin › Cấu hình trang đơn › Danh Mục Dự Án">
    @if ($errors->any())
        <div class="mb-6 flex items-start gap-3 px-4 py-3 rounded-lg text-sm text-red-800 bg-red-50 border border-red-200 shadow-sm">
            <div>
                <strong class="font-semibold block mb-1">Vui lòng kiểm tra lại:</strong>
                <ul class="list-disc ml-4 text-red-700">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-sm font-semibold text-gray-700">Quản lý Danh Mục Dự Án</h2>
        </div>
        <form method="GET" action="{{ route('admin.danh-muc-du-an.index') }}" class="flex items-center gap-2">
            <select name="status" onchange="this.form.submit()" class="px-4 py-2.5 text-sm font-medium border border-gray-300 rounded-lg outline-none focus:border-[#A31D1D]">
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="deleted" {{ $status === 'deleted' ? 'selected' : '' }}>Đã ẩn</option>
            </select>
        </form>
    </div>

    <div class="mb-8 p-6 bg-blue-50/40 rounded-xl border border-blue-100 shadow-sm relative overflow-hidden">
        <h3 class="text-sm font-bold text-blue-800 mb-5">THÊM DANH MỤC MỚI</h3>
        <form action="{{ route('admin.danh-muc-du-an.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 items-end">
                <div class="md:col-span-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                    <input type="text" name="ten_danh_muc" value="{{ old('ten_danh_muc') }}" required placeholder="VD: Khách sạn - Resort" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D]">
                </div>
                <div>
                    <button type="submit" class="w-full px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm" style="background:#A31D1D;">Lưu Danh Mục</button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-semibold w-1/2">Tên Danh Mục</th>
                    <th class="px-6 py-4 font-semibold text-center">Số Dự Án Liên Kết</th>
                    <th class="px-6 py-4 font-semibold text-center">Trạng thái</th>
                    <th class="px-6 py-4 font-semibold text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($danhMucs as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors {{ $item->is_delete ? 'bg-red-50/30' : '' }}">
                        <td class="px-6 py-4 font-bold text-gray-800">{{ $item->ten_danh_muc }}</td>
                        <td class="px-6 py-4 text-center text-blue-600 font-bold">
                            <a href="{{ route('admin.du-an.index',['danh_muc_id' => $item->danh_muc_du_an_id]) }}" class="hover:underline">{{ $item->du_ans_count }} dự án</a>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->is_delete) <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">Đã ẩn</span>
                            @else <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Đang hoạt động</span> @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                @if(!$item->is_delete)
                                    <button type="button" data-name="{{ $item->ten_danh_muc }}" onclick="openEditModal(this, '{{ route('admin.danh-muc-du-an.update', $item->danh_muc_du_an_id) }}')" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button type="button" onclick="openDeleteModal('{{ route('admin.danh-muc-du-an.destroy', $item->danh_muc_du_an_id) }}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                @else
                                    <form method="POST" action="{{ route('admin.danh-muc-du-an.restore', $item->danh_muc_du_an_id) }}" class="inline">
                                        @csrf @method('PUT')
                                        <button class="px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 rounded-lg">Khôi phục</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-12 text-gray-500">Chưa có danh mục nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL EDIT --}}
    <div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Sửa Danh Mục</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500">X</button>
            </div>
            <form id="editForm" method="POST" class="p-6">
                @csrf @method('PUT')
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tên danh mục <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_name" name="ten_danh_muc" required class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-blue-500">
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-gray-100">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg">Hủy</button>
                    <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg">Lưu cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DELETE --}}
    <div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center transform scale-95 transition-transform duration-300">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Tạm ẩn danh mục?</h3>
            <p class="text-sm text-gray-500 mb-6">Bạn có thể khôi phục lại bất cứ lúc nào. Các dự án thuộc danh mục này KHÔNG bị ảnh hưởng hiển thị.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg">Hủy</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 rounded-lg">Đồng ý</button>
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
            document.getElementById('edit_name').value = btn.getAttribute('data-name');
            editModal.classList.remove('hidden'); editModal.classList.add('flex');
            setTimeout(() => { editModal.classList.remove('opacity-0'); editModalInner.classList.remove('scale-95'); }, 10);
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
            setTimeout(() => { deleteModal.classList.remove('opacity-0'); deleteModalInner.classList.remove('scale-95'); }, 10);
        }
        function closeDeleteModal() {
            deleteModal.classList.add('opacity-0'); deleteModalInner.classList.add('scale-95');
            setTimeout(() => { deleteModal.classList.add('hidden'); deleteModal.classList.remove('flex'); }, 300);
        }
    </script>
    @endpush
</x-admin.layouts.app>