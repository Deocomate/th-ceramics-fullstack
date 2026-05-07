<x-layouts.client title="Chi tiết tin tức" data-page="news-detail" main-class="bg-background-secondary pb-14 lg:pb-20">

    {{-- NỘI DUNG TRANG CHI TIẾT TIN TỨC --}}
    @include('clients.news.partials.detail-hero')
    @include('clients.news.partials.detail-content')
    @include('clients.news.partials.catalog-section')
    @include('clients.news.partials.author-section')
    @include('clients.news.partials.detail-related')
    <x-products.faq2 />

</x-layouts.client>