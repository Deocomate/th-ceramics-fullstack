@php
  $featuredProducts = is_object($products ?? null) && method_exists($products, 'items')
      ? collect($products->items())->values()
      : collect($products ?? [])->take(8)->values();
  $hasPaginator = is_object($products ?? null)
      && method_exists($products, 'currentPage')
      && method_exists($products, 'lastPage')
      && method_exists($products, 'url');
  $currentPage = $hasPaginator ? $products->currentPage() : 1;
  $lastPage = $hasPaginator ? $products->lastPage() : 1;
  $paginationPages = collect([1, 2, 3, $currentPage - 1, $currentPage, $currentPage + 1, $lastPage])
      ->filter(fn ($page) => $page >= 1 && $page <= $lastPage)
      ->unique()
      ->sort()
      ->values();
@endphp

<section class="w-full md:pb-16 animate-fade-in-up" data-product-section>
  @if($featuredProducts->isEmpty())
  <div class="py-12 text-center text-primary/70">
    Không tìm thấy sản phẩm phù hợp với bộ lọc.
  </div>
  @else
  {{-- Mobile Swiper --}}
  <div class="lg:hidden" data-product-carousel-shell>
    <div class="swiper w-full overflow-hidden" data-product-carousel-swiper>
      <div class="swiper-wrapper">
        @foreach($featuredProducts->chunk(4) as $chunk)
        <article class="swiper-slide w-full pb-1" data-product-slide>
          <div class="grid grid-cols-2 gap-4">
            @foreach($chunk as $product)
              @php
                $productImage = \App\Support\AssetPath::url(collect($product->images ?? [])->first(), 'assets/images/ngoi-01.jpg');
                $productPrice = (float) ($product->price ?? 0);
              @endphp
              <a href="{{ route('client.products.linh-vat-phong-thuy.detail', $product->linh_vat_phong_thuy_ct_id) }}" class="flex flex-col group cursor-pointer">
                <div class="bg-white shadow-[0px_4px_4px_0px_rgba(0,0,0,0.1)] overflow-hidden aspect-square">
                  <img src="{{ $productImage }}" alt="{{ $product->name ?? 'Sản phẩm' }}" class="w-full h-full object-cover mix-blend-multiply" />
                </div>
                <h3 class="mt-3 text-black font-semibold text-[14px] leading-[20px] uppercase">
                  <span class="block lowercase first-letter:uppercase md:uppercase">{{ $product->name ?? 'Sản phẩm' }}</span>
                </h3>
                <p class="text-gray-500 text-[12px] leading-[20px]">MSP: {{ $product->code ?? 'N/A' }}</p>
                <p class="text-secondary font-bold text-[14px] leading-[20px]">{{ $productPrice > 0 ? number_format($productPrice, 0, ',', '.') . ' đ' : 'Liên hệ' }}</p>
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
    @foreach($featuredProducts as $product)
      @php
        $productImage = \App\Support\AssetPath::url(collect($product->images ?? [])->first(), 'assets/images/ngoi-01.jpg');
        $productPrice = (float) ($product->price ?? 0);
      @endphp
      <a href="{{ route('client.products.linh-vat-phong-thuy.detail', $product->linh_vat_phong_thuy_ct_id) }}" class="flex flex-col group cursor-pointer">
        <div class="product-card relative bg-white rounded-sm shadow-lg overflow-hidden mb-4 aspect-square transition-all duration-300 group-hover:-translate-y-1">
          <img src="{{ $productImage }}" alt="{{ $product->name ?? 'Sản phẩm' }}" class="w-full h-full object-cover mix-blend-multiply" />
          <div class="product-overlay">
            <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
            <span>Xem chi tiết</span>
          </div>
        </div>
        <h3 class="text-primary font-semibold text-sm uppercase mb-2 transition-colors group-hover:text-secondary">
          <span class="block lowercase first-letter:uppercase md:uppercase">{{ $product->name ?? 'Sản phẩm' }}</span>
        </h3>
        <p class="text-gray-500 text-[13px] mb-2">MSP: {{ $product->code ?? 'N/A' }}</p>
        <p class="text-secondary font-bold text-[14px]">Giá: {{ $productPrice > 0 ? number_format($productPrice, 0, ',', '.') . ' đ' : 'Liên hệ' }}</p>
      </a>
    @endforeach
  </div>

  @if ($hasPaginator)
  <div
    class="hidden lg:flex items-center justify-between gap-6 mt-16 text-textPrimary font-bold text-[17px]"
  >
    @if($products->onFirstPage())
    <button
      type="button"
      disabled
      class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 transition-all cursor-default"
      aria-label="Trang trước"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
      </svg>
    </button>
    @else
    <a
      href="{{ $products->previousPageUrl() }}"
      class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer"
      aria-label="Trang trước"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
      </svg>
    </a>
    @endif

    <div class="flex items-center gap-5">
      @php $previousPage = null; @endphp
      @foreach($paginationPages as $page)
        @if(! is_null($previousPage) && $page > $previousPage + 1)
          <span class="text-black/40 tracking-widest px-1">...</span>
        @endif

        <a
          href="{{ $products->url($page) }}"
          class="{{ $page === $currentPage ? 'text-black border-b-[3px] border-black pb-[2px]' : 'text-black/40 hover:text-black transition-colors' }} px-1"
          @if($page === $currentPage) aria-current="page" @endif
        >{{ $page }}</a>

        @php $previousPage = $page; @endphp
      @endforeach
    </div>

    @if($products->hasMorePages())
    <a
      href="{{ $products->nextPageUrl() }}"
      class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer"
      aria-label="Trang sau"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
      </svg>
    </a>
    @else
    <button
      type="button"
      disabled
      class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 transition-all cursor-default"
      aria-label="Trang sau"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
      </svg>
    </button>
    @endif
  </div>
  @endif
  @endif
</section>
