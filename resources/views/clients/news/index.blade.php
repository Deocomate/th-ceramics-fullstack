<x-layouts.client title="Tin tức" data-page="news" main-class="bg-neutral-2 pb-14 lg:pb-20" hide-newsletter>

    {{-- Nội dung chính của trang Tin tức --}}
    @include('clients.news.partials.hero')
    @include('clients.news.partials.main-content', [
        'categories' => $categories ?? collect(),
        'featuredNews' => $featuredNews ?? collect(),
        'news' => $news ?? null,
    ])
    <x-products.faq2 />

</x-layouts.client>
