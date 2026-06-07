<x-client.layouts.main title="Gạch Cổ Bát Tràng" data-page="products" main-class="bg-background-secondary" :hide-newsletter="true">

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<style>
  @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Lavishly+Yours&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Ephesis&family=Italianno&display=swap");
</style>
@endpush

@php
  $assetUrl = function (?string $path, string $fallback) {
      if (empty($path)) {
          return asset($fallback);
      }

      if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
          return $path;
      }

      if (\Illuminate\Support\Str::startsWith($path, 'assets/')) {
          return asset($path);
      }

      return asset('storage/' . $path);
  };

  $sectionPayload = function (?array $stored, array $fallback) {
      $stored = is_array($stored) ? $stored : [];
      $colors = is_array($stored['colors'] ?? null) ? array_values($stored['colors']) : ['#A98467', '#B22222', '#5D5FEF'];

      return [
          'title' => $stored['title'] ?? $fallback['title'],
          'subtitle' => $stored['subtitle'] ?? 'Phối sắc tự nhiên - Hiện diện một nền',
          'description' => $stored['description'] ?? 'Sự không đồng màu này không phải là lỗi kỹ thuật, mà chính là "chứng chỉ" cho nghệ thuật chế tác thủ công. Chính sự biến thiên của lửa trong lò bầu, lò ếch ngày xưa đã tạo nên vẻ đẹp "vạn biến" mà các dòng gạch công nghiệp hiện đại khó lòng mô phỏng được.',
          'colors' => array_slice(array_pad($colors, 3, null), 0, 3),
          'gallery' => is_array($stored['gallery'] ?? null) ? array_values($stored['gallery']) : [],
      ];
  };

  $sectionImages = function (array $section, $products, array $fallbacks) {
      if (!empty($section['gallery'])) {
          return collect($section['gallery'])->values();
      }

      $images = collect($products)
          ->flatMap(fn ($product) => is_array($product->images) ? $product->images : [])
          ->filter()
          ->values();

      return $images->isNotEmpty() ? $images : collect($fallbacks);
  };

  $sections = [
      [
          'key' => 'bat',
          'label' => 'Gạch Bát',
          'products' => $batProducts,
          'payload' => $sectionPayload($config->section_bat, ['title' => 'GẠCH BÁT']),
          'fallbacks' => ['assets/images/gach-bat-01.jpg', 'assets/images/gach-bat-02.jpg'],
          'ellipse' => 'assets/images/gach-bat-elip.svg',
          'reverse' => false,
      ],
      [
          'key' => 'that',
          'label' => 'Gạch Thất & Gạch Xây',
          'products' => $thatXayProducts,
          'payload' => $sectionPayload($config->section_that, ['title' => 'Gạch Thất & Gạch Xây']),
          'fallbacks' => ['assets/images/gach-that-01.jpg'],
          'ellipse' => 'assets/images/gach-that-elip.svg',
          'reverse' => true,
      ],
      [
          'key' => 'the',
          'label' => 'Gạch Thẻ',
          'products' => $theProducts,
          'payload' => $sectionPayload($config->section_the, ['title' => 'GẠCH THẺ']),
          'fallbacks' => ['assets/images/gach-the-01.jpg'],
          'ellipse' => 'assets/images/gach-the-elip.svg',
          'reverse' => false,
      ],
  ];
@endphp

<x-client.shared.catalog-sticky-btn />

<section class="relative w-full h-[530px] md:h-[720px] lg:h-[840px] overflow-hidden flex items-start">
  <div class="absolute inset-0 z-0">
    <img src="{{ $assetUrl($config->thumbnail_main, 'assets/images/gach-co-banner.png') }}" alt="Gạch Cổ Bát Tràng Banner" class="w-full h-full object-cover" />
    <div class="absolute inset-0 bg-black/40"></div>
  </div>

  <div class="container mx-auto px-[38px] md:px-6 lg:px-[120px] relative z-10 flex flex-col justify-between items-start text-white">
    <div data-aos="fade-right" class="mt-[40px] md:mt-20 w-full md:w-auto">
      <h1 class="text-center md:text-left font-lavishly text-[50px] sm:text-[60px] leading-[75px] md:text-[80px] lg:text-[128px] md:leading-tight drop-shadow-md">
        Gạch cổ Bát Tràng
      </h1>
    </div>
    <div data-aos="fade-left" class="w-full mt-[150px] md:mt-32 md:self-end md:max-w-xl text-center">
      <p class="font-ephesis text-[24px] leading-[32px] md:text-3xl lg:text-[36px] md:leading-[1.2] lg:leading-[1.4] drop-shadow-lg opacity-90">
        Trên trời có đám mây xanh<br />
        Ở giữa mây trắng, chung quanh mây vàng<br />
        Ước gì anh lấy được nàng<br />
        Để anh mua gạch Bát Tràng về xây<br />
        Xây dọc rồi lại xây ngang<br />
        Xây hồ bán nguyệt cho nàng rửa chân
      </p>
    </div>
  </div>
</section>

<div class="w-[85%] max-w-[1320px] mx-auto">
  <div class="pt-6 pb-3 md:pb-6 md:pt-8 relative z-10">
    <x-client.shared.breadcrumb current-label="Gạch Cổ Bát Tràng" />
  </div>
</div>

<x-client.shared.product-filter :show-type-filter="true" />

<div class="w-[85%] max-w-[1320px] mx-auto">
  <section class="md:pb-16">
    @forelse($sections as $section)
      @if($section['products']->isNotEmpty())
        @php
          $payload = $section['payload'];
          $galleryImages = $sectionImages($payload, $section['products'], $section['fallbacks']);
          $heroImage = $galleryImages->first();
          $thumbImages = $galleryImages->skip(1)->take(3);
          if ($thumbImages->isEmpty()) {
              $thumbImages = $galleryImages->take(3);
          }
          $sliderPrefix = 'gach-' . $section['key'] . '-products';
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 {{ $section['reverse'] ? 'gap-x-12 lg:gap-x-24' : 'gap-x-24 lg:gap-x-36' }} gap-y-[30px] md:gap-y-12 items-start mb-[40px] md:mb-32" data-aos="fade-up">
          @if($section['reverse'])
            <x-client.products.gach-co-bat-trang.product-info-grid :title="$payload['title']" :subtitle="$payload['subtitle']" :description="$payload['description']" :colors="$payload['colors']" wrapper-class="order-2 lg:order-1" title-class="uppercase" :products="$section['products']" />
          @endif

          <div class="relative {{ $section['reverse'] ? 'order-1 lg:order-2' : '' }}">
            <div class="relative mb-[10px] md:mb-6">
              <img src="{{ $assetUrl($heroImage, $section['fallbacks'][0]) }}" alt="{{ $section['label'] }}" class="w-full aspect-[1.1/1] h-auto object-cover rounded-sm shadow-md" />
              <img src="{{ asset($section['ellipse']) }}" alt="" class="hidden md:block absolute top-1/2 -translate-y-1/2 {{ $section['reverse'] ? '-left-12 md:-left-20' : '-right-12 md:-right-20' }} max-w-[220px] aspect-square object-contain drop-shadow-2xl z-10" onerror="this.style.display = 'none'" />
            </div>
            <div class="grid grid-cols-3 gap-[10px] md:gap-7">
              @foreach($thumbImages as $image)
                <div class="aspect-square bg-white shadow-sm border border-black/5 flex items-center justify-center rounded-sm overflow-hidden p-2 md:p-0">
                  <img src="{{ $assetUrl($image, $section['fallbacks'][0]) }}" alt="{{ $section['label'] }}" class="w-full h-full object-cover" />
                </div>
              @endforeach
            </div>
          </div>

          @unless($section['reverse'])
            <x-client.products.gach-co-bat-trang.product-info-grid :title="$payload['title']" :subtitle="$payload['subtitle']" :description="$payload['description']" :colors="$payload['colors']" :products="$section['products']" />
          @endunless

          <div class="lg:col-span-2 order-3" data-aos="fade-up">
            <div class="flex items-center justify-between mb-6">
              <p class="text-primary font-bold uppercase tracking-[0.06em] text-[13px]">Danh sách sản phẩm {{ $section['label'] }}</p>
              <div class="hidden md:flex items-center gap-3">
                <button type="button" class="{{ $sliderPrefix }}-prev product-list-nav-btn w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                  </svg>
                </button>
                <button type="button" class="{{ $sliderPrefix }}-next product-list-nav-btn w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </button>
              </div>
            </div>
            <div class="swiper {{ $sliderPrefix }}-slider">
              <div class="swiper-wrapper">
                @foreach($section['products'] as $product)
                  @php
                    $productImage = is_array($product->images) ? ($product->images[0] ?? null) : null;
                  @endphp
                  <div class="swiper-slide !h-auto">
                    <x-client.shared.product-card href="{{ route('client.products.gach-co-bat-trang.detail', $product->gach_co_bat_trang_ct_id) }}"
                      image="{{ $assetUrl($productImage, $section['fallbacks'][0]) }}" title="{{ $product->name }}" code="MSP: {{ $product->code }}"
                      price="Giá: {{ $product->price > 0 ? number_format($product->price) . ' đ/viên' : 'Liên hệ' }}" :show-overlay="true" />
                  </div>
                @endforeach
              </div>
            </div>
            <div class="swiper-pagination {{ $sliderPrefix }}-pagination product-list-pagination md:!hidden mt-10 relative flex justify-center w-full"></div>
          </div>
        </div>
      @endif
    @empty
      <div class="py-16 text-center text-primary/70">Chưa có sản phẩm Gạch Cổ Bát Tràng.</div>
    @endforelse

    @if($batProducts->isEmpty() && $thatXayProducts->isEmpty() && $theProducts->isEmpty())
      <div class="py-16 text-center text-primary/70">Không tìm thấy sản phẩm phù hợp với bộ lọc.</div>
    @endif
  </section>
</div>

<x-client.shared.fabrication-process />
<x-client.shared.outstanding-value :gia-tri-vuot-troi="$giaTriVuotTroi ?? null" />
<x-client.shared.journey-video :hide-title="true" />
<x-client.shared.works-simple :show-nav="true" />

<x-client.shared.recommendations
    :related-products="$recommendationProducts"
    route-name="client.products.gach-co-bat-trang.detail"
    pk-field="gach_co_bat_trang_ct_id"
    product-type="gach_co_bat_trang_ct"
/>

<section class="w-full relative pb-[70px] md:pb-32 bg-background-secondary overflow-visible" data-aos="fade-up">
  <x-client.shared.faq-accordion />
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const initSectionProductSlider = (sliderSelector, nextSelector, prevSelector, paginationSelector) => {
      if (!document.querySelector(sliderSelector)) return;

      new Swiper(sliderSelector, {
        slidesPerView: 2,
        spaceBetween: 16,
        loop: true,
        speed: 600,
        grabCursor: true,
        navigation: {
          nextEl: nextSelector,
          prevEl: prevSelector,
        },
        pagination: {
          el: paginationSelector,
          clickable: true,
        },
        breakpoints: {
          640: { slidesPerView: 2, spaceBetween: 18 },
          1024: { slidesPerView: 4, spaceBetween: 24 },
        },
      });
    };

    initSectionProductSlider(".gach-bat-products-slider", ".gach-bat-products-next", ".gach-bat-products-prev", ".gach-bat-products-pagination");
    initSectionProductSlider(".gach-that-products-slider", ".gach-that-products-next", ".gach-that-products-prev", ".gach-that-products-pagination");
    initSectionProductSlider(".gach-the-products-slider", ".gach-the-products-next", ".gach-the-products-prev", ".gach-the-products-pagination");

    if (typeof GLightbox !== "undefined") {
      GLightbox({
        touchNavigation: true,
        loop: true,
        autoplayVideos: true,
      });
    }
  });
</script>
@endpush

</x-client.layouts.main>
