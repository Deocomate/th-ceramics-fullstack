@php
  $assetUrl = fn ($path, $fallback = null) => \App\Support\AssetPath::url($path, $fallback);
  $variants = $phanLoais
      ->map(fn ($variant) => [
          'name' => $variant->name,
          'variantId' => $variant->phan_loai_phu_kien_ngoi_ct_id,
          'sku' => $variant->code,
          'price' => $variant->price,
          'priceFormatted' => number_format((float) $variant->price, 0, ',', '.') . ' đ/m²',
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

  <section class="w-[85%] max-w-[1320px] mx-auto pb-16 lg:pb-24 pt-0 md:pt-4">
    <div class="text-center mb-8 md:mb-16" data-aos="fade-up">
      <h3 class="text-[20px] md:text-3xl font-semibold text-secondary uppercase drop-shadow-sm">
        Mô tả sản phẩm
      </h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-16 items-center">
      <div class="flex items-center justify-center" data-aos="fade-right">
        <img src="{{ $assetUrl($product->size_image, 'assets/images/gach-bat-size-1.png') }}" alt="Mô tả kích thước {{ $product->name }}" class="w-full max-w-[550px] object-contain" />
      </div>
      <div class="flex flex-col justify-center" data-aos="fade-left">
        <ul class="list-disc pl-5 md:pl-16 space-y-3 md:space-y-4 text-primary font-medium text-[15px] md:text-[20px] leading-relaxed">
          @forelse(($product->des ?? []) as $desc)
            <li>{{ $desc }}</li>
          @empty
            <li>Timeless beauty to be treasured</li>
            <li>High-quality and classic design, suitable for decoration</li>
            <li>Beginner friendly and improves intelligence</li>
          @endforelse
        </ul>
      </div>
    </div>
  </section>

  <x-client.shared.works-simple :show-nav="true" />
  <x-client.shared.outstanding-value />
  <x-client.shared.journey-video />
  <x-client.shared.recommendations
    :related-products="$relatedProducts"
    route-name="client.products.phu-kien-ngoi.detail"
    pk-field="phu_kien_ngoi_ct_id"
    product-type="phu_kien_ngoi_ct"
  />
  <x-client.shared.faq-cta-banner />
</x-client.layouts.main>
