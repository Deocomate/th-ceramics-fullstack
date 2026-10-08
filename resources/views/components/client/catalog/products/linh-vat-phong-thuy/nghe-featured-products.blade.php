@props([
    'products' => null,
])
@php
  $featuredProducts = is_object($products ?? null) && method_exists($products, 'items')
      ? collect($products->items())->values()
      : collect($products ?? [])->take(8)->values();
  $hasPaginator = is_object($products ?? null)
      && method_exists($products, 'currentPage')
      && method_exists($products, 'lastPage')
      && method_exists($products, 'url');
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
                $productImage = \App\Support\AssetPath::url(\App\Domains\Catalog\Infrastructure\ProductGallery::firstImagePath($product->images ?? []), 'assets/images/ngoi-01.jpg');
                $productPrice = (float) ($product->price ?? 0);
              @endphp
              <x-client.catalog.shared.product-card
                href="{{ route('client.products.linh-vat-phong-thuy.detail', $product->linh_vat_phong_thuy_ct_id) }}"
                image="{{ $productImage }}"
                title="{{ $product->name ?? 'Sản phẩm' }}"
                code="MSP: {{ $product->code ?: 'Đang cập nhật' }}"
                price="{{ $productPrice > 0 ? number_format($productPrice, 0, ',', '.') . ' đ' : 'Liên hệ' }}"
                detail-route-name="client.products.linh-vat-phong-thuy.detail"
                :product="$product"
              />
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
        $productImage = \App\Support\AssetPath::url(\App\Domains\Catalog\Infrastructure\ProductGallery::firstImagePath($product->images ?? []), 'assets/images/ngoi-01.jpg');
        $productPrice = (float) ($product->price ?? 0);
      @endphp
      <x-client.catalog.shared.product-card
        href="{{ route('client.products.linh-vat-phong-thuy.detail', $product->linh_vat_phong_thuy_ct_id) }}"
        image="{{ $productImage }}"
        title="{{ $product->name ?? 'Sản phẩm' }}"
        code="MSP: {{ $product->code ?: 'Đang cập nhật' }}"
        price="{{ $productPrice > 0 ? number_format($productPrice, 0, ',', '.') . ' đ' : 'Liên hệ' }}"
        :show-overlay="true"
        detail-route-name="client.products.linh-vat-phong-thuy.detail"
        :product="$product"
      />
    @endforeach
  </div>

  @if ($hasPaginator)
    <x-client.shared.custom-pagination :paginator="$products" />
  @endif
  @endif
</section>
