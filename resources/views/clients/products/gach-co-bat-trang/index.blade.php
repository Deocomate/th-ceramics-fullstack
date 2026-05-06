<x-layouts.client title="Gạch Cổ Bát Tràng" data-page="products" main-class="bg-background-secondary" :hide-newsletter="true">

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<style>
  @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Lavishly+Yours&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Ephesis&family=Italianno&display=swap");
</style>
@endpush

<x-catalog-button />

<!-- Banner Section -->
<section class="relative w-full h-[530px] md:h-[720px] lg:h-[840px] overflow-hidden flex items-start">
  <div class="absolute inset-0 z-0">
    <img src="{{ !empty($config->thumbnail_main) ? Storage::url($config->thumbnail_main) : asset('assets/images/gach-co-banner.png') }}" alt="Gạch Cổ Bát Tràng Banner" class="w-full h-full object-cover" />
    <div class="absolute inset-0 bg-black/40"></div>
  </div>

  <div
    class="container mx-auto px-[38px] md:px-6 lg:px-[120px] relative z-10 flex flex-col justify-between items-start text-white">
    <div data-aos="fade-right" class="mt-[40px] md:mt-20 w-full md:w-auto">
      <h1
        class="text-center md:text-left font-lavishly text-[50px] sm:text-[60px] leading-[75px] md:text-[80px] lg:text-[128px] md:leading-tight drop-shadow-md">
        Gạch cổ Bát Tràng
      </h1>
    </div>
    <div data-aos="fade-left" class="w-full mt-[150px] md:mt-32 md:self-end md:max-w-xl text-center">
      <p
        class="font-ephesis text-[24px] leading-[32px] md:text-3xl lg:text-[36px] md:leading-[1.2] lg:leading-[1.4] drop-shadow-lg opacity-90">
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
  <!-- Breadcrumb -->
  <div class="pt-6 pb-3 md:pb-6 md:pt-8 relative z-10">
    <x-products.breadcrumb current-label="Gạch Cổ Bát Tràng" />
  </div>

  <hr class="border-t border-black/10 mb-4 w-full" />

  <!-- Product Grid Section -->
  <section class="md:pb-16">
    <!-- Filter Button -->
    <div class="mb-8 block" data-aos="fade-up">
      <button
        class="flex items-center gap-3 text-textPrimary hover:text-secondary transition-colors font-bold uppercase tracking-[0.05em] text-[13px]">
        <div class="w-8 h-8 flex items-center justify-center border border-black/60 rounded text-black/60">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
            </path>
          </svg>
        </div>
        BỘ LỌC SẢN PHẨM
      </button>
    </div>

    @php
    $batProducts = $products->filter(fn($p) => \Illuminate\Support\Str::contains($p->name, 'Bát'))->values();
    $thatXayProducts = $products->filter(fn($p) => \Illuminate\Support\Str::contains($p->name, 'Thất') || \Illuminate\Support\Str::contains($p->name, 'Xây'))->values();
    $theProducts = $products->filter(fn($p) => \Illuminate\Support\Str::contains($p->name, 'Thẻ'))->values();
    @endphp

    <!-- ==================== GẠCH BÁT SECTION ==================== -->
    <div
      class="grid grid-cols-1 lg:grid-cols-2 gap-x-24 lg:gap-x-36 gap-y-[30px] md:gap-y-12 items-start mb-[40px] md:mb-32"
      data-aos="fade-up">
      <!-- Left: Image Gallery -->
      <div class="relative">
        <div class="relative mb-[10px] md:mb-6">
          <img src="{{ !empty($batProducts) && !empty($batProducts[0]->images) && is_array($batProducts[0]->images) ? Storage::url($batProducts[0]->images[0]) : asset('assets/images/gach-bat-01.jpg') }}" alt="Gạch bát công trình"
            class="w-full aspect-[1.1/1] h-auto object-cover rounded-sm shadow-md"
            onerror="this.src = 'https://placehold.co/800x800/8c5a3c/fff?text=Gach+Bat+01'" />
          <img src="{{ asset('assets/images/gach-bat-elip.svg') }}" alt=""
            class="hidden md:block absolute top-1/2 -translate-y-1/2 -right-12 md:-right-20 max-w-[220px] aspect-square object-contain drop-shadow-2xl z-10"
            onerror="this.style.display = 'none'" />
        </div>
        <div class="grid grid-cols-3 gap-[10px] md:gap-7">
          @foreach($batProducts->take(3) as $batProduct)
          <div
            class="aspect-square bg-white shadow-sm border border-black/5 flex items-center justify-center rounded-sm overflow-hidden p-2 md:p-0">
            <img src="{{ !empty($batProduct->images) && is_array($batProduct->images) ? Storage::url($batProduct->images[0]) : asset('assets/images/gach-bat-02.jpg') }}" alt="{{ $batProduct->name }}" class="w-full h-full object-contain" />
          </div>
          @endforeach
        </div>
      </div>

      <!-- Right: Product Info -->
      @include('clients.products.gach-co-bat-trang.partials.product-info', [
        'title' => 'GẠCH BÁT',
        'subtitle' => 'Phối sắc tự nhiên - Hiện diện một nền',
        'products' => $batProducts,
      ])

      <!-- Product Cards Slider -->
      <div class="lg:col-span-2 order-3" data-aos="fade-up">
        <div class="flex items-center justify-between mb-6">
          <p class="text-primary font-bold uppercase tracking-[0.06em] text-[13px]">Danh sách sản phẩm Gạch Bát</p>
          <div class="hidden md:flex items-center gap-3">
            <button type="button"
              class="gach-bat-products-prev product-list-nav-btn w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all"><svg
                class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg></button>
            <button type="button"
              class="gach-bat-products-next product-list-nav-btn w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all"><svg
                class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg></button>
          </div>
        </div>
        <div class="swiper gach-bat-products-slider">
          <div class="swiper-wrapper">
            @foreach($batProducts as $batProduct)
            <div class="swiper-slide !h-auto">
              <x-products.product-card href="{{ route('client.products.gach-co-bat-trang.detail', $batProduct->gach_co_bat_trang_ct_id) }}"
              image="{{ !empty($batProduct->images) && is_array($batProduct->images) ? Storage::url($batProduct->images[0]) : asset('assets/images/gach-bat-01.jpg') }}" title="{{ $batProduct->name }}" alt="{{ $batProduct->name }}" code="MSP: {{ $batProduct->code }}"
              price="{{ $batProduct->price > 0 ? number_format($batProduct->price) . ' đ/viên' : 'Liên hệ' }}" price-prefix="Giá" :show-overlay="true" image-class="w-full h-full object-cover mix-blend-multiply" />
            </div>
            @endforeach
          </div>
        </div>
        <div
          class="swiper-pagination gach-bat-products-pagination product-list-pagination md:!hidden mt-10 relative flex justify-center w-full">
        </div>
      </div>
    </div>

    <!-- ==================== GẠCH THẤT & GẠCH XÂY SECTION ==================== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 lg:gap-x-24 gap-y-12 items-start mb-[40px] md:mb-32"
      data-aos="fade-up">
      <!-- Left: Product Info -->
      @include('clients.products.gach-co-bat-trang.partials.product-info', [
        'title' => 'Gạch Thất & Gạch Xây',
        'subtitle' => 'Phối sắc tự nhiên - Hiện diện một nền',
        'wrapperClass' => 'order-2 lg:order-1',
        'titleClass' => 'uppercase',
        'products' => $thatXayProducts,
      ])

      <!-- Right: Image Gallery -->
      <div class="relative order-1 lg:order-2">
        <div class="relative mb-[10px] md:mb-6">
          <img src="{{ !empty($thatXayProducts) && !empty($thatXayProducts[0]->images) && is_array($thatXayProducts[0]->images) ? Storage::url($thatXayProducts[0]->images[0]) : asset('assets/images/gach-that-01.jpg') }}" alt="Gạch thất công trình"
            class="w-full aspect-[1.1/1] h-auto object-cover rounded-sm shadow-md"
            onerror="this.src = 'https://placehold.co/800x800/8c5a3c/fff?text=Gach+That+01'" />
          <img src="{{ asset('assets/images/gach-that-elip.svg') }}" alt=""
            class="hidden md:block absolute top-1/2 -translate-y-1/2 -left-12 md:-left-20 max-w-[220px] aspect-square object-contain drop-shadow-2xl z-10"
            onerror="this.style.display = 'none'" />
        </div>
        <div class="grid grid-cols-3 gap-[10px] md:gap-7">
          @foreach($thatXayProducts->take(3) as $thatProduct)
          <div
            class="aspect-square bg-white shadow-sm border border-black/5 flex items-center justify-center rounded-sm overflow-hidden p-2 md:p-0">
            <img src="{{ !empty($thatProduct->images) && is_array($thatProduct->images) ? Storage::url($thatProduct->images[0]) : asset('assets/images/gach-that-01.jpg') }}" alt="{{ $thatProduct->name }}" class="w-full h-full object-cover" />
          </div>
          @endforeach
        </div>
      </div>

      <!-- Product Cards Slider -->
      <div class="lg:col-span-2 order-3" data-aos="fade-up">
        <div class="flex items-center justify-between mb-6">
          <p class="text-primary font-bold uppercase tracking-[0.06em] text-[13px]">Danh sách sản phẩm Gạch Thất & Gạch
            Xây</p>
          <div class="hidden md:flex items-center gap-3">
            <button type="button"
              class="gach-that-products-prev product-list-nav-btn w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all"><svg
                class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg></button>
            <button type="button"
              class="gach-that-products-next product-list-nav-btn w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all"><svg
                class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg></button>
          </div>
        </div>
        <div class="swiper gach-that-products-slider">
          <div class="swiper-wrapper">
            @foreach($thatXayProducts as $thatProduct)
            <div class="swiper-slide !h-auto">
              <x-products.product-card href="{{ route('client.products.gach-co-bat-trang.detail', $thatProduct->gach_co_bat_trang_ct_id) }}"
              image="{{ !empty($thatProduct->images) && is_array($thatProduct->images) ? Storage::url($thatProduct->images[0]) : asset('assets/images/gach-that-01.jpg') }}" title="{{ $thatProduct->name }}" alt="{{ $thatProduct->name }}" code="MSP: {{ $thatProduct->code }}"
              price="{{ $thatProduct->price > 0 ? number_format($thatProduct->price) . ' đ/viên' : 'Liên hệ' }}" price-prefix="Giá" :show-overlay="true" image-class="w-full h-full object-cover mix-blend-multiply" />
            </div>
            @endforeach
          </div>
        </div>
        <div
          class="swiper-pagination gach-that-products-pagination product-list-pagination md:!hidden mt-10 relative flex justify-center w-full">
        </div>
      </div>
    </div>

    <!-- ==================== GẠCH THẺ SECTION ==================== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-24 lg:gap-x-36 gap-y-12 items-start" data-aos="fade-up">
      <!-- Left: Image Gallery -->
      <div class="relative">
        <div class="relative mb-[10px] md:mb-6">
          <img src="{{ !empty($theProducts) && !empty($theProducts[0]->images) && is_array($theProducts[0]->images) ? Storage::url($theProducts[0]->images[0]) : asset('assets/images/gach-the-01.jpg') }}" alt="Gạch thẻ công trình"
            class="w-full aspect-[1.1/1] h-auto object-cover rounded-sm shadow-md"
            onerror="this.src = 'https://placehold.co/800x800/8c5a3c/fff?text=Gach+The+01'" />
          <img src="{{ asset('assets/images/gach-the-elip.svg') }}" alt=""
            class="hidden md:block absolute top-1/2 -translate-y-1/2 -right-12 md:-right-20 max-w-[220px] aspect-square object-contain drop-shadow-2xl z-10"
            onerror="this.style.display = 'none'" />
        </div>
        <div class="grid grid-cols-3 gap-[10px] md:gap-7">
          @foreach($theProducts->take(3) as $theProduct)
          <div
            class="aspect-square bg-white shadow-sm border border-black/5 flex items-center justify-center rounded-sm overflow-hidden p-2 md:p-0">
            <img src="{{ !empty($theProduct->images) && is_array($theProduct->images) ? Storage::url($theProduct->images[0]) : asset('assets/images/gach-the-01.jpg') }}" alt="{{ $theProduct->name }}" class="w-full h-full object-cover" />
          </div>
          @endforeach
        </div>
      </div>

      <!-- Right: Product Info -->
      @include('clients.products.gach-co-bat-trang.partials.product-info', [
        'title' => 'GẠCH THẺ',
        'subtitle' => 'Phối sắc tự nhiên - Hiện diện một nền',
        'products' => $theProducts,
      ])

      <!-- Product Cards Slider -->
      <div class="lg:col-span-2 order-3" data-aos="fade-up">
        <div class="flex items-center justify-between mb-6">
          <p class="text-primary font-bold uppercase tracking-[0.06em] text-[13px]">Danh sách sản phẩm Gạch Thẻ</p>
          <div class="hidden md:flex items-center gap-3">
            <button type="button"
              class="gach-the-products-prev product-list-nav-btn w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all"><svg
                class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg></button>
            <button type="button"
              class="gach-the-products-next product-list-nav-btn w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all"><svg
                class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg></button>
          </div>
        </div>
        <div class="swiper gach-the-products-slider">
          <div class="swiper-wrapper">
            @foreach($theProducts as $theProduct)
            <div class="swiper-slide !h-auto">
              <x-products.product-card href="{{ route('client.products.gach-co-bat-trang.detail', $theProduct->gach_co_bat_trang_ct_id) }}"
              image="{{ !empty($theProduct->images) && is_array($theProduct->images) ? Storage::url($theProduct->images[0]) : asset('assets/images/gach-the-01.jpg') }}" title="{{ $theProduct->name }}" alt="{{ $theProduct->name }}" code="MSP: {{ $theProduct->code }}"
              price="{{ $theProduct->price > 0 ? number_format($theProduct->price) . ' đ/viên' : 'Liên hệ' }}" price-prefix="Giá" :show-overlay="true" image-class="w-full h-full object-cover mix-blend-multiply" />
            </div>
            @endforeach
          </div>
        </div>
        <div
          class="swiper-pagination gach-the-products-pagination product-list-pagination md:!hidden mt-10 relative flex justify-center w-full">
        </div>
      </div>
    </div>
  </section>
</div>

<!-- CÔNG ĐOẠN CHẾ TÁC SECTION -->
<x-products.fabrication-process />
<x-products.journey-video :hide-title="true" />

<!-- DẤU ẤN TRÊN NHỮNG CÔNG TRÌNH SECTION -->
<x-products.works-simple :show-nav="true" />

<!-- CÓ THỂ BẠN QUAN TÂM SECTION -->
<x-products.recommendations />

<!-- FAQ Section -->
<section class="w-full relative pb-[70px] md:pb-32 bg-background-secondary overflow-visible" data-aos="fade-up">
  <x-products.faq-content />
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Init Product Sliders
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

    // Init GLightbox
    if (typeof GLightbox !== "undefined") {
      document.querySelectorAll(".glightbox").forEach((anchor) => {
        const image = anchor.querySelector("img");
        if (image) {
          anchor.setAttribute("href", image.currentSrc || image.src);
        }
      });
      GLightbox({
        touchNavigation: true,
        loop: true,
        autoplayVideos: true,
      });
    }
  });
</script>
@endpush

</x-layouts.client>