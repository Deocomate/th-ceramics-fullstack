<x-client.layouts.main title="Tin tức" data-page="news" main-class="bg-neutral-2" hide-newsletter>

    {{-- Nội dung chính của trang Tin tức --}}
    <x-client.news.hero />
    <x-client.news.main-content-list
        :category-id="$categoryId ?? null"
        :current-category="$currentCategory ?? null"
        :news="$news ?? null"
        :categories-with-news="$categoriesWithNews ?? null"
    />
    <x-client.news.history-related
        :recent-articles="$recentArticles ?? null"
        :recent-products="$recentProducts ?? null"
    />
    <x-client.shared.newsletter />
</x-client.layouts.main>
