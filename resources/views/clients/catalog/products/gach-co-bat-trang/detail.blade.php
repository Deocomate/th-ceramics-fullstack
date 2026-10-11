@php
    $productTitle = $product->name ?? 'Gạch Cổ Bát Tràng';
    $sizeImage = \App\Support\AssetPath::url($product->size_image, 'assets/images/gtt-size.webp');
    $productImages = collect($product->images ?? [])->map(fn($img) => \App\Support\AssetPath::url($img))->values()->all();
    $metaDesc = !empty($product->des) && is_array($product->des) ? implode('. ', $product->des) : $productTitle . ' - Gốm Sứ Thanh Hải';
@endphp

<x-client.layouts.main :title="$productTitle" data-page="products" main-class="bg-background-secondary pb-14 md:pb-20" :hide-newsletter="true">

@push('head')
    <meta name="description" content="{{ $metaDesc }}">
    <script type="application/ld+json">
    {!! \Illuminate\Support\Js::encode([
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $productTitle,
        'image' => $productImages,
        'description' => $metaDesc,
        'sku' => $product->code ?? '',
        'brand' => ['@type' => 'Brand', 'name' => 'Gốm Sứ Thanh Hải'],
        'offers' => [
            '@type' => 'Offer',
            'url' => route('client.products.gach-co-bat-trang.detail', $product->gach_co_bat_trang_ct_id),
            'priceCurrency' => 'VND',
            'price' => (string) ($product->price ?? 0),
            'availability' => 'https://schema.org/InStock',
            'seller' => ['@type' => 'Organization', 'name' => 'Gốm Sứ Thanh Hải'],
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@push('styles')
<style>
    @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Italianno&display=swap");
</style>
@endpush

<!-- Sub Breadcrumb -->
<div class="hidden md:block w-[85%] max-w-[1320px] mx-auto py-8">
    <x-client.shared.breadcrumb text-class="font-semibold text-primary/60 uppercase text-[14px] md:text-base"
        link-class="hover:text-primary transition-colors" separator-class="mx-1" parent-href="{{ route('client.products.gach-co-bat-trang.index') }}"
        parent-label="Sản phẩm" current-class="text-primary font-semibold pb-1" :current-label="$productTitle" />
    <hr class="border-t border-black/10 mt-4 w-full" />
</div>

<!-- Product Detail Container -->
<x-client.catalog.shared.product-detail-container
    :title="$productTitle"
    price="{{ $product->price > 0 ? number_format($product->price, 0, ',', '.') . ' đ/viên' : 'Liên hệ' }}"
    rawPrice="{{ $product->price }}"
    sku="{{ $product->code ?? '' }}"
    :features="$product->des ?? []"
    :images="$product->images ?? []"
    productType="gach_co_bat_trang_ct"
    productId="{{ $product->gach_co_bat_trang_ct_id }}"
/>

<x-client.catalog.shared.journey-video :video="$journeyVideo ?? null" :hide-title="true" />
<x-client.content.shared.works-simple :show-nav="true" />
<x-client.catalog.shared.quantity-calculator
    :image="$sizeImage"
    :dinhMuc="$dinhMuc"
    :rate="$dinhMuc->first()?->value" />
<x-client.content.shared.fabrication-process />
<x-client.content.shared.outstanding-value />
<x-client.content.shared.custom-design-process />
<hr class="md:mb-16 mb-8" />
<x-client.catalog.shared.recommendations
    :related-products="$relatedProducts"
    :show-decor="true"
    route-name="client.products.gach-co-bat-trang.detail"
    pk-field="gach_co_bat_trang_ct_id"
    product-type="gach_co_bat_trang_ct"
/>
<x-client.shared.faq-cta-banner />
<x-client.catalog.shared.weight-calculator-sticky-bar />

</x-client.layouts.main>
