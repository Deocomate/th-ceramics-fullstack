<x-admin.layouts.app title="Thêm Dự Án" breadcrumb="Admin › Dự Án › Thêm mới">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Thông tin dự án mới</h2>
        </div>
        <form method="POST" action="{{ route('admin.du-an.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @include('admin.content.du-an.partials.form', ['danhMucs' => $danhMucs])
        </form>
    </div>
</x-admin.layouts.app>
