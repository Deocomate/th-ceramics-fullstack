@section('preview_url', route('client.products.linh-vat-phong-thuy.detail', $product->linh_vat_phong_thuy_ct_id))

<x-admin.layouts.app title="Cập nhật Linh Vật" breadcrumb="Admin › DS Sản phẩm chi tiết › Chỉnh sửa">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cập nhật Sản Phẩm: {{ $product->name }}</h2>
        </div>
        <form method="POST" action="{{ route('admin.linh-vat-phong-thuy-ct.update', $product->linh_vat_phong_thuy_ct_id) }}" enctype="multipart/form-data" class="p-6">
            @csrf @method('PUT')

            @include('admin.catalog.linh-vat-phong-thuy-ct.partials.form', [
                'product' => $product,
                'destroyUrl' => route('admin.linh-vat-phong-thuy-ct.image.destroy', $product->linh_vat_phong_thuy_ct_id),
                'uploadUrl' => route('admin.linh-vat-phong-thuy-ct.image.store', $product->linh_vat_phong_thuy_ct_id),
                'reorderUrl' => route('admin.linh-vat-phong-thuy-ct.gallery.reorder', $product->linh_vat_phong_thuy_ct_id),
            ])

            <div class="pt-6 mt-8 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.linh-vat-phong-thuy-ct.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Hủy bỏ</a>
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm" style="background:#A31D1D;">Lưu Thay Đổi</button>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
