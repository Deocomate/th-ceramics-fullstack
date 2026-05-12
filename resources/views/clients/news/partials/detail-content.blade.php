@php
  $blocks = collect($article->noi_dung_blocks ?? [])->filter(fn ($block) => is_array($block))->values();
  $articleTitle = $article->tieu_de ?? 'Tin tức Thanh Hải';

  $subtitleClass = 'text-[12px] font-bold text-primary opacity-80 uppercase tracking-widest mb-4';
  $titleClass = 'text-3xl md:text-4xl font-arima font-medium text-primary mb-4 md:mb-6 leading-tight';
  $descClass = 'text-[14px] text-primary/80 leading-[1.8] text-left md:text-justify font-light md:font-normal whitespace-pre-line';
@endphp

<section class="pt-10 pb-20 md:py-24 bg-neutral-2">
  <div class="w-[85%] max-w-[1200px] mx-auto flex flex-col gap-16 md:gap-24">
    @if ($blocks->isEmpty())
      <article class="text-primary/90 leading-relaxed whitespace-pre-line max-w-[1000px] mx-auto">
        {{ $article->mo_ta_ngan }}
      </article>
    @endif

    @foreach ($blocks as $block)
      @php
        $type = data_get($block, 'type', 'text');
        $data = data_get($block, 'data', []);
      @endphp

      @switch($type)
        @case('split_content')
          @php
            $isImgLeft = data_get($data, 'layout') === 'image_left';
            $imageUrl = \App\Support\AssetPath::url(data_get($data, 'image_url'), 'assets/images/news-detail-1.png');
            $imageAlt = data_get($data, 'image_alt', $articleTitle);
          @endphp

          <div class="flex flex-col {{ $isImgLeft ? 'md:flex-row-reverse' : 'md:flex-row' }} gap-10 md:gap-24 items-start" data-aos="fade-up">
            <div class="w-full md:w-1/2 flex flex-col pt-0 md:pt-6">
              @if (data_get($data, 'subtitle'))
                <p class="{{ $subtitleClass }}">{{ data_get($data, 'subtitle') }}</p>
              @endif
              @if (data_get($data, 'title'))
                <h2 class="{{ $titleClass }}">{{ data_get($data, 'title') }}</h2>
              @endif
              @if (data_get($data, 'description'))
                <p class="{{ $descClass }} md:w-[95%]">{{ data_get($data, 'description') }}</p>
              @endif
            </div>
            <div class="w-full md:w-1/2">
              <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" class="w-full h-auto object-cover">
            </div>
          </div>
          @break

        @case('image_metadata')
          @php
            $imageUrl = \App\Support\AssetPath::url(data_get($data, 'image_url'), 'assets/images/news-01.jpg');
            $imageAlt = data_get($data, 'image_alt', $articleTitle);
            $specs = collect(data_get($data, 'specs', []))->filter(fn ($spec) => is_array($spec) && (data_get($spec, 'label') || data_get($spec, 'value')));
          @endphp

          <div class="flex flex-col md:flex-row gap-10 md:gap-24 items-start md:items-center" data-aos="fade-up">
            <div class="w-full md:w-1/2">
              <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" class="w-full h-auto object-cover border border-black/5">
            </div>
            <div class="w-full md:w-1/2 flex flex-col gap-5 md:pl-4">
              @foreach ($specs as $spec)
                <div class="flex flex-col gap-1 border-b border-gray-200 pb-3 last:border-0">
                  @if (data_get($spec, 'label'))
                    <span class="text-[13px] font-extrabold text-primary uppercase tracking-widest">{{ data_get($spec, 'label') }}</span>
                  @endif
                  @if (data_get($spec, 'value'))
                    <span class="text-[15px] text-primary/80">{{ data_get($spec, 'value') }}</span>
                  @endif
                </div>
              @endforeach
            </div>
          </div>
          @break

        @case('full_width_image')
        @case('full_width')
        @case('image')
          @php
            $imageUrl = \App\Support\AssetPath::url(data_get($data, 'image_url'), 'assets/images/news-detail-3.png');
            $imageAlt = data_get($data, 'image_alt', data_get($data, 'caption', $articleTitle));
          @endphp

          <figure class="w-full mx-auto" data-aos="fade-up">
            <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" class="w-full h-auto object-cover">
            @if (data_get($data, 'caption'))
              <figcaption class="text-xs text-primary/60 mt-2">{{ data_get($data, 'caption') }}</figcaption>
            @endif
          </figure>
          @break

        @case('two_image_content')
          @php
            $isImgLeft = data_get($data, 'layout') === 'images_left';
            $imageUrl1 = \App\Support\AssetPath::url(data_get($data, 'image_url_1'), 'assets/images/news-detail-4.png');
            $imageUrl2 = \App\Support\AssetPath::url(data_get($data, 'image_url_2'), 'assets/images/news-detail-5.png');
            $imageAlt1 = data_get($data, 'image_alt_1', $articleTitle);
            $imageAlt2 = data_get($data, 'image_alt_2', $articleTitle);
            $specs = collect(data_get($data, 'specs', []))->filter(fn ($spec) => is_array($spec) && (data_get($spec, 'label') || data_get($spec, 'value')));
          @endphp

          <div class="flex flex-col {{ $isImgLeft ? 'md:flex-row-reverse' : 'md:flex-row' }} gap-12 md:gap-24 items-start" data-aos="fade-up">
            <div class="w-full md:w-1/2 flex flex-col pt-0 md:pt-6">
              @if (data_get($data, 'subtitle'))
                <p class="{{ $subtitleClass }}">{{ data_get($data, 'subtitle') }}</p>
              @endif
              @if (data_get($data, 'title'))
                <h2 class="{{ $titleClass }} md:w-[70%]">{{ data_get($data, 'title') }}</h2>
              @endif
              @if (data_get($data, 'description'))
                <p class="{{ $descClass }} md:w-[80%]">{{ data_get($data, 'description') }}</p>
              @endif

              @if ($specs->isNotEmpty())
                <div class="w-full md:w-3/4 flex flex-col gap-5 mt-6 md:mt-8 border-t border-gray-200 pt-6">
                  @foreach ($specs as $spec)
                    <div class="flex flex-col gap-1">
                      @if (data_get($spec, 'label'))
                        <span class="text-[13px] font-extrabold text-primary uppercase tracking-widest">{{ data_get($spec, 'label') }}</span>
                      @endif
                      @if (data_get($spec, 'value'))
                        <span class="text-sm text-primary/80">{{ data_get($spec, 'value') }}</span>
                      @endif
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
            <div class="w-full md:w-1/2 flex flex-col items-start md:items-center gap-10 md:gap-8">
              <img src="{{ $imageUrl1 }}" alt="{{ $imageAlt1 }}" class="md:w-[60%] w-full h-auto object-cover self-end shadow-sm">
              <img src="{{ $imageUrl2 }}" alt="{{ $imageAlt2 }}" class="w-full h-auto object-cover shadow-sm">
            </div>
          </div>
          @break

        @case('call_to_action')
          @php
            $imageUrl = \App\Support\AssetPath::url(data_get($data, 'image_url'), 'assets/images/news-detail-5.png');
            $imageAlt = data_get($data, 'image_alt', data_get($data, 'title', $articleTitle));
            $buttonText = data_get($data, 'button_text', 'XEM CHI TIẾT');
            $buttonLink = data_get($data, 'button_link', '#') ?: '#';
          @endphp

          <div class="bg-white overflow-hidden" data-aos="fade-up">
            <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-16">
              <div class="w-full lg:w-5/12 p-8 lg:p-12">
                @if (data_get($data, 'title'))
                  <h2 class="text-3xl lg:text-[42px] font-arima font-light text-primary leading-tight mb-6">
                    {{ data_get($data, 'title') }}
                  </h2>
                @endif
                <a href="{{ $buttonLink }}" class="inline-block px-10 py-3.5 border border-primary text-[13px] font-bold text-primary uppercase tracking-[0.2em] hover:bg-primary hover:text-white transition-all duration-300">
                  {{ $buttonText }}
                </a>
              </div>
              <div class="w-full lg:w-7/12">
                <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" class="w-full h-auto object-cover">
              </div>
            </div>
          </div>
          @break

        @default
          @php
            $content = data_get($data, 'content', data_get($data, 'text', data_get($data, 'body', '')));
          @endphp

          @if ($content)
            <article class="text-primary/90 leading-relaxed whitespace-pre-line max-w-[1000px] mx-auto" data-aos="fade-up">
              {{ $content }}
            </article>
          @endif
      @endswitch
    @endforeach
  </div>
</section>
