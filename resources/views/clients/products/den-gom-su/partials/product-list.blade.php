@php
  $items = $products instanceof \Illuminate\Contracts\Pagination\Paginator
      ? collect($products->items())
      : collect($products ?? []);
  $mobileChunks = $items->chunk(4)->values();
  $sectionId = $sectionId ?? 'den-gom-su-products';
@endphp

<section class="w-[85%] max-w-[1320px] mx-auto pb-[43px] md:pb-16 animate-fade-in-up" data-product-section id="{{ $sectionId }}">
  <div class="lg:hidden" data-product-carousel-shell>
    @if($items->isNotEmpty())
    <div
      class="flex overflow-x-auto scroll-smooth snap-x snap-mandatory scrollbar-hide"
      data-product-carousel
    >
      @foreach($mobileChunks as $chunk)
      <article class="flex-shrink-0 w-full snap-start pb-1" data-product-slide>
        <div class="grid grid-cols-2 gap-4">
          @foreach($chunk as $product)
          @php
            $firstImage = collect($product->images ?? [])->first();
            $imageUrl = \App\Support\AssetPath::url($firstImage, 'assets/images/ngoi-01.jpg');
          @endphp
          <x-products.product-card
            href="{{ route('client.products.den-gom-su.detail', $product->den_vuon_gom_su_ct_id) }}"
            image="{{ $imageUrl }}"
            alt="{{ $product->name }}"
            title="{{ $product->name }}"
            code="MSP: {{ $product->display_code ?? 'Đang cập nhật' }}"
            price="{{ $product->display_price }}"
            wrapper-class="flex flex-col group cursor-pointer"
            image-container-class="bg-white shadow-[0px_4px_4px_0px_rgba(0,0,0,0.1)] overflow-hidden aspect-square"
            title-class="mt-3 text-black font-semibold text-[14px] leading-[20px] uppercase"
            code-class="text-gray-500 text-[12px] leading-[20px]"
            price-class="text-secondary font-bold text-[14px] leading-[20px]"
          />
          @endforeach
        </div>
      </article>
      @endforeach
    </div>

    @if($mobileChunks->count() > 1)
    <div class="mt-5 flex justify-center gap-[7px]" aria-label="Product mobile pagination">
      @foreach($mobileChunks as $index => $chunk)
      <button
        type="button"
        class="product-section-dot h-2 w-2 rounded-full {{ $loop->first ? 'bg-secondary' : 'bg-[#C76E00]/30' }}"
        data-product-dot="{{ $index }}"
        aria-label="Slide {{ $index + 1 }}"
        aria-current="{{ $loop->first ? 'true' : 'false' }}"
      ></button>
      @endforeach
    </div>
    @endif
    @else
    <div class="py-10 text-center text-gray-500">Chưa có sản phẩm nào.</div>
    @endif
  </div>

  <div
    class="hidden lg:grid grid-cols-4 gap-x-[16px] gap-y-[30px] sm:gap-x-6 sm:gap-y-14"
    data-aos="fade-up"
    data-aos-delay="200"
  >
    @forelse($items as $product)
    @php
      $firstImage = collect($product->images ?? [])->first();
      $imageUrl = \App\Support\AssetPath::url($firstImage, 'assets/images/ngoi-01.jpg');
    @endphp
    <x-products.product-card
      href="{{ route('client.products.den-gom-su.detail', $product->den_vuon_gom_su_ct_id) }}"
      image="{{ $imageUrl }}"
      alt="{{ $product->name }}"
      title="{{ $product->name }}"
      code="MSP: {{ $product->display_code ?? 'Đang cập nhật' }}"
      price="{{ $product->display_price }}"
      price-prefix="Giá"
      :show-overlay="true"
    />
    @empty
    <div class="col-span-full py-12 text-center text-gray-500">Chưa có sản phẩm nào.</div>
    @endforelse
  </div>

  <x-products.custom-pagination :paginator="$products" />
</section>
