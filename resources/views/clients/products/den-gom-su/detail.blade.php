@php
  $firstImage = collect($product->images ?? [])->first();
  $detailBanner = \App\Support\AssetPath::url($firstImage, 'assets/images/detail-banner.png');
  $variants = $product->phanLoais
      ->where('is_delete', 0)
      ->sortBy('price')
      ->map(fn ($variant) => [
          'name' => $variant->name,
          'variantId' => $variant->phan_loai_den_vuon_gom_su_ct_id,
          'sku' => $variant->code,
          'price' => number_format((float) $variant->price, 0, ',', '.') . 'đ',
      ])
      ->values();
@endphp

<x-client.layouts.main title="Chi tiết Đèn Gốm Sứ" data-page="products" main-class="bg-background-secondary pb-14 md:pb-20" :hide-newsletter="true">
  <section class="hidden md:flex relative w-full h-[180px] md:h-[210px] items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
      <img src="{{ $detailBanner }}" alt="{{ $product->name }} Banner" class="w-full h-full object-cover" />
    </div>
    <div class="relative z-10 text-center text-white px-4 pt-4">
      <h1 class="text-2xl md:text-3xl font-bold mb-2.5 uppercase">
        {{ $product->name }}
      </h1>
      <p class="text-xs md:text-sm text-white/80">
        <a href="{{ route('client.home') }}" class="hover:text-white transition-colors">Trang chủ</a>
        <svg class="w-2.5 h-2.5 inline-block mx-2 fill-current opacity-80" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M11.5797 31.4214C11.1695 31.0111 10.9391 30.4548 10.9391 29.8747C10.9391 29.2946 11.1695 28.7383 11.5797 28.3281L22.4078 17.5L11.5797 6.67184C11.1937 6.25726 10.9836 5.70915 10.9936 5.14283C11.0036 4.5765 11.2328 4.03612 11.6331 3.63539C12.0334 3.23465 12.5735 3.0048 13.1399 2.99421C13.7062 2.98361 14.2545 3.1931 14.6695 3.57858L27.046 15.9516C27.4561 16.3618 27.6865 16.9182 27.6865 17.4983C27.6865 18.0783 27.4561 18.6347 27.046 19.0449L14.6729 31.4214C14.2627 31.8315 13.7064 32.0619 13.1263 32.0619C12.5462 32.0619 11.9899 31.8315 11.5797 31.4214Z" fill="currentColor" />
        </svg>
        <a href="{{ route('client.products.den-gom-su.index') }}" class="hover:text-white transition-colors">Đèn Gốm Sứ</a>
      </p>
    </div>
  </section>

  <div class="hidden md:block w-[85%] max-w-[1320px] mx-auto py-8">
    <x-client.shared.breadcrumb current-label="{{ $product->name }}" parent-label="Đèn Gốm Sứ" parent-href="{{ route('client.products.den-gom-su.index') }}" />
    <hr class="border-t border-black/10 mt-4 w-full" />
  </div>

  <x-client.shared.product-detail-container
    title="{{ $product->name }}"
    price="{{ $product->display_price }}"
    rawPrice="{{ $product->min_price ?? 0 }}"
    sku="{{ $product->display_code ?? '' }}"
    :features="$product->des && is_array($product->des) ? $product->des : null"
    :images="$product->images ?? []"
    :variants="$variants"
    productType="den_vuon_gom_su_ct"
    productId="{{ $product->den_vuon_gom_su_ct_id }}"
  />

  @if($product->size_image || (!empty($product->size_des) && is_array($product->size_des)))
  <section class="w-[85%] max-w-[1320px] mx-auto pb-[40px] md:pb-16" data-aos="fade-up">
    <h2 class="text-[20px] leading-[32px] tracking-[0.6px] md:text-3xl md:leading-normal md:tracking-wide font-semibold text-center text-secondary mb-6 md:mb-12 uppercase break-words">
      Thông tin kích thước
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
      @if($product->size_image)
      <img src="{{ \App\Support\AssetPath::url($product->size_image, 'assets/images/ngoi-01.jpg') }}" alt="Kích thước {{ $product->name }}" class="w-full h-auto object-contain bg-white rounded-sm" />
      @endif
      @if(!empty($product->size_des) && is_array($product->size_des))
      <ul class="list-disc pl-5 space-y-2 text-[#2E2F2A] text-[14px] md:text-lg leading-relaxed">
        @foreach($product->size_des as $item)
        <li>{{ $item }}</li>
        @endforeach
      </ul>
      @endif
    </div>
  </section>
  @endif

  <x-client.shared.journey-video :hide-title="true" />
  <x-client.shared.works />
  <x-client.shared.recommendations
    :related-products="$relatedProducts"
    route-name="client.products.den-gom-su.detail"
    pk-field="den_vuon_gom_su_ct_id"
    product-type="den_vuon_gom_su_ct"
  />
  <x-client.shared.faq-cta-banner />
</x-client.layouts.main>
