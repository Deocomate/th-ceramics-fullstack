<x-layouts.client title="Linh Vật Phong Thủy" data-page="products" main-class="bg-background-secondary">

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

@include('clients.products.linh-vat-phong-thuy.partials.banner-section')

@php
  $currentProducts = $products instanceof \Illuminate\Contracts\Pagination\Paginator
      ? collect($products->items())->values()
      : collect($products ?? [])->values();
@endphp

<div class="w-[85%] max-w-[1320px] mx-auto">
  <!-- Breadcrumb -->
  <div class="pt-6 pb-3 md:pb-6 md:pt-8 relative z-10">
    <x-products.breadcrumb current-label="Linh Vật Phong Thủy" />
  </div>
</div>

<x-products.product-filter />

<div class="w-[85%] max-w-[1320px] mx-auto">
  <!-- Product Grid Section -->
  <section class="md:pb-8" data-aos="fade-up">
    @php
      $linhVatItems = $config->linhVat ?? collect();
      $layoutConfigs = [
        [
          'section_class' => 'mb-[50px] md:mb-24',
          'direction' => 'flex-col md:flex-row',
          'image_wrapper' => 'w-full h-full max-h-[438px] md:max-h-full md:w-[68%] z-0',
          'image_box' => 'h-full md:aspect-auto',
          'box_margin' => 'md:ml-[-6%]',
          'bg' => 'bg-[#5D8482]',
          'fallback' => 'assets/images/nghe.png',
          'fallback_error' => 'assets/images/ngoi-01.jpg',
        ],
        [
          'section_class' => 'mt-8 mb-20',
          'direction' => 'flex-col md:flex-row-reverse',
          'image_wrapper' => 'w-full md:w-[68%] z-0',
          'image_box' => 'aspect-[6/5] md:aspect-auto',
          'box_margin' => 'md:mr-[-6%]',
          'bg' => 'bg-[#D2A35C]',
          'fallback' => 'assets/images/phuong.png',
          'fallback_error' => 'assets/images/ngoi-05.jpg',
        ],
        [
          'section_class' => 'mt-8 mb-8 md:mb-20',
          'direction' => 'flex-col md:flex-row',
          'image_wrapper' => 'w-full md:w-[68%] z-0',
          'image_box' => 'aspect-[5/6] md:aspect-auto',
          'box_margin' => 'md:ml-[-6%]',
          'bg' => 'bg-[#B36E6E]',
          'fallback' => 'assets/images/dau-rong.png',
          'fallback_error' => 'assets/images/ngoi-07.jpg',
        ],
      ];
    @endphp

    @foreach($linhVatItems as $index => $linhVat)
      @php
        $layoutConfig = $layoutConfigs[$index % count($layoutConfigs)];
        $linhVatImage = \App\Support\AssetPath::url($linhVat->image, $layoutConfig['fallback']);
        $linhVatFallback = asset($layoutConfig['fallback_error']);
      @endphp

      <section class="{{ $layoutConfig['section_class'] }} relative" data-aos="fade-up">
        <div class="flex {{ $layoutConfig['direction'] }} items-center">
          <div class="{{ $layoutConfig['image_wrapper'] }}">
            <div class="{{ $layoutConfig['image_box'] }} overflow-hidden rounded-sm shadow-xl">
              <img
                src="{{ $linhVatImage }}"
                alt="Linh vật {{ $linhVat->title }}"
                class="w-full h-full object-cover"
                onerror="this.onerror=null; this.src='{{ $linhVatFallback }}'"
              />
            </div>
          </div>
          <div class="w-[90%] md:w-[38%] mt-[-60px] md:mt-0 {{ $layoutConfig['box_margin'] }} {{ $layoutConfig['bg'] }} p-8 md:p-12 lg:p-16 text-white shadow-2xl z-10 rounded-sm">
            <h2 class="font-lavishly text-[48px] md:text-[56px] lg:text-[64px] mb-12 leading-none">{{ $linhVat->title }}</h2>
            <p class="font-light text-justify text-base md:text-lg lg:text-[20px] leading-8 md:leading-9 tracking-normal">
              {{ $linhVat->description }}
            </p>
          </div>
        </div>
      </section>

      @include('clients.products.linh-vat-phong-thuy.partials.nghe-featured-products', [
        'products' => $products,
      ])
    @endforeach
  </section>
</div>

<!-- Product Grid Section -->
@if($currentProducts->isNotEmpty())
<section class="mt-8 md:mt-auto relative mx-auto pb-8 overflow-visible">
  <div class="relative w-[85%] md:w-full max-w-[1920px] mx-auto z-10">
    <div class="md:ml-[15.5%] grid grid-cols-2 lg:grid-cols-4 gap-x-6 lg:gap-x-8 gap-y-[30px] lg:gap-y-6"
      data-aos="fade-up">
      @foreach($currentProducts->take(5) as $product)
        @php
          $productImage = \App\Support\AssetPath::url(collect($product->images ?? [])->first(), 'assets/images/ngoi-01.jpg');
          $delay = 100 + ($loop->index * 100);
          $isFirst = $loop->first;
        @endphp
        <div class="flex flex-col {{ $isFirst ? 'col-span-2 lg:col-span-2' : '' }} group cursor-pointer" data-aos="fade-up" data-aos-delay="{{ $delay }}"
          onclick="window.location.href = '{{ route('client.products.linh-vat-phong-thuy.detail', $product->linh_vat_phong_thuy_ct_id) }}'">
          <div
            class="product-card relative w-full {{ $isFirst ? 'aspect-[2/1] md:aspect-[18.8/10]' : 'aspect-[1.1/1] md:aspect-[9/10]' }} shadow mb-4 flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:-translate-y-1">
            <img src="{{ $productImage }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
            <div class="product-overlay">
              <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
              <span>Xem chi tiết</span>
            </div>
          </div>
          <h3
            class="font-bold text-[#212121] text-[14px] lg:text-[15px] uppercase mb-1 tracking-wide transition-colors group-hover:text-secondary">
            {{ $product->name }}</h3>
          <p class="text-gray-500 text-[12px] lg:text-[13px] mb-1">MSP: {{ $product->code }}</p>
          <p class="font-bold text-[#C47526] text-[13px] lg:text-[14px]">{{ $product->price > 0 ? number_format($product->price) . 'đ' : 'Liên hệ' }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<x-products.fabrication-process />
<x-products.journey-video :video="$config->video" :hide-title="true" />
<x-products.recommendations
    :related-products="$currentProducts->take(4)"
    route-name="client.products.linh-vat-phong-thuy.detail"
    pk-field="linh_vat_phong_thuy_ct_id"
    product-type="linh_vat_phong_thuy_ct"
/>

<!-- FAQ Section -->
<section class="w-full relative pb-[70px] md:pb-32 bg-background-secondary overflow-visible" data-aos="fade-up">
  <x-products.faq-content />
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", () => {
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
