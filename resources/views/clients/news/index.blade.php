<x-layouts.client title="Tin tức" data-page="news" main-class="bg-neutral-2 pb-14 lg:pb-20" hide-newsletter>

    {{-- Nội dung chính của trang Tin tức --}}
    @include('clients.news.partials.hero')
    @include('clients.news.partials.main-content')
    @include('clients.news.partials.history-related')
    <x-products.faq2 />

</x-layouts.client>