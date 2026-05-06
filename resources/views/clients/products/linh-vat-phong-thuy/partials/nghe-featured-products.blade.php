<section class="w-full md:pb-16 animate-fade-in-up" data-product-section>
  {{-- Mobile Swiper --}}
  <div class="lg:hidden" data-product-carousel-shell>
    <div class="swiper w-full overflow-hidden" data-product-carousel-swiper>
      <div class="swiper-wrapper">
        @foreach($products->chunk(4) as $chunkIndex => $chunk)
        <article class="swiper-slide w-full pb-1" data-product-slide>
          <div class="grid grid-cols-2 gap-4">
            @foreach($chunk as $product)
              @php
                $productImage = (!empty($product->images) && is_array($product->images)) ? $product->images[0] : null;
              @endphp
              <a href="{{ route('client.products.linh-vat-phong-thuy.detail', $product->linh_vat_phong_thuy_ct_id) }}" class="flex flex-col group cursor-pointer">
                <div class="bg-white shadow-[0px_4px_4px_0px_rgba(0,0,0,0.1)] overflow-hidden aspect-square">
                  <img src="{{ $productImage ? Storage::url($productImage) : asset('assets/images/ngoi-01.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply" />
                </div>
                <h3 class="mt-3 text-black font-semibold text-[14px] leading-[20px] uppercase">
                  <span class="block lowercase first-letter:uppercase md:uppercase">{{ $product->name }}</span>
                </h3>
                <p class="text-gray-500 text-[12px] leading-[20px]">MSP: {{ $product->code }}</p>
                <p class="text-secondary font-bold text-[14px] leading-[20px]">{{ $product->price > 0 ? number_format($product->price) . 'đ' : 'Liên hệ' }}</p>
              </a>
            @endforeach
          </div>
        </article>
        @endforeach
      </div>
    </div>

    <div
      class="mt-5 flex justify-center gap-[7px]"
      data-product-swiper-pagination
      aria-label="Product mobile pagination"
    ></div>
  </div>

  {{-- Desktop Grid --}}
  <div
    class="hidden lg:grid grid-cols-4 gap-x-6 gap-y-14"
    data-aos="fade-up"
    data-aos-delay="200"
  >
    @foreach($products as $product)
      @php
        $productImage = (!empty($product->images) && is_array($product->images)) ? $product->images[0] : null;
      @endphp
      <a href="{{ route('client.products.linh-vat-phong-thuy.detail', $product->linh_vat_phong_thuy_ct_id) }}" class="flex flex-col group cursor-pointer">
        <div class="product-card relative bg-white rounded-sm shadow-lg overflow-hidden mb-4 aspect-square transition-all duration-300 group-hover:-translate-y-1">
          <img src="{{ $productImage ? Storage::url($productImage) : asset('assets/images/ngoi-01.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply" />
          <div class="product-overlay">
            <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
            <span>Xem chi tiết</span>
          </div>
        </div>
        <h3 class="text-primary font-semibold text-sm uppercase mb-2 transition-colors group-hover:text-secondary">
          <span class="block lowercase first-letter:uppercase md:uppercase">{{ $product->name }}</span>
        </h3>
        <p class="text-gray-500 text-[13px] mb-2">MSP: {{ $product->code }}</p>
        <p class="text-secondary font-bold text-[14px]">Giá: {{ $product->price > 0 ? number_format($product->price) . 'đ' : 'Liên hệ' }}</p>
      </a>
    @endforeach
  </div>

  {{-- Desktop Pagination --}}
  <div
    class="hidden lg:flex items-center justify-between gap-6 mt-16 text-textPrimary font-bold text-[17px]"
  >
    <button
      class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
      </svg>
    </button>

    <div class="flex items-center gap-5">
      <a href="#" class="text-black border-b-[3px] border-black pb-[2px] px-1">1</a>
      <a href="#" class="text-black/40 hover:text-black transition-colors px-1">2</a>
      <a href="#" class="text-black/40 hover:text-black transition-colors px-1">3</a>
      <span class="text-black/40 tracking-widest px-1">...</span>
      <a href="#" class="text-black/40 hover:text-black transition-colors px-1">9</a>
    </div>

    <button
      class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
      </svg>
    </button>
  </div>
</section>
