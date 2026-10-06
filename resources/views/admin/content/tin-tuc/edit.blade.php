@section('preview_url', route('client.news.detail', $tinTuc->slug))

<x-admin.layouts.app title="Sửa Bài Viết" breadcrumb="Admin › Tin Tức › Sửa Bài">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @include('admin.content.tin-tuc.partials.form', ['tinTuc' => $tinTuc, 'danhMucs' => $danhMucs])
</x-admin.layouts.app>
