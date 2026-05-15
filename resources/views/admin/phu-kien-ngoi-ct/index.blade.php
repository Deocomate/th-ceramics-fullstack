<x-admin.layout.app title="Sản phẩm: {{ $categoryLabel }}" breadcrumb="Admin › Phụ Kiện Ngói › {{ $categoryLabel }}">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-sm font-semibold text-gray-700">Danh sách {{ $categoryLabel }}</h2>
            <p class="text-xs text-gray-400 mt-0.5">Tổng cộng {{ count($products) }} sản phẩm</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.phu-kien-ngoi-ct.index') }}" class="flex items-center">
                <input type="hidden" name="category_type" value="{{ $categoryType }}">
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 text-sm border border-gray-300 rounded-lg outline-none focus:border-[#A31D1D] bg-white cursor-pointer">
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Sản phẩm đang bán</option>
                    <option value="deleted" {{ $status === 'deleted' ? 'selected' : '' }}>Sản phẩm đã ẩn</option>
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Tất cả</option>
                </select>
            </form>
            <a href="{{ route('admin.phu-kien-ngoi-ct.create', ['category_type' => $categoryType]) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg transition-colors duration-200" style="background:#A31D1D;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Thêm sản phẩm
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-semibold">Tên sản phẩm</th>
                    <th class="px-6 py-4 font-semibold text-center">Kích thước</th>
                    <th class="px-6 py-4 font-semibold text-center">Phân loại</th>
                    <th class="px-6 py-4 font-semibold text-center">Trạng thái</th>
                    <th class="px-6 py-4 font-semibold text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors {{ $product->is_delete ? 'bg-red-50/30' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0 {{ $product->is_delete ? 'opacity-50 grayscale' : '' }}">
                                    @php $thumb = is_array($product->images) ? ($product->images[0] ?? null) : null; @endphp
                                    @if($thumb)
                                        <img src="{{ \App\Support\AssetPath::url($thumb) }}" class="w-full h-full object-contain" alt="{{ $product->name }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-gray-800 text-sm mb-1 {{ $product->is_delete ? 'text-gray-400 line-through' : '' }}">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-400">{{ \App\Models\PhuKienNgoiCt::categoryCodePrefix($product->category_type) }}{{ $product->phu_kien_ngoi_ct_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center font-medium text-gray-600">{{ $product->size ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if(!$product->is_delete)
                                <a href="{{ route('admin.phan-loai-phu-kien-ngoi-ct.index', ['category_type' => $categoryType, 'product_id' => $product->phu_kien_ngoi_ct_id]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 border border-purple-200 rounded-lg text-xs font-bold hover:bg-purple-100 transition-colors shadow-sm">
                                    Quản lý {{ $product->phan_loais_count ?? 0 }} phân loại
                                </a>
                            @else
                                <span class="text-xs text-gray-400">Không khả dụng</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($product->is_delete)
                                <span class="px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-700">Đã ẩn</span>
                            @else
                                <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-700">Đang bán</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                @if(!$product->is_delete)
                                    <a href="{{ route('admin.phu-kien-ngoi-ct.edit', $product->phu_kien_ngoi_ct_id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Sửa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button type="button" onclick="openDeleteModal('{{ route('admin.phu-kien-ngoi-ct.destroy', $product->phu_kien_ngoi_ct_id) }}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Ẩn/Xóa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @else
                                    <button type="button" onclick="openRestoreModal('{{ route('admin.phu-kien-ngoi-ct.restore', $product->phu_kien_ngoi_ct_id) }}')" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 hover:bg-green-100 rounded-lg transition-colors">
                                        Khôi phục
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Chưa có sản phẩm nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Tạm ẩn sản phẩm?</h3>
            <p class="mb-6 text-sm text-red-600 bg-red-50 p-3 rounded-lg border border-red-200">Các phân loại thuộc sản phẩm này cũng sẽ bị ẩn khỏi website.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="deleteForm" method="POST" class="flex-1">@csrf @method('DELETE')<button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Đồng ý ẩn</button></form>
            </div>
        </div>
    </div>

    <div id="restoreModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Khôi phục sản phẩm?</h3>
            <p class="text-sm text-gray-500 mb-6">Sản phẩm sẽ được mở lại. Các phân loại con cần được mở lại riêng nếu đang bị ẩn.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeRestoreModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="restoreForm" method="POST" class="flex-1">@csrf @method('PUT')<button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors shadow-sm">Khôi phục</button></form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openModal(modalId, formId, actionUrl) {
            document.getElementById(formId).action = actionUrl;
            const modal = document.getElementById(modalId);
            const inner = modal.querySelector('.bg-white');
            modal.classList.remove('hidden'); modal.classList.add('flex');
            void modal.offsetWidth;
            modal.classList.remove('opacity-0'); inner.classList.remove('scale-95');
        }
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            const inner = modal.querySelector('.bg-white');
            modal.classList.add('opacity-0'); inner.classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
        }
        function openDeleteModal(actionUrl) { openModal('deleteModal', 'deleteForm', actionUrl); }
        function closeDeleteModal() { closeModal('deleteModal'); }
        function openRestoreModal(actionUrl) { openModal('restoreModal', 'restoreForm', actionUrl); }
        function closeRestoreModal() { closeModal('restoreModal'); }
    </script>
    @endpush
</x-admin.layout.app>
