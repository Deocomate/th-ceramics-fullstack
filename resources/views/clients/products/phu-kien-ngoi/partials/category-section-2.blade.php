@if($boNocChuVanProducts->isNotEmpty())
@php
  $assetUrl = fn (?string $path, ?string $fallback = null) => \App\Support\AssetPath::url($path, $fallback);
  $productImageUrl = fn ($product, string $fallback) => $assetUrl(data_get($product, 'images.0'), $fallback);
@endphp

<section class="w-full pb-[30px] md:pb-16">
  <div class="md:bg-[#EBCEC1] opacity-80 p-6 lg:p-10" style="margin-right: max(0px, calc((100% - 1320px) / 2))" data-aos="fade-up" data-aos-delay="100">
    <div class="flex flex-col lg:flex-row gap-8 lg:gap-16 max-w-[1320px] ml-auto">
      <div class="w-full lg:w-[45%] ml-0 lg:ml-[10%] flex flex-col justify-stretch">
        <div class="w-full flex-grow relative shadow-lg overflow-hidden bg-black/5 min-h-[400px] lg:min-h-[500px]">
          <img src="{{ $assetUrl($config->sec2_image, 'assets/images/dao-kim.png') }}" alt="" class="absolute inset-0 w-full h-full object-cover">

          <div class="absolute bottom-6 left-1/2 -translate-x-1/2 w-[95%] max-w-[560px] z-10 hover:scale-105 transition-transform duration-300">
            <div class="relative w-full flex items-center justify-center">
              <img src="{{ asset('assets/images/brush.svg') }}" alt="" class="w-full drop-shadow-xl">
              <span class="absolute text-white font-bold text-[24px] md:text-[32px] uppercase tracking-wider" style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4)">
                {{ $config->sec2_title ?: 'BỜ NÓC CHỮ VẠN' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="w-full lg:w-[55%] flex flex-col justify-between">
        <div class="lg:hidden" data-product-section>
          <div class="flex overflow-x-auto scroll-smooth snap-x snap-mandatory scrollbar-hide" data-product-carousel>
            @foreach($boNocChuVanProducts->chunk(4) as $chunk)
              <article class="flex-shrink-0 w-full snap-start pb-1" data-product-slide>
                <div class="grid grid-cols-2 gap-4">
                  @foreach($chunk as $product)
                    <a href="{{ route('client.products.phu-kien-ngoi.detail', ['id' => $product->bo_noc_chu_van_ct_id, 'type' => 'chu_van']) }}" class="flex flex-col group cursor-pointer">
                      <div class="bg-white shadow-[0px_4px_4px_0px_rgba(0,0,0,0.1)] overflow-hidden aspect-square">
                        <img src="{{ $productImageUrl($product, 'assets/images/pk-03.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply">
                      </div>
                      <h3 class="mt-3 text-black font-semibold text-[14px] leading-[20px] lowercase first-letter:uppercase">
                        <span class="block">{{ $product->name }}</span>
                      </h3>
                      <p class="text-gray-500 text-[12px] leading-[20px]">MSP: PKN-CV{{ $product->bo_noc_chu_van_ct_id }}</p>
                      <p class="text-secondary font-bold text-[14px] leading-[20px]">Liên hệ</p>
                    </a>
                  @endforeach
                </div>
              </article>
            @endforeach
          </div>

          <div class="mt-5 flex justify-center gap-[7px]" aria-label="Product mobile pagination">
            @foreach($boNocChuVanProducts->chunk(4) as $index => $chunk)
              <button class="product-section-dot h-2 w-2 rounded-full {{ $index === 0 ? 'bg-secondary' : 'bg-[#C76E00]/30' }}" data-product-dot="{{ $index }}" type="button" aria-label="Slide {{ $index + 1 }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
            @endforeach
          </div>
        </div>

        <div class="hidden lg:grid grid-cols-2 gap-x-8 lg:gap-x-16 gap-y-10 lg:gap-y-12 mb-10">
          @foreach($boNocChuVanProducts->take(4) as $product)
            <div class="flex flex-col group cursor-pointer" onclick="window.location.href = '{{ route('client.products.phu-kien-ngoi.detail', ['id' => $product->bo_noc_chu_van_ct_id, 'type' => 'chu_van']) }}'">
              <div class="product-card relative w-full aspect-square shadow mb-4 flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:-translate-y-1">
                <img src="{{ $productImageUrl($product, 'assets/images/pk-03.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply">
                <div class="product-overlay">
                  <img src="{{ asset('assets/images/eye.svg') }}" alt="Search">
                  <span>Xem chi tiết</span>
                </div>
              </div>
              <h3 class="font-bold text-[#212121] text-[14px] lg:text-[15px] uppercase mb-1 tracking-wide transition-colors group-hover:text-secondary">
                {{ $product->name }}
              </h3>
              <p class="text-gray-500 text-[12px] lg:text-[13px] mb-1">MSP: PKN-CV{{ $product->bo_noc_chu_van_ct_id }}</p>
              <p class="font-bold text-[#C47526] text-[13px] lg:text-[14px]">Giá: Liên hệ</p>
            </div>
          @endforeach
        </div>

        <div class="hidden lg:flex items-center justify-center gap-5 mt-10 lg:mt-auto">
          <button class="w-[40px] h-[40px] rounded-full border border-secondary flex items-center justify-center text-secondary hover:bg-secondary hover:text-white transition-all duration-300" type="button" aria-label="Sản phẩm trước">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
          </button>
          <button class="w-[40px] h-[40px] rounded-full bg-secondary flex items-center justify-center text-white hover:opacity-90 transition-all duration-300" type="button" aria-label="Sản phẩm tiếp theo">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</section>
@endif
