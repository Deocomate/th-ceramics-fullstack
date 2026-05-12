@php
  $recentArticles = collect($recentArticles ?? []);
  $recentProducts = collect($recentProducts ?? []);
@endphp

@if ($recentArticles->isNotEmpty() || $recentProducts->isNotEmpty())
<section class="pb-16 md:pb-32">
  <div class="w-[85%] max-w-[1320px] mx-auto">
    @if ($recentArticles->isNotEmpty())
      <div class="pb-12 md:pb-24 border-b md:border-b-0 border-gray-200">
        <h2 class="text-sm md:text-base font-archivo font-bold text-secondary uppercase mb-6 tracking-wide" data-aos="fade-right">
          Bài viết đã xem liên quan
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-12" data-aos="fade-up">
          @foreach ($recentArticles as $article)
            @php
              $publishedAt = $article->ngay_dang ?? $article->created_at;
            @endphp
            <article class="flex flex-col group">
              <a href="{{ route('client.news.detail', $article->slug) }}" class="aspect-[16/11] overflow-hidden mb-5">
                <img
                  src="{{ \App\Support\AssetPath::url($article->anh_dai_dien, 'assets/images/news-01.jpg') }}"
                  alt="{{ $article->tieu_de }}"
                  class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                />
              </a>
              <p class="text-[11px] font-bold text-primary opacity-80 uppercase tracking-widest mb-2">
                {{ $article->danhMuc->ten_danh_muc ?? 'Tin tức' }}
              </p>
              <a href="{{ route('client.news.detail', $article->slug) }}">
                <h3 class="text-xl font-arima font-medium text-primary mb-3 leading-tight hover:text-secondary transition-colors cursor-pointer">
                  {{ $article->tieu_de }}
                </h3>
              </a>
              <p class="text-[12px] text-gray-500 mb-4 uppercase">
                {{ optional($publishedAt)->format('j \T\H\Á\N\G n, Y') }}
              </p>
              <p class="text-[12px] text-primary/70 leading-relaxed mb-4 line-clamp-3">
                {{ $article->mo_ta_ngan }}
              </p>
              <a href="{{ route('client.news.detail', $article->slug) }}" class="text-primary font-medium underline underline-offset-4 hover:text-secondary underline-zinc-400">
                Xem thêm
              </a>
            </article>
          @endforeach
        </div>
      </div>
    @endif

    @if ($recentProducts->isNotEmpty())
      <div class="history-products-section pt-8 md:pt-0">
        <h2 class="text-sm md:text-base font-archivo font-bold text-secondary uppercase mb-6 tracking-wide" data-aos="fade-right">
          Đã xem gần đây
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6" data-aos="fade-up">
          @foreach ($recentProducts as $product)
            <a href="{{ $product->url }}" class="flex flex-col group">
              <div class="aspect-square shadow-lg flex items-center justify-center mb-4 transition-transform group-hover:-translate-y-1 duration-300 bg-white overflow-hidden">
                <img
                  src="{{ $product->image }}"
                  alt="{{ $product->name }}"
                  class="w-full h-full object-cover"
                />
              </div>
              <h3 class="text-xs font-bold text-primary/90 uppercase mb-1 truncate group-hover:text-secondary transition-colors">
                {{ $product->name }}
              </h3>
              <p class="text-xs font-bold text-primary/60">
                {{ $product->price > 0 ? number_format($product->price) . ' VNĐ' : 'Liên hệ' }}
              </p>
            </a>
          @endforeach
        </div>
      </div>
    @endif
  </div>
</section>
@endif
