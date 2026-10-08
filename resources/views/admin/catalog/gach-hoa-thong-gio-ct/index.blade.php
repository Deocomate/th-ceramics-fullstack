<x-admin.layouts.app title="Sản phẩm: Gạch Hoa Thông Gió" breadcrumb="Admin › DS Sản phẩm chi tiết › Gạch Hoa Thông Gió">

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
        @include('admin.catalog.partials.product-list-search')
        @include('admin.catalog.partials.bulk-rename-products', ['type' => 'gach-hoa-thong-gio-ct'])
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="w-12 px-3 py-4 text-center"><input type="checkbox" data-bulk-rename-select-all aria-label="Chọn tất cả sản phẩm" class="block mx-auto h-4 w-4 rounded border-gray-300 text-[#A31D1D] focus:ring-[#A31D1D]"></th>
                        <th class="px-6 py-4 font-semibold">Sản phẩm</th>
                        <th class="px-6 py-4 font-semibold">Mã sản phẩm</th>
                        <th class="px-6 py-4 font-semibold">Giá / Kích thước</th>
                        <th class="px-6 py-4 font-semibold text-center">Trạng thái</th>
                        <th class="px-6 py-4 font-semibold text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" data-admin-product-sortable data-priority-endpoint="{{ route('admin.products.priority.update', 'gach-hoa-thong-gio-ct') }}" data-can-reorder="{{ $status === 'active' ? 'true' : 'false' }}">
                    @forelse($products as $product)
                        <tr data-admin-product-search-row data-search-text="{{ $product->name }} {{ $product->code ?? '' }}" data-priority-id="{{ $product->gach_hoa_thong_gio_ct_id }}" class="hover:bg-gray-50/50 transition-colors {{ $product->is_delete ? 'bg-red-50/30' : '' }}">
                            <td class="w-12 px-3 py-4 text-center align-middle"><input type="checkbox" class="bulk-rename-product-checkbox block mx-auto h-4 w-4 rounded border-gray-300 text-[#A31D1D] focus:ring-[#A31D1D]" value="{{ $product->gach_hoa_thong_gio_ct_id }}" aria-label="Chọn {{ $product->name }}"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <x-admin.catalog.shared.product-thumbnail :images="$product->images" :is-delete="$product->is_delete" :alt="$product->name" />
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
                                        <a href="{{ route('admin.gach-hoa-thong-gio-ct.create', ['copy_from' => $product->gach_hoa_thong_gio_ct_id]) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Nhân bản / Sao chép sản phẩm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </a>
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
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">Chưa có sản phẩm nào. Hãy thêm mới ngay!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-admin.catalog.shared.delete-restore-modal />
</x-admin.layouts.app>