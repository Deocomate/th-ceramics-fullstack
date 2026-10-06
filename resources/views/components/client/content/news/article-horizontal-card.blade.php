@props(['article'])

@php
  $publishedAt = $article->ngay_dang ?? $article->created_at;
@endphp

<article class="flex flex-col lg:flex-row gap-6 lg:gap-[23px] items-start border-b border-gray-300 pb-8 lg:pb-12 last:border-0" data-aos="fade-up">
  <a href="{{ route('client.news.detail', $article->slug) }}" class="w-full lg:w-[351px] lg:h-[261px] lg:flex-shrink-0 overflow-hidden group">
    <img
      src="{{ \App\Support\AssetPath::url($article->anh_dai_dien, 'assets/images/news-01.jpg') }}"
      alt="{{ $article->tieu_de }}"
      class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
    />
  </a>

  <div class="w-full lg:flex-1 flex flex-col min-w-0">
    <p class="font-archivo text-xs font-semibold text-black uppercase mb-3 lg:mb-[14px]">
      {{ $article->danhMuc->ten_danh_muc ?? 'Tin tức' }}
    </p>

    <a href="{{ route('client.news.detail', $article->slug) }}" class="mb-3 lg:mb-3">
      <h3 class="font-arima text-xl lg:text-[32px] font-light leading-snug lg:leading-[35px] text-[#101010] hover:text-secondary transition-colors">
        {{ $article->tieu_de }}
      </h3>
    </a>

    <p class="font-archivo text-xs font-semibold text-[#AFAFAF] uppercase mb-3 lg:mb-[11px]">
      {{ optional($publishedAt)->format('j \T\H\Á\N\G n, Y') }}
    </p>

    <p class="font-archivo text-sm font-extralight leading-[25px] text-[#2E2F2A] text-justify line-clamp-4">
      {{ $article->mo_ta_ngan }}
      <a
        href="{{ route('client.news.detail', $article->slug) }}"
        class="underline hover:text-secondary transition-colors"
      >Xem thêm</a>
    </p>
  </div>
</article>
