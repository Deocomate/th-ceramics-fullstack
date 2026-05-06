<x-admin.layout.app title="Sản phẩm: Gạch Hoa Thông Gió" breadcrumb="Admin › DS Sản phẩm chi tiết › Gạch Hoa Thông Gió">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-sm font-semibold text-gray-700">Danh sách sản phẩm</h2>
            <p class="text-xs text-gray-400 mt-0.5">Tổng cộng {{ count($products) }} sản phẩm</p>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- BỘ LỌC TRẠNG THÁI -->
            <form method="GET" action="{{ route('admin.gach-hoa-thong-gio-ct.index') }}" class="flex items-center">
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 text-sm border border-gray-300 rounded-lg outline-none focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] bg-white cursor-pointer">
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Sản phẩm đang bán</option>
                    <option value="deleted" {{ $status === 'deleted' ? 'selected' : '' }}>Sản phẩm đã ẩn</option>
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Tất cả</option>
                </select>
            </form>

            <a href="{{ route('admin.gach-hoa-thong-gio-ct.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg transition-colors duration-200" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Thêm sản phẩm
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Sản phẩm</th>
                        <th class="px-6 py-4 font-semibold">Mã sản phẩm</th>
                        <th class="px-6 py-4 font-semibold">Giá / Kích thước</th>
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
                                        @if(is_array($product->images) && count($product->images) > 0)
                                            <img src="{{ asset('storage/' . $product->images[0]) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800 text-sm mb-1 {{ $product->is_delete ? 'text-gray-400 line-through' : '' }}">{{ $product->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-700 border border-gray-200 rounded text-xs font-bold tracking-wide">
                                    {{ $product->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-[#A31D1D] mb-1 {{ $product->is_delete ? 'text-gray-400 line-through' : '' }}">{{ number_format($product->price, 0, ',', '.') }} VNĐ</div>
                                <div class="text-xs text-gray-500">Size: {{ $product->size ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($product->is_delete)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-700">Đã ẩn</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-700">Đang bán</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    @if(!$product->is_delete)
                                        <a href="{{ route('admin.gach-hoa-thong-gio-ct.edit', $product->gach_hoa_thong_gio_ct_id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Sửa">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <button type="button" onclick="openDeleteModal('{{ route('admin.gach-hoa-thong-gio-ct.destroy', $product->gach_hoa_thong_gio_ct_id) }}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Ẩn/Xóa">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @else
                                        <!-- Nút Khôi phục -->
                                        <button type="button" onclick="openRestoreModal('{{ route('admin.gach-hoa-thong-gio-ct.restore', $product->gach_hoa_thong_gio_ct_id) }}')" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 hover:bg-green-100 rounded-lg transition-colors" title="Khôi phục">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                            Khôi phục
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">Chưa có sản phẩm nào. Hãy thêm mới ngay!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL XÓA SẢN PHẨM --}}
    <div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Tạm ẩn sản phẩm?</h3>
            <p class="text-sm text-gray-500 mb-6">Sản phẩm sẽ được đưa vào danh sách đã ẩn. Bạn vẫn có thể khôi phục lại bất cứ lúc nào.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Đồng ý</button>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL KHÔI PHỤC SẢN PHẨM --}}
    <div id="restoreModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Khôi phục sản phẩm?</h3>
            <p class="text-sm text-gray-500 mb-6">Sản phẩm này sẽ xuất hiện lại trên Website.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeRestoreModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="restoreForm" method="POST" class="flex-1">
                    @csrf @method('PUT')
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors shadow-sm">Khôi phục</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const deleteModal = document.getElementById('deleteModal');
        const deleteModalInner = deleteModal.querySelector('.bg-white');
        function openDeleteModal(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            deleteModal.classList.remove('hidden'); deleteModal.classList.add('flex');
            void deleteModal.offsetWidth;
            deleteModal.classList.remove('opacity-0'); deleteModalInner.classList.remove('scale-95');
        }
        function closeDeleteModal() {
            deleteModal.classList.add('opacity-0'); deleteModalInner.classList.add('scale-95');
            setTimeout(() => { deleteModal.classList.add('hidden'); deleteModal.classList.remove('flex'); }, 300);
        }

        const restoreModal = document.getElementById('restoreModal');
        const restoreModalInner = restoreModal.querySelector('.bg-white');
        function openRestoreModal(actionUrl) {
            document.getElementById('restoreForm').action = actionUrl;
            restoreModal.classList.remove('hidden'); restoreModal.classList.add('flex');
            void restoreModal.offsetWidth;
            restoreModal.classList.remove('opacity-0'); restoreModalInner.classList.remove('scale-95');
        }
        function closeRestoreModal() {
            restoreModal.classList.add('opacity-0'); restoreModalInner.classList.add('scale-95');
            setTimeout(() => { restoreModal.classList.add('hidden'); restoreModal.classList.remove('flex'); }, 300);
        }
    </script>
    @endpush
</x-admin.layout.app>