@php
  $publishedAt = $article->ngay_dang ?? $article->created_at;
@endphp

<article class="flex flex-col lg:flex-row gap-8 lg:gap-16 items-start border-b border-gray-200 pb-8 lg:pb-12 last:border-0" data-aos="fade-up">
  <a href="{{ route('client.news.detail', $article->slug) }}" class="w-full lg:w-[45%] aspect-[16/10] overflow-hidden group">
    <img
      src="{{ \App\Support\AssetPath::url($article->anh_dai_dien, 'assets/images/news-01.jpg') }}"
      alt="{{ $article->tieu_de }}"
      class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
    />
  </a>

  <div class="w-full lg:w-[55%] flex flex-col pt-2">
    <p class="text-[12px] font-bold text-primary opacity-80 uppercase tracking-widest mb-3">
      {{ $article->danhMuc->ten_danh_muc ?? 'Tin tức' }}
    </p>
    <a href="{{ route('client.news.detail', $article->slug) }}">
      <h3 class="text-2xl md:text-3xl font-arima font-medium text-primary mb-4 leading-tight hover:text-secondary transition-colors cursor-pointer">
        {{ $article->tieu_de }}
      </h3>
    </a>
    <p class="text-[13px] text-gray-500 mb-4 uppercase">
      {{ optional($publishedAt)->format('j \T\H\Á\N\G n, Y') }}
    </p>
    <p class="text-base text-primary/80 leading-relaxed mb-6 text-justify line-clamp-4">
      {{ $article->mo_ta_ngan }}
    </p>
    <div>
      <a href="{{ route('client.news.detail', $article->slug) }}" class="text-primary font-medium underline underline-offset-4 hover:text-secondary transition-colors underline-zinc-400">
        Xem thêm
      </a>
    </div>
  </div>
</article>
