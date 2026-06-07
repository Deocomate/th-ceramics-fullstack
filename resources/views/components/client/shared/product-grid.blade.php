@props(['products' => collect(), 'category' => null, 'routeName' => null, 'pkField' => null, 'productType' => null])

@php
  $typeByRoute = [
      'client.products.ngoi-am-duong.detail' => 'ngoi_am_duong_ct',
      'client.products.ngoi-hai-van-mieu.detail' => 'ngoi_hai_van_mieu_ct',
      'client.products.ngoi-hai-co.detail' => 'ngoi_hai_co_ct',
      'client.products.gach-hoa-thong-gio.detail' => 'gach_hoa_thong_gio_ct',
      'client.products.gach-trang-tri.detail' => 'gach_trang_tri_ct',
      'client.products.gach-co-bat-trang.detail' => 'gach_co_bat_trang_ct',
      'client.products.linh-vat-phong-thuy.detail' => 'linh_vat_phong_thuy_ct',
  ];
  $resolvedProductType = $productType ?: ($typeByRoute[$routeName] ?? null);
@endphp

<section
  class="w-[85%] max-w-[1320px] mx-auto pb-[43px] md:pb-16 animate-fade-in-up"
>
  <div
    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-[16px] gap-y-[30px] sm:gap-x-6 sm:gap-y-14"
    data-aos="fade-up"
    data-aos-delay="200"
  >
    @forelse ($products as $product)
    @php
      $productId = $pkField ? data_get($product, $pkField) : $product->getKey();
      $detailUrl = ($routeName && $productId && \Illuminate\Support\Facades\Route::has($routeName))
          ? route($routeName, $productId)
          : '#';
      $firstImage = collect($product->images ?? [])->first();
      $imageUrl = \App\Support\AssetPath::url($firstImage, 'assets/images/ngoi-01.jpg');
      $price = (float) ($product->price ?? 0);
    @endphp
      <div class="flex flex-col group cursor-pointer">
        <a href="{{ $detailUrl }}" class="flex flex-col flex-grow">
          <div class="product-card relative bg-white rounded-sm overflow-hidden mb-3 md:mb-5 transition-all duration-300 group-hover:-translate-y-1 aspect-square shadow-lg">
            <img
              src="{{ $imageUrl }}"
              alt="{{ $product->name ?? '' }}"
              class="w-full h-full object-cover mix-blend-multiply"
              onerror="this.onerror=null; this.src='{{ asset('assets/images/ngoi-01.jpg') }}'"
            />
            <div class="product-overlay">
              <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
              <span>Xem chi tiết</span>
            </div>
          </div>
          <h3 class="text-[#101010] font-medium text-[16px] leading-[25px] uppercase mb-1 transition-colors group-hover:text-secondary">
            <span class="block lowercase first-letter:uppercase md:uppercase">
              {{ $product->name ?? '' }}
            </span>
          </h3>
          <p class="text-[#3C4043] font-light text-[14px] leading-[25px] mb-1">
            MSP: {{ $product->code ?? '' }}
          </p>
          <p class="text-secondary font-bold text-[15px] leading-[15px]">
            Giá: {{ $price > 0 ? number_format($price) . 'đ' : 'Liên hệ' }}
          </p>
        </a>

        @if($resolvedProductType && $productId && $price > 0)
          <div class="mt-2">
            <button
              type="button"
              class="js-add-to-cart border border-secondary text-secondary text-[11px] md:text-[13px] font-bold py-1.5 px-4 rounded-full hover:bg-secondary hover:text-white transition-all mt-3 self-start"
              data-product-type="{{ $resolvedProductType }}"
              data-product-id="{{ $productId }}"
              data-product-name="{{ $product->name ?? '' }}"
              onclick="event.stopPropagation();"
            >
              Thêm vào giỏ
            </button>
          </div>
        @endif
      </div>
    @empty
    <div class="col-span-full text-center py-12 text-gray-500">Chưa có sản phẩm nào.</div>
    @endforelse
  </div>

  @if ($products && method_exists($products, 'lastPage'))
    @php
      $currentPage = $products->currentPage();
      $lastPage = $products->lastPage();
      $windowStart = max(2, $currentPage - 1);
      $windowEnd = min($lastPage - 1, $currentPage + 1);
      $pages = [1];

      if ($windowStart > 2) {
          $pages[] = '...';
      }

      for ($page = $windowStart; $page <= $windowEnd; $page++) {
          $pages[] = $page;
      }

      if ($windowEnd < $lastPage - 1) {
          $pages[] = '...';
      }

      if ($lastPage > 1) {
          $pages[] = $lastPage;
      }
    @endphp
    <nav
      class="flex items-center justify-between gap-6 mt-[40px] md:mt-16 text-textPrimary font-bold text-[17px]"
      aria-label="Pagination"
    >
      @if ($products->onFirstPage())
      <span class="w-10 h-10 border-2 border-black/10 rounded-full flex items-center justify-center text-black/20 cursor-not-allowed" aria-disabled="true">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
      </span>
      @else
      <a href="{{ $products->previousPageUrl() }}" class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer" rel="prev">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
      </a>
      @endif

      <div class="flex items-center gap-5">
        @foreach ($pages as $page)
          @if (is_string($page))
          <span class="text-black/40 tracking-widest px-1">{{ $page }}</span>
          @elseif ($page == $currentPage)
          <span class="text-black border-b-[3px] border-black pb-[2px] px-1" aria-current="page">{{ $page }}</span>
          @else
          <a href="{{ $products->url($page) }}" class="text-black/40 hover:text-black transition-colors px-1">{{ $page }}</a>
          @endif
        @endforeach
      </div>

      @if ($products->hasMorePages())
      <a href="{{ $products->nextPageUrl() }}" class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer" rel="next">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
      </a>
      @else
      <span class="w-10 h-10 border-2 border-black/10 rounded-full flex items-center justify-center text-black/20 cursor-not-allowed" aria-disabled="true">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
      </span>
      @endif
    </nav>
  @endif
</section>
