<x-admin.layouts.app title="Sản phẩm: {{ $categoryLabel }}" breadcrumb="Admin › Phụ Kiện Ngói › {{ $categoryLabel }}">
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
        @include('admin.catalog.partials.product-list-search')
        @include('admin.catalog.partials.bulk-rename-products', ['type' => 'phu-kien-ngoi-ct', 'categoryType' => $categoryType])
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                <tr>
                        <th class="w-12 px-3 py-4 text-center"><input type="checkbox" data-bulk-rename-select-all aria-label="Chọn tất cả sản phẩm" class="block mx-auto h-4 w-4 rounded border-gray-300 text-[#A31D1D] focus:ring-[#A31D1D]"></th>
                    <th class="px-6 py-4 font-semibold">Tên sản phẩm</th>
                    <th class="px-6 py-4 font-semibold text-center">Kích thước</th>
                    <th class="px-6 py-4 font-semibold text-center">Phân loại</th>
                    <th class="px-6 py-4 font-semibold text-center">Trạng thái</th>
                    <th class="px-6 py-4 font-semibold text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" data-admin-product-sortable data-priority-endpoint="{{ route('admin.products.priority.update', 'phu-kien-ngoi-ct') }}" data-can-reorder="{{ $status === 'active' ? 'true' : 'false' }}" data-category-type="{{ $categoryType }}">
                @forelse($products as $product)
                        <tr data-admin-product-search-row data-search-text="{{ $product->name }} {{ $product->code ?? '' }}" data-priority-id="{{ $product->phu_kien_ngoi_ct_id }}" class="hover:bg-gray-50/50 transition-colors {{ $product->is_delete ? 'bg-red-50/30' : '' }}">
                            <td class="w-12 px-3 py-4 text-center align-middle"><input type="checkbox" class="bulk-rename-product-checkbox block mx-auto h-4 w-4 rounded border-gray-300 text-[#A31D1D] focus:ring-[#A31D1D]" value="{{ $product->phu_kien_ngoi_ct_id }}" aria-label="Chọn {{ $product->name }}"></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <x-admin.catalog.shared.product-thumbnail :images="$product->images" :is-delete="$product->is_delete" :alt="$product->name" />
                                <div>
                                    <div class="font-bold text-gray-800 text-sm mb-1 {{ $product->is_delete ? 'text-gray-400 line-through' : '' }}">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-400">{{ \App\Domains\Catalog\Domain\PhuKienNgoiCategory::codePrefix($product->category_type) }}{{ $product->phu_kien_ngoi_ct_id }}</div>
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
                                    <a href="{{ route('admin.phu-kien-ngoi-ct.create', ['copy_from' => $product->phu_kien_ngoi_ct_id, 'category_type' => $categoryType]) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Nhân bản / Sao chép sản phẩm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    </a>
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
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Chưa có sản phẩm nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-admin.catalog.shared.delete-restore-modal
        delete-title="Tạm ẩn sản phẩm?"
        restore-message="Sản phẩm sẽ được mở lại. Các phân loại con cần được mở lại riêng nếu đang bị ẩn.">
        <x-slot:deleteAlert>
            <p class="mb-6 text-sm text-red-600 bg-red-50 p-3 rounded-lg border border-red-200">Các phân loại thuộc sản phẩm này cũng sẽ bị ẩn khỏi website.</p>
        </x-slot:deleteAlert>
    </x-admin.catalog.shared.delete-restore-modal>
</x-admin.layouts.app>
