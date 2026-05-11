@php
  $blocks = collect($article->noi_dung_blocks ?? [])->filter(fn ($block) => is_array($block))->values();
@endphp

<section class="pt-10 pb-20 md:py-20 bg-neutral-2">
  <div class="w-[85%] max-w-[1000px] mx-auto flex flex-col gap-8">
    <div class="text-xs font-bold uppercase text-primary/70">
      {{ $article->danhMuc->ten_danh_muc ?? 'Tin tức' }}
    </div>

    @if ($blocks->isEmpty())
    <article class="text-primary/90 leading-relaxed whitespace-pre-line">
      {{ $article->mo_ta_ngan }}
    </article>
    @endif

    @foreach ($blocks as $block)
      @php
        $type = data_get($block, 'type', 'text');
        $data = data_get($block, 'data', []);
      @endphp

      @if ($type === 'full_width' || $type === 'image')
      <figure class="w-full">
        <img
          src="{{ \App\Support\AssetPath::url(data_get($data, 'image_url'), 'assets/images/news-detail-1.png') }}"
          alt="{{ data_get($data, 'caption', $article->tieu_de) }}"
          class="w-full h-auto object-cover"
        />
        @if (data_get($data, 'caption'))
        <figcaption class="text-xs text-primary/60 mt-2">{{ data_get($data, 'caption') }}</figcaption>
        @endif
      </figure>
      @elseif ($type === 'two_image_content')
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <img src="{{ \App\Support\AssetPath::url(data_get($data, 'image_url_1'), 'assets/images/news-detail-4.png') }}" alt="" class="w-full h-auto object-cover" />
        <img src="{{ \App\Support\AssetPath::url(data_get($data, 'image_url_2'), 'assets/images/news-detail-5.png') }}" alt="" class="w-full h-auto object-cover" />
      </div>
      @else
      <article class="text-primary/90 leading-relaxed whitespace-pre-line">
        {{ data_get($data, 'content', data_get($data, 'text', data_get($data, 'body', ''))) }}
      </article>
      @endif
    @endforeach
  </div>
</section>
