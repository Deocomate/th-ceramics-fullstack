@props([
    'trangChu' => null,
    'ngoiAmDuongs' => null,
    'ngoiHais' => null,
    'gachHoas' => null,
    'about' => null,
    'factory' => null,
    'showroomImages' => null,
    'showroomContent' => null,
    'news' => null,
    'article' => null,
    'articles' => null,
    'relatedArticles' => null,
    'historyArticles' => null,
    'projects' => null,
    'project' => null,
    'relatedProjects' => null,
    'categories' => null,
    'categoriesWithNews' => null,
    'categoryId' => null,
    'selectedCategory' => null,
    'currentCategory' => null,
    'config' => null,
    'products' => null,
    'relatedProducts' => null,
    'product' => null,
    'colors' => null,
    'dinhMuc' => null,
    'giaTriVuotTroi' => null,
    'parentConfig' => null,
    'pageLabel' => null,
    'indexRouteName' => null,
    'categoryType' => null,
    'categoryLabel' => null,
    'denGomProducts' => null,
    'denSuProducts' => null,
    'featuredProducts' => null,
    'collectionProducts' => null,
    'ngheProducts' => null,
    'linhVatProducts' => null,
    'bgImage' => null,
    'activeOrder' => false,
    'activeAccount' => false,
    'activeCatalog' => false,
    'activeGuide' => false,
    'activeProcess' => false,
    'activePrivacy' => false,
    'activeReturn' => false,
    'activeShipping' => false,
    'image' => null,
    'label1' => null,
    'rate1' => null,
    'label2' => null,
    'rate2' => null,
    'sectionId' => null,
    'sectionClass' => null,
    'sectionTitle' => null,
    'desktopLinkHref' => null,
    'detailRouteName' => null,
    'wrapperClass' => null,
    'titleClass' => null,
    'title' => null,
    'subtitle' => null,
    'description' => null,
    'items' => null,])
<section class="py-16 md:py-24 bg-neutral-2">
  <div class="w-[85%] max-w-[1320px] mx-auto flex flex-col gap-[60px]">
    @if (($categoryId ?? 0) > 0)
      <div class="news-category-section">
        <h2 class="text-2xl md:text-4xl font-arima font-semibold text-secondary uppercase mb-8 md:mb-12 tracking-wide" data-aos="fade-right">
          {{ $currentCategory->ten_danh_muc ?? 'Tin tức' }}
        </h2>

        <div class="max-w-[90%] flex flex-col mx-auto gap-12 md:gap-16">
          @forelse ($news as $article)
            <x-client.news.article-horizontal-card :article="$article" />
          @empty
            <p class="text-primary/60">Chưa có bài viết nào trong danh mục này.</p>
          @endforelse
        </div>

        @if ($news && method_exists($news, 'lastPage'))
          <x-client.shared.custom-pagination :paginator="$news->withQueryString()" />
        @endif
      </div>
    @else
      @forelse ($categoriesWithNews as $category)
        <div class="news-category-section">
          <h2 class="text-2xl md:text-4xl font-arima font-semibold text-secondary uppercase mb-8 md:mb-12 tracking-wide" data-aos="fade-right">
            {{ $category->ten_danh_muc }}
          </h2>

          <div class="max-w-[90%] mx-auto">
            <div class="flex flex-col gap-12 md:gap-16">
              @foreach ($category->tinTucs as $article)
                <x-client.news.article-horizontal-card :article="$article" />
              @endforeach
            </div>

            <div class="flex justify-end mt-4 md:mt-8">
              <a
                href="{{ route('client.news.index', ['category' => $category->danh_muc_tin_tuc_id]) }}"
                class="flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-primary hover:text-secondary transition-all group"
              >
                Xem thêm
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" class="transition-transform group-hover:translate-x-1" aria-hidden="true">
                  <path d="M1 7H13M13 7L7 1M13 7L7 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </a>
            </div>
          </div>
        </div>
      @empty
        <p class="text-center text-primary/60 py-6">Chưa có bài viết nào.</p>
      @endforelse
    @endif
  </div>
</section>
