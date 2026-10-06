<x-admin.layouts.app title="Thêm mã giảm giá" breadcrumb="Admin › Mã giảm giá › Thêm mới">

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Thông tin mã giảm giá mới</h2>
        </div>
        <form method="POST" action="{{ route('admin.coupons.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf

            @include('admin.commerce.coupons.partials.form', ['productTypes' => $productTypes])
        </form>
    </div>

</x-admin.layouts.app>
