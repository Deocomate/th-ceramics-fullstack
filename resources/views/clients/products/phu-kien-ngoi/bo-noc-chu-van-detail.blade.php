@php
  $assetUrl = fn ($path, $fallback = null) => \App\Support\AssetPath::url($path, $fallback);
  $variants = $phanLoais
      ->map(fn ($variant) => [
          'name' => $variant->name,
          'variantId' => $variant->phan_loai_phu_kien_ngoi_ct_id,
          'sku' => $variant->code,
          'price' => $variant->price,
          'priceFormatted' => number_format((float) $variant->price, 0, ',', '.') . ' đ/m²',
          'class' => str_contains(mb_strtolower($variant->name), 'combo') ? 'w-[54%] md:w-auto md:min-w-[240px]' : 'flex-1 md:w-auto md:min-w-[200px]',
      ])
      ->values();
  $firstVariant = $variants->first();
@endphp

<x-client.layouts.main :title="$product->name" :description="implode(', ', $product->des ?? [])" data-page="products" main-class="bg-background-secondary pb-14 md:pb-20" :hide-newsletter="true">
  <section class="relative w-full hidden md:block">
    <div class="relative w-full aspect-[4/3] md:aspect-[8/6] lg:aspect-auto h-full lg:[clip-path:inset(40px_0_0_0)] lg:-mt-[40px]">
      <img src="{{ $assetUrl($pageConfig->thumbnail_main ?? null, 'assets/images/pk-banner.png') }}" alt="Phụ Kiện Ngói" class="w-full h-full object-cover" />
      <div class="absolute inset-0 flex flex-col items-center pt-[5%] md:pt-[5%] lg:pt-[5%]" data-aos="fade-up" data-aos-delay="100">
        <div class="text-center text-white px-4 w-[85%] max-w-[1320px] mx-auto">
          <h1 class="font-archivo text-[26px] md:text-4xl lg:text-[44px] font-bold uppercase mb-2 md:mb-6 drop-shadow-md break-words">
            PHỤ KIỆN NGÓI
          </h1>
          <p class="font-italianno text-xl md:text-[34px] lg:text-[48px] font-normal leading-none tracking-wide drop-shadow-sm text-white/95 break-words">
            {{ $pageConfig->banner_text_1 ?? 'Nâng niu nét chạm trổ, sắt son cùng thời gian' }}
          </p>
          <p class="font-italianno text-xl md:text-[34px] lg:text-[48px] font-normal leading-none tracking-wide drop-shadow-sm text-white/95 break-words">
            {{ $pageConfig->banner_text_2 ?? 'Phụ kiện Thanh Hải: Điểm nhấn tâm linh, hoàn thiện dáng hình kiến trúc Việt' }}
          </p>
        </div>
      </div>
    </div>
  </section>

  <div class="hidden md:block w-[85%] max-w-[1320px] mx-auto py-8">
    <x-client.shared.breadcrumb
      parent-href="{{ route('client.products.phu-kien-ngoi.index') }}"
      parent-label="Sản phẩm"
      current-label="PHỤ KIỆN NGÓI" />
    <hr class="border-t border-black/10 mt-4 w-full" />
  </div>

  <x-client.shared.product-detail-container
    title="{{ $product->name }}"
    price="{{ $firstVariant ? $firstVariant['priceFormatted'] : 'Liên hệ' }}"
    rawPrice="{{ $firstVariant['price'] ?? 0 }}"
    sku="{{ $firstVariant['sku'] ?? \App\Models\PhuKienNgoiCt::categoryCodePrefix($product->category_type) . $product->phu_kien_ngoi_ct_id }}"
    :features="$product->des ?? null"
    :images="$product->images ?? []"
    :variants="$variants"
    productType="phu_kien_ngoi_ct"
    productId="{{ $product->phu_kien_ngoi_ct_id }}"
  />

  <section class="w-full md:w-[85%] max-w-[1320px] mx-auto pb-16 lg:pb-20">
    <div class="text-center mb-8" data-aos="fade-up">
      <h2 class="text-[20px] md:text-3xl font-semibold text-secondary uppercase drop-shadow-sm">
        Bộ ngói bò chữ vạn và phụ kiện
      </h2>
    </div>
    <div class="flex flex-col gap-8 md:gap-12">
      @if(!empty($product->images) && is_array($product->images))
        @foreach($product->images as $image)
          <img src="{{ $assetUrl($image) }}" alt="Chi tiết {{ $product->name }}" class="w-full {{ $loop->first ? 'block' : 'hidden md:block' }} select-none pointer-events-none" data-aos="fade-up" />
        @endforeach
      @else
        <img src="{{ asset('assets/images/chu-van-1.png') }}" alt="Bộ ngói bò chữ vạn và phụ kiện" class="w-full hidden md:block select-none pointer-events-none" data-aos="fade-up" />
        <img src="{{ asset('assets/images/chu-van-2.png') }}" alt="Bộ ngói bò chữ vạn và phụ kiện" class="w-full hidden md:block select-none pointer-events-none" data-aos="fade-up" />
        <img src="{{ asset('assets/images/chu-van-3.png') }}" alt="Bộ ngói bò chữ vạn và phụ kiện" class="w-full hidden md:block select-none pointer-events-none" data-aos="fade-up" />
        <img src="{{ asset('assets/images/chu-van-mobile.png') }}" alt="Bộ ngói bò chữ vạn và phụ kiện" class="w-[90%] mx-auto block md:hidden select-none pointer-events-none" data-aos="fade-up" />
      @endif
    </div>
  </section>

  <x-client.shared.outstanding-value />
  <x-client.shared.journey-video />
  <x-client.shared.works />
  <x-client.shared.recommendations
    :related-products="$relatedProducts"
    route-name="client.products.phu-kien-ngoi.detail"
    pk-field="phu_kien_ngoi_ct_id"
    product-type="phu_kien_ngoi_ct"
  />
  <x-client.shared.faq-cta-banner />
</x-client.layouts.main>
