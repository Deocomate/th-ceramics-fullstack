@php
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

  <x-client.shared.journey-video :video="$journeyVideo ?? null" :hide-title="true" />
  <x-client.shared.works />

  @if($product->size_image || (!empty($product->size_des) && is_array($product->size_des)))
  <section id="bang-kich-thuoc" class="w-[85%] max-w-[1320px] mx-auto pb-[40px] md:pb-16" data-aos="fade-up">
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

  <x-client.shared.outstanding-value />
  <x-client.shared.recommendations
    :related-products="$relatedProducts"
    route-name="client.products.den-gom-su.detail"
    pk-field="den_vuon_gom_su_ct_id"
    product-type="den_vuon_gom_su_ct"
  />
  <x-client.shared.faq-cta-banner />
</x-client.layouts.main>
