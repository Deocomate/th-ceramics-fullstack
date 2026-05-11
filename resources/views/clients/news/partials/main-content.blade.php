@php
  $categoryId = request()->integer('category');
@endphp

<section class="py-12 md:py-20 bg-neutral-2">
  <div class="w-[85%] max-w-[1320px] mx-auto flex flex-col gap-10 md:gap-14">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
      <h2 class="text-2xl md:text-4xl font-arima font-semibold text-secondary uppercase">Tin tức mới nhất</h2>
      <form method="GET" action="{{ route('client.news.index') }}" class="flex flex-wrap gap-2">
        <a
          href="{{ route('client.news.index') }}"
          class="px-4 py-2 text-xs md:text-sm font-bold uppercase border {{ $categoryId ? 'border-black/20 text-primary/70' : 'border-secondary text-secondary' }}"
        >
          Tất cả
        </a>
        @foreach ($categories as $category)
        <button
          type="submit"
          name="category"
          value="{{ $category->danh_muc_tin_tuc_id }}"
          class="px-4 py-2 text-xs md:text-sm font-bold uppercase border transition-colors {{ $categoryId === $category->danh_muc_tin_tuc_id ? 'border-secondary text-secondary' : 'border-black/20 text-primary/70 hover:text-secondary hover:border-secondary' }}"
        >
          {{ $category->ten_danh_muc }}
        </button>
        @endforeach
      </form>
    </div>

    @if ($featuredNews->isNotEmpty())
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
      @foreach ($featuredNews as $article)
      <article class="bg-white shadow-sm overflow-hidden">
        <a href="{{ route('client.news.detail', $article->slug) }}" class="block aspect-[16/10] overflow-hidden">
          <img
            src="{{ \App\Support\AssetPath::url($article->anh_dai_dien, 'assets/images/news-01.jpg') }}"
            alt="{{ $article->tieu_de }}"
            class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
          />
        </a>
        <div class="p-5">
          <p class="text-[11px] font-bold text-primary/70 uppercase mb-2">{{ $article->danhMuc->ten_danh_muc ?? 'Tin tức' }}</p>
          <a href="{{ route('client.news.detail', $article->slug) }}" class="text-xl font-arima text-primary hover:text-secondary transition-colors">
            {{ $article->tieu_de }}
          </a>
          <p class="text-xs text-primary/60 mt-3">{{ optional($article->ngay_dang)->format('d/m/Y') ?? optional($article->created_at)->format('d/m/Y') }}</p>
        </div>
      </article>
      @endforeach
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">
      @forelse ($news as $article)
      <article class="flex flex-col">
        <a href="{{ route('client.news.detail', $article->slug) }}" class="aspect-[16/10] overflow-hidden mb-4 block">
          <img
            src="{{ \App\Support\AssetPath::url($article->anh_dai_dien, 'assets/images/news-01.jpg') }}"
            alt="{{ $article->tieu_de }}"
            class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
          />
        </a>
        <p class="text-[11px] font-bold text-primary/70 uppercase mb-2">{{ $article->danhMuc->ten_danh_muc ?? 'Tin tức' }}</p>
        <a href="{{ route('client.news.detail', $article->slug) }}" class="text-xl font-arima text-primary hover:text-secondary transition-colors mb-2">
          {{ $article->tieu_de }}
        </a>
        <p class="text-xs text-primary/60 mb-3">{{ optional($article->ngay_dang)->format('d/m/Y') ?? optional($article->created_at)->format('d/m/Y') }}</p>
        <p class="text-sm text-primary/80 leading-relaxed line-clamp-3">{{ $article->mo_ta_ngan }}</p>
      </article>
      @empty
      <p class="col-span-full text-center text-primary/60 py-6">Chưa có bài viết nào.</p>
      @endforelse
    </div>

    @if ($news && method_exists($news, 'links'))
    <div class="pt-4">
      {{ $news->links() }}
    </div>
    @endif
  </div>
</section>
