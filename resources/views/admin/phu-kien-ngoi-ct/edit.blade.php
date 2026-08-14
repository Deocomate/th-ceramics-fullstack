@section('preview_url', $product->category_type === \App\Models\PhuKienNgoiCt::TYPE_CHU_VAN
    ? route('client.products.phu-kien-ngoi.bo-noc-chu-van.detail', $product->phu_kien_ngoi_ct_id)
    : route('client.products.phu-kien-ngoi.ngoi-bo-noc.detail', $product->phu_kien_ngoi_ct_id))

<x-admin.layouts.app title="Cập nhật {{ $categoryLabel }}" breadcrumb="Admin › Phụ Kiện Ngói › Chỉnh sửa">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cập nhật Sản Phẩm: {{ $product->name }}</h2>
        </div>
        <form method="POST" action="{{ route('admin.phu-kien-ngoi-ct.update', $product->phu_kien_ngoi_ct_id) }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="category_type" value="{{ $categoryType }}">

            @include('admin.phu-kien-ngoi-ct.partials.form', [
                'product' => $product,
                'categoryType' => $categoryType,
                'destroyUrl' => route('admin.phu-kien-ngoi-ct.image.destroy', $product->phu_kien_ngoi_ct_id),
                'uploadUrl' => route('admin.phu-kien-ngoi-ct.image.store', $product->phu_kien_ngoi_ct_id),
                'reorderUrl' => route('admin.phu-kien-ngoi-ct.gallery.reorder', $product->phu_kien_ngoi_ct_id),
            ])

            <div class="pt-6 mt-8 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.phu-kien-ngoi-ct.index', ['category_type' => $categoryType]) }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy bỏ</a>
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;">Lưu Thay Đổi</button>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
