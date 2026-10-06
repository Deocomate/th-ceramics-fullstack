<x-admin.layouts.app title="Thêm Bài Viết Mới" breadcrumb="Admin › Tin Tức › Viết Bài">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @include('admin.content.tin-tuc.partials.form', ['tinTuc' => null, 'danhMucs' => $danhMucs])
</x-admin.layouts.app>