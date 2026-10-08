@section('preview_url', route('client.products.gach-trang-tri.detail', $product->gach_trang_tri_ct_id))

<x-admin.layouts.app title="Cập nhật Gạch Trang Trí" breadcrumb="Admin › DS Sản phẩm chi tiết › Chỉnh sửa">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cập nhật Sản Phẩm: {{ $product->name }}</h2>
            <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-md text-xs font-bold">{{ $product->code }}</span>
        </div>
        <form method="POST" action="{{ route('admin.gach-trang-tri-ct.update', $product->gach_trang_tri_ct_id) }}" enctype="multipart/form-data" class="p-6">
            @csrf @method('PUT')

            @include('admin.catalog.gach-trang-tri-ct.partials.form', [
                'product' => $product,
                'destroyUrl' => route('admin.gach-trang-tri-ct.image.destroy', $product->gach_trang_tri_ct_id),
                'uploadUrl' => route('admin.gach-trang-tri-ct.image.store', $product->gach_trang_tri_ct_id),
                'reorderUrl' => route('admin.gach-trang-tri-ct.gallery.reorder', $product->gach_trang_tri_ct_id),
            ])

            <div class="pt-6 mt-8 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.gach-trang-tri-ct.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy bỏ</a>
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                    Lưu Thay Đổi
                </button>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
