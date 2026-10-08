<x-client.layouts.main title="Tin tức" data-page="news" main-class="bg-neutral-2" hide-newsletter>

    {{-- Nội dung chính của trang Tin tức --}}
    <x-client.content.news.hero />
    <x-client.content.news.main-content-list
        :category-id="$categoryId ?? null"
        :current-category="$currentCategory ?? null"
        :news="$news ?? null"
        :categories-with-news="$categoriesWithNews ?? null"
    />
    <x-client.content.news.history-related
        :recent-articles="$recentArticles ?? null"
        :recent-products="$recentProducts ?? null"
    />
    <x-client.content.shared.newsletter />
</x-client.layouts.main>
