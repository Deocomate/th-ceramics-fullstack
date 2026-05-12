{{-- Bờ Nóc Chữ Vạn Category Section --}}
@if($boNocChuVanProducts->isNotEmpty())
<section class="w-full pb-[30px] md:pb-16">
  <div class="bg-[#C4A882] opacity-80 p-6 lg:p-10" style="margin-left: max(0px, calc((100% - 1320px) / 2))"
    data-aos="fade-up" data-aos-delay="100">
    <div class="flex flex-col lg:flex-row-reverse gap-8 lg:gap-16 max-w-[1320px] mr-auto">
      {{-- Featured Product --}}
      <div class="w-full lg:w-[45%] mr-0 lg:mr-[10%] flex flex-col justify-stretch">
        @php
          $featured = $boNocChuVanProducts->first();
          $featuredImage = (!empty($featured->images) && is_array($featured->images)) ? $featured->images[0] : null;
        @endphp
        <div
          class="w-full flex-grow relative shadow-xl overflow-hidden bg-black/5 min-h-[400px] lg:min-h-[500px] border border-black/10">
          <img src="{{ $featuredImage ? asset('storage/' . $featuredImage) : asset('assets/images/chu-van-mobile.png') }}" alt="" class="absolute inset-0 w-full h-full object-cover" />
          <div
            class="absolute bottom-6 left-1/2 -translate-x-1/2 w-[85%] max-w-[560px] z-10 hover:scale-105 transition-transform duration-300">
            <div class="relative w-full flex items-center justify-center">
              <img src="{{ asset('assets/images/brush.svg') }}" alt="" class="w-full drop-shadow-xl opacity-90" />
              <span
                class="lan-can-brush-title absolute text-white font-bold text-[24px] md:text-[32px] uppercase tracking-wider"
                style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4)">
                BỜ NÓC CHỮ VẠN
              </span>
            </div>
          </div>
        </div>
      </div>

      {{-- Product Grid --}}
      <div class="w-full lg:w-[55%] flex flex-col justify-between">
        <div class="grid grid-cols-2 gap-x-4 md:gap-x-8 lg:gap-x-16 gap-y-6 md:gap-y-10 lg:gap-y-12 mb-6 md:mb-10">
          @foreach($boNocChuVanProducts->take(4) as $product)
            @php
              $productImage = (!empty($product->images) && is_array($product->images)) ? $product->images[0] : null;
            @endphp
            <div class="flex flex-col group cursor-pointer" onclick="window.location.href = '{{ route('client.products.phu-kien-ngoi.detail', ['id' => $product->bo_noc_chu_van_ct_id, 'type' => 'chu_van']) }}'">
              <div
                class="product-card relative w-full aspect-square mb-4 flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:-translate-y-1 shadow-md">
                <img src="{{ $productImage ? asset('storage/' . $productImage) : asset('assets/images/ngoi-01.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
                <div class="product-overlay">
                  <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
                  <span>Xem chi tiết</span>
                </div>
              </div>
              <h3
                class="font-bold text-[#212121] text-[14px] lg:text-[15px] uppercase mb-1 tracking-wide transition-colors group-hover:text-secondary">
                {{ $product->name }}</h3>
              <p class="text-gray-500 text-[12px] lg:text-[13px] mb-1">MSP: {{ $product->bo_noc_chu_van_ct_id }}</p>
              <p class="font-bold text-[#C47526] text-[13px] lg:text-[14px]">Giá: Liên hệ</p>
            </div>
          @endforeach
        </div>

        <div class="flex items-center justify-center gap-5 mt-0 md:mt-10 lg:mt-auto">
          <button
            class="w-[40px] h-[40px] rounded-full border border-secondary flex items-center justify-center text-secondary hover:bg-secondary hover:text-white transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
              </path>
            </svg>
          </button>
          <button
            class="w-[40px] h-[40px] rounded-full bg-secondary flex items-center justify-center text-white hover:opacity-90 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M14 5l7 7m0 0l-7 7m7-7H3">
              </path>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</section>
@endif
