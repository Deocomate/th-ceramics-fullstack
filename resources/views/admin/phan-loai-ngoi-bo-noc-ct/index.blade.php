<x-admin.layout.app title="Phân Loại Ngói Bò Nóc" breadcrumb="Admin › DS Sản phẩm chi tiết › Ngói Bò Nóc › Phân Loại">
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

    {{-- HEADER & BỘ LỌC --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            @if(isset($selectedProduct))
                <a href="{{ route('admin.ngoi-bo-noc-ct.index') }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-[#A31D1D] hover:bg-red-50 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h2 class="text-sm font-semibold text-gray-500">Quản lý Phân loại của:</h2>
                    <p class="text-[#A31D1D] text-xl font-bold uppercase tracking-wide mt-0.5">{{ $selectedProduct->name }}</p>
                </div>
            @else
                <div>
                    <h2 class="text-sm font-semibold text-gray-700">Tất cả Biến thể Phân loại</h2>
                </div>
            @endif
        </div>
        <form method="GET" action="{{ route('admin.phan-loai-ngoi-bo-noc-ct.index') }}" class="flex items-center gap-2">
            @if(isset($selectedProduct)) <input type="hidden" name="product_id" value="{{ $selectedProduct->ngoi_bo_noc_ct_id }}"> @endif
            <select name="status" onchange="this.form.submit()" class="px-4 py-2.5 text-sm font-medium border border-gray-300 rounded-lg outline-none focus:border-[#A31D1D]">
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Đang bán</option>
                <option value="deleted" {{ $status === 'deleted' ? 'selected' : '' }}>Đã ẩn</option>
            </select>
        </form>
    </div>

    {{-- FORM THÊM MỚI --}}
    <div class="mb-8 p-6 bg-blue-50/40 rounded-xl border border-blue-100 shadow-sm relative overflow-hidden">
        <h3 class="text-sm font-bold text-blue-800 mb-5">THÊM PHÂN LOẠI MỚI</h3>
        <form action="{{ route('admin.phan-loai-ngoi-bo-noc-ct.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 items-end">
                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sản phẩm cha <span class="text-red-500">*</span></label>
                    @if(isset($selectedProduct))
                        <input type="hidden" name="ngoi_bo_noc_ct_id" value="{{ $selectedProduct->ngoi_bo_noc_ct_id }}">
                        <select disabled class="w-full px-4 py-2.5 text-sm font-medium border rounded-lg border-gray-300 bg-gray-100 text-gray-500">
                            <option selected>{{ $selectedProduct->name }}</option>
                        </select>
                    @else
                        <select name="ngoi_bo_noc_ct_id" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] bg-white">
                            <option value="">-- Chọn sản phẩm --</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->ngoi_bo_noc_ct_id }}" {{ old('ngoi_bo_noc_ct_id') == $prod->ngoi_bo_noc_ct_id ? 'selected' : '' }}>{{ $prod->name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tên phân loại <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="VD: Tráng Men" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mã Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" required placeholder="VD: NBN-TM-01" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] font-mono">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Giá tiền (VNĐ) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price') }}" required min="0" placeholder="VD: 5500" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D]">
                </div>
            </div>
            <div class="flex justify-end mt-6 pt-5 border-t border-blue-100/50">
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm" style="background:#A31D1D;">Lưu Phân Loại</button>
            </div>
        </form>
    </div>

    {{-- BẢNG DANH SÁCH --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-semibold w-1/4">Sản Phẩm Cha</th>
                    <th class="px-6 py-4 font-semibold">Tên Phân Loại</th>
                    <th class="px-6 py-4 font-semibold">Mã Code</th>
                    <th class="px-6 py-4 font-semibold">Giá bán</th>
                    <th class="px-6 py-4 font-semibold text-center">Trạng thái</th>
                    <th class="px-6 py-4 font-semibold text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($phanLoais as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors {{ $item->is_delete ? 'bg-red-50/30' : '' }}">
                        <td class="px-6 py-4"><span class="font-medium text-gray-700">{{ $item->product->name ?? 'Không xác định' }}</span></td>
                        <td class="px-6 py-4 font-bold text-gray-800">{{ $item->name }}</td>
                        <td class="px-6 py-4"><span class="px-2.5 py-1 bg-gray-100 border border-gray-200 rounded text-xs font-bold font-mono">{{ $item->code }}</span></td>
                        <td class="px-6 py-4 font-bold text-[#A31D1D]">{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                        <td class="px-6 py-4 text-center">
                            @if($item->is_delete) <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">Đã ẩn</span>
                            @else <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Đang bán</span> @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                @if(!$item->is_delete)
                                    <button type="button" data-id="{{ $item->phan_loai_ngoi_bo_noc_ct_id }}" data-pid="{{ $item->ngoi_bo_noc_ct_id }}" data-name="{{ $item->name }}" data-code="{{ $item->code }}" data-price="{{ $item->price }}" onclick="openEditModal(this, '{{ route('admin.phan-loai-ngoi-bo-noc-ct.update', $item->phan_loai_ngoi_bo_noc_ct_id) }}')" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button type="button" onclick="openDeleteModal('{{ route('admin.phan-loai-ngoi-bo-noc-ct.destroy', $item->phan_loai_ngoi_bo_noc_ct_id) }}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                @else
                                    <form method="POST" action="{{ route('admin.phan-loai-ngoi-bo-noc-ct.restore', $item->phan_loai_ngoi_bo_noc_ct_id) }}" class="inline">
                                        @csrf @method('PUT')
                                        <button class="px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 rounded-lg">Khôi phục</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-12 text-gray-500">Chưa có phân loại nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL EDIT --}}
    <div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Sửa Phân Loại</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500">X</button>
            </div>
            <form id="editForm" method="POST" class="p-6">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sản phẩm cha</label>
                        <input type="hidden" name="ngoi_bo_noc_ct_id" id="edit_pid_hidden">
                        <select id="edit_pid" disabled class="w-full px-4 py-2.5 text-sm bg-gray-100 text-gray-500 rounded-lg">
                            @foreach($products as $prod) <option value="{{ $prod->ngoi_bo_noc_ct_id }}">{{ $prod->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tên phân loại <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_name" name="name" required class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Mã Code <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_code" name="code" required class="w-full px-4 py-2.5 text-sm border rounded-lg font-mono focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Giá bán <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_price" name="price" required min="0" class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-blue-500">
                    </div>
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
            <h3 class="text-xl font-bold text-gray-800 mb-2">Tạm ẩn phân loại?</h3>
            <p class="text-sm text-gray-500 mb-6">Bạn có thể khôi phục lại bất cứ lúc nào.</p>
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
            document.getElementById('edit_pid').value = btn.getAttribute('data-pid');
            document.getElementById('edit_pid_hidden').value = btn.getAttribute('data-pid');
            document.getElementById('edit_name').value = btn.getAttribute('data-name');
            document.getElementById('edit_code').value = btn.getAttribute('data-code');
            document.getElementById('edit_price').value = btn.getAttribute('data-price');
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
</x-admin.layout.app>