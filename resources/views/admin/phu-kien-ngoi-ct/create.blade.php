<x-admin.layout.app title="Thêm {{ $categoryLabel }}" breadcrumb="Admin › Phụ Kiện Ngói › Thêm mới">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Thông tin {{ $categoryLabel }} mới</h2>
        </div>
        <form method="POST" action="{{ route('admin.phu-kien-ngoi-ct.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            <input type="hidden" name="category_type" value="{{ $categoryType }}">

            @include('admin.phu-kien-ngoi-ct.partials.form', ['product' => null, 'categoryType' => $categoryType])

            <div class="pt-6 mt-8 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.phu-kien-ngoi-ct.index', ['category_type' => $categoryType]) }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</a>
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;">Lưu Sản Phẩm</button>
            </div>
        </form>
    </div>
</x-admin.layout.app>
