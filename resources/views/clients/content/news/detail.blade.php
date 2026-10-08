<x-client.layouts.main title="{{ $article->tieu_de ?? 'Chi tiết tin tức' }}" data-page="news-detail"
    main-class="bg-background-secondary">

    {{-- NỘI DUNG TRANG CHI TIẾT TIN TỨC --}}
    <x-client.content.news.detail-hero :article="$article ?? null" />
    <x-client.content.news.detail-content :article="$article ?? null" />
    <x-client.content.news.catalog-section :article="$article ?? null" />
    <x-client.content.news.detail-related :related-news="$relatedNews ?? null" />
    <x-client.content.shared.newsletter />

</x-client.layouts.main>
