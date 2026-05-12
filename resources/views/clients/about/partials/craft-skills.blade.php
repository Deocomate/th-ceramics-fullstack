@php
  $craftTitle = $about->nt_che_tac_head ?? 'NGHỆ THUẬT CHẾ TÁC THỦ CÔNG ĐIÊU LUYỆN';
  $craftBody = $about->nt_che_tac_body ?? 'Nghệ thuật chế tác của Thanh Hải bắt đầu từ những đôi tay thuần thục mỗi ngày.';
  $craftImages = collect($about->nt_che_tac_anh ?? [])->values();

  $luyenDatTitle = $about->nt_luyen_dat_head ?? 'Kỹ thuật luyện đất';
  $luyenDatBody = $about->nt_luyen_dat_body ?? 'Đất là gốc rễ, nền tảng của mọi sản phẩm gốm chất lượng.';
  $luyenDatItems = collect($about->nt_luyen_dat_item ?? [])->filter(fn ($item) => is_array($item))->values();

  $dunLoTitle = $about->nt_dun_lo_head ?? 'Kỹ thuật đun lò';
  $dunLoBody = $about->nt_dun_lo_body ?? 'Công đoạn nung sản phẩm trong lò là bước quyết định chất lượng gốm.';
  $dunLoImages = collect($about->nt_dun_lo_anh ?? [])->values();
@endphp

<!-- Craft Section 3: Nghệ thuật chế tác thủ công điêu luyện -->
<div class="mt-[25px] md:mt-32 text-center max-w-7xl mx-auto">
  <h2
    class="text-[20px] md:text-4xl font-bold text-secondary mb-[20px] md:mb-12 uppercase tracking-[0.6px] md:tracking-wide leading-[32px] md:leading-normal"
    data-aos="fade-up"
  >
    {{ $craftTitle }}
  </h2>
  <p
    class="text-textPrimary leading-[28px] text-justify md:text-center mb-[30px] md:mb-24 max-w-2xl mx-auto font-medium tracking-[0.48px] md:tracking-wide"
    data-aos="fade-up"
  >
    {{ $craftBody }}
  </p>

  <div
    class="hidden md:grid grid-cols-1 md:grid-cols-2 gap-[59px] mb-16"
    data-aos="fade-up"
  >
    @foreach($craftImages->take(2) as $image)
    <div class="aspect-[1/1] relative overflow-hidden shadow-lg">
      <img
        src="{{ \App\Support\AssetPath::url($image, 'assets/images/about-02.jpg') }}"
        alt="Chế tác thủ công"
        class="w-full h-full object-cover"
      />
    </div>
    @endforeach
  </div>

  <div class="space-y-12 md:space-y-16">
    <div
      class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-16 text-left items-center"
      data-aos="fade-up"
    >
      <article>
        <h3 class="text-2xl md:text-3xl font-bold text-textPrimary mb-4">{{ $luyenDatTitle }}</h3>
        <p class="text-textPrimary leading-relaxed font-medium tracking-wide">{{ $luyenDatBody }}</p>
      </article>
      <div class="relative overflow-hidden shadow-lg aspect-[1/1]">
        <img
          src="{{ \App\Support\AssetPath::url($craftImages->first(), 'assets/images/about-02.jpg') }}"
          alt="{{ $luyenDatTitle }}"
          class="w-full h-full object-cover"
        />
      </div>
    </div>

    @foreach($luyenDatItems as $item)
      @if($loop->index % 2 === 0)
      <div
        class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-16 text-left items-center"
        data-aos="fade-up"
      >
        <div class="relative overflow-hidden shadow-lg aspect-[1/1]">
          <img
            src="{{ \App\Support\AssetPath::url(data_get($item, 'image'), 'assets/images/about-02.jpg') }}"
            alt="{{ data_get($item, 'head', 'Nghệ thuật thủ công') }}"
            class="w-full h-full object-cover"
          />
        </div>
        <article>
          <h3 class="text-2xl md:text-3xl font-bold text-textPrimary mb-4">{{ data_get($item, 'head') }}</h3>
          <p class="text-textPrimary leading-relaxed font-medium tracking-wide">{{ data_get($item, 'body') }}</p>
        </article>
      </div>
      @else
      <div
        class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-16 text-left items-center"
        data-aos="fade-up"
      >
        <article>
          <h3 class="text-2xl md:text-3xl font-bold text-textPrimary mb-4">{{ data_get($item, 'head') }}</h3>
          <p class="text-textPrimary leading-relaxed font-medium tracking-wide">{{ data_get($item, 'body') }}</p>
        </article>
        <div class="relative overflow-hidden shadow-lg aspect-[1/1]">
          <img
            src="{{ \App\Support\AssetPath::url(data_get($item, 'image'), 'assets/images/about-02.jpg') }}"
            alt="{{ data_get($item, 'head', 'Nghệ thuật thủ công') }}"
            class="w-full h-full object-cover"
          />
        </div>
      </div>
      @endif
    @endforeach

    <div
      class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-16 text-left"
      data-aos="fade-up"
    >
      <article class="md:col-span-2 text-center">
        <h3 class="text-2xl md:text-3xl font-bold text-textPrimary mb-4">{{ $dunLoTitle }}</h3>
        <p class="text-textPrimary leading-relaxed font-medium tracking-wide max-w-3xl mx-auto">{{ $dunLoBody }}</p>
      </article>

      @foreach($dunLoImages->take(2) as $image)
      <div class="relative overflow-hidden shadow-lg aspect-[1/1]">
        <img
          src="{{ \App\Support\AssetPath::url($image, 'assets/images/about-02.jpg') }}"
          alt="{{ $dunLoTitle }}"
          class="w-full h-full object-cover"
        />
      </div>
      @endforeach
    </div>
  </div>
</div>
