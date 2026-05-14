<section class="py-16 md:py-24 bg-neutral-2">
  <div class="w-[85%] max-w-[1320px] mx-auto flex flex-col gap-12 md:gap-32">
    @if (($categoryId ?? 0) > 0)
      <div class="news-category-section">
        <h2 class="text-2xl md:text-4xl font-arima font-semibold text-secondary uppercase mb-8 md:mb-12 tracking-wide" data-aos="fade-right">
          {{ $currentCategory->ten_danh_muc ?? 'Tin tức' }}
        </h2>

        <div class="max-w-[90%] flex flex-col mx-auto gap-12 md:gap-16">
          @forelse ($news as $article)
            @include('clients.news.partials.article-horizontal-card', ['article' => $article])
          @empty
            <p class="text-primary/60">Chưa có bài viết nào trong danh mục này.</p>
          @endforelse
        </div>

        @if ($news && method_exists($news, 'lastPage'))
          <x-products.custom-pagination :paginator="$news->withQueryString()" />
        @endif
      </div>
    @else
      @forelse ($categoriesWithNews as $category)
        <div class="news-category-section">
          <h2 class="text-2xl md:text-4xl font-arima font-semibold text-secondary uppercase mb-8 md:mb-12 tracking-wide" data-aos="fade-right">
            {{ $category->ten_danh_muc }}
          </h2>

          <div class="max-w-[90%] flex flex-col mx-auto gap-12 md:gap-16">
            @foreach ($category->tinTucs as $article)
              @include('clients.news.partials.article-horizontal-card', ['article' => $article])
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

        @if (! $loop->last)
          <div class="border-t border-gray-200 pt-3 md:pt-0"></div>
        @endif
      @empty
        <p class="text-center text-primary/60 py-6">Chưa có bài viết nào.</p>
      @endforelse
    @endif
  </div>
</section>
