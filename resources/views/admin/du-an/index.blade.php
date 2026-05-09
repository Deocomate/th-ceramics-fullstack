<x-admin.layout.app title="Dự Án" breadcrumb="Admin › Cấu hình trang đơn › Dự Án">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-sm font-semibold text-gray-700">Danh sách Dự Án</h2>
            <p class="text-xs text-gray-400 mt-0.5">Tổng cộng {{ $duAns->total() }} dự án</p>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- BỘ LỌC DANH MỤC -->
            <form method="GET" action="{{ route('admin.du-an.index') }}" class="flex items-center">
                <select name="danh_muc_id" onchange="this.form.submit()" class="px-3 py-2 text-sm border border-gray-300 rounded-lg outline-none focus:border-[#A31D1D] bg-white">
                    <option value="">Tất cả danh mục</option>
                    @foreach($danhMucs as $dm)
                        <option value="{{ $dm->danh_muc_du_an_id }}" {{ $danhMucId == $dm->danh_muc_du_an_id ? 'selected' : '' }}>
                            {{ $dm->ten_danh_muc }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('admin.du-an.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg transition-colors duration-200" style="background:#A31D1D;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Thêm dự án
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Dự án</th>
                        <th class="px-6 py-4 font-semibold">Danh mục</th>
                        <th class="px-6 py-4 font-semibold">Địa điểm / Sản phẩm</th>
                        <th class="px-6 py-4 font-semibold text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($duAns as $product)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                        @if(is_array($product->images) && count($product->images) > 0)
                                            <img src="{{ asset('storage/' . $product->images[0]) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800 text-sm mb-1">{{ $product->ten_du_an }}</div>
                                        <div class="text-xs text-gray-500 font-mono">Slug: {{ $product->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-[#A31D1D]">
                                {{ $product->danhMuc->ten_danh_muc ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800 mb-1">📍 {{ $product->dia_diem }} ({{ $product->nam ?? 'N/A' }})</div>
                                <div class="text-xs text-gray-500">🧱 SP: {{ $product->san_pham }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.du-an.edit', $product->du_an_id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Sửa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.du-an.destroy', $product->du_an_id) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn dự án này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">Chưa có dự án nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- PHÂN TRANG --}}
        @if($duAns->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $duAns->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-admin.layout.app>