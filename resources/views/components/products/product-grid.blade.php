@props(['products' => collect(), 'category' => null, 'routeName' => null, 'pkField' => null, 'productType' => null])

@php
  $typeByRoute = [
      'client.products.ngoi-am-duong.detail' => 'ngoi_am_duong_ct',
      'client.products.ngoi-hai-van-mieu.detail' => 'ngoi_hai_van_mieu_ct',
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
    <article class="flex flex-col group">
      <a href="{{ $detailUrl }}" class="flex flex-col cursor-pointer">
        <div
          class="product-card relative bg-white rounded-sm shadow-lg overflow-hidden mb-4 aspect-square transition-all duration-300 group-hover:-translate-y-1"
        >
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
        <h3
          class="text-primary font-semibold text-sm uppercase mb-2 transition-colors group-hover:text-secondary"
        >
          <span class="block lowercase first-letter:uppercase md:uppercase"
            >{{ $product->name ?? '' }}</span
          >
        </h3>
        <p class="text-gray-500 text-[13px] mb-2">MSP: {{ $product->code ?? '' }}</p>
        <p class="text-secondary font-bold text-[14px]">Giá: {{ $price > 0 ? number_format($price) . 'đ' : 'Liên hệ' }}</p>
      </a>
      @if ($resolvedProductType && $productId && $price > 0)
      <button
        type="button"
        class="js-add-to-cart mt-3 self-start border border-secondary text-secondary text-[11px] md:text-[13px] font-bold py-1.5 px-4 rounded-full hover:bg-secondary hover:text-white transition-all"
        data-product-type="{{ $resolvedProductType }}"
        data-product-id="{{ $productId }}"
        data-product-name="{{ $product->name ?? '' }}"
      >
        Thêm vào giỏ
      </button>
      @endif
    </article>
    @empty
    <div class="col-span-full text-center py-12 text-gray-500">Chưa có sản phẩm nào.</div>
    @endforelse
  </div>

  @if (method_exists($products, 'links') && $products->hasPages())
  <div class="mt-[40px] md:mt-16">
    {{ $products->withQueryString()->links() }}
  </div>
  @endif
</section>
