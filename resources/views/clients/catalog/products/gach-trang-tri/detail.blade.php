@php
    $sizeImage = \App\Support\AssetPath::url($product->size_image, 'assets/images/gtt-size.webp');
    $productTitle = $product->name ?? 'Gạch Trang Trí';
    $productPrice = (float) ($product->price ?? 0);
    $priceFormatted = $productPrice > 0 ? number_format($productPrice, 0, ',', '.') . ' đ/viên' : 'Liên hệ';
    $productSku = $product->code ?? '';

    $jsonLdImages = \App\Domains\Catalog\Infrastructure\ProductGallery::normalize($product->images ?? [])
        ->where('type', 'image')
        ->map(fn ($item) => \App\Support\AssetPath::url($item['path'] ?? null))
        ->filter()
        ->values()
        ->all();
    $jsonLdDesc = \Illuminate\Support\Str::limit(strip_tags(implode(', ', $product->des ?? [])), 300);
@endphp

<x-client.layouts.main title="{{ $productTitle }} - Gạch Trang Trí | Gốm Sứ Thanh Hải" data-page="products" main-class="bg-background-secondary pb-14 md:pb-20" :hide-newsletter="true">

@push('head')
    @if ($jsonLdDesc)
        <meta name="description" content="{{ $jsonLdDesc }}">
    @endif
    <script type="application/ld+json">
    {!! \Illuminate\Support\Js::encode([
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $productTitle,
        'image' => $jsonLdImages,
        'description' => $jsonLdDesc,
        'sku' => $productSku,
        'brand' => ['@type' => 'Brand', 'name' => 'Gốm Sứ Thanh Hải'],
        'offers' => [
            '@type' => 'Offer',
            'url' => route('client.products.gach-trang-tri.detail', $product->gach_trang_tri_ct_id),
            'priceCurrency' => 'VND',
            'price' => (string) $productPrice,
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
        link-class="hover:text-primary transition-colors" separator-class="mx-1"
        parent-href="{{ route('client.products.gach-trang-tri.index') }}"
        parent-label="Gạch Trang Trí"
        current-class="text-primary font-semibold pb-1"
        current-label="{{ $productTitle }}" />
    <hr class="border-t border-black/10 mt-4 w-full" />
</div>

<!-- Product Detail Container -->
<x-client.catalog.shared.product-detail-container
    title="{{ $productTitle }}"
    price="{{ $priceFormatted }}"
    rawPrice="{{ $productPrice }}"
    sku="{{ $productSku }}"
    :features="$product->des ?? []"
    :images="$product->images ?? []"
    productType="gach_trang_tri_ct"
    productId="{{ $product->gach_trang_tri_ct_id }}"
/>

<x-client.catalog.shared.journey-video :video="$journeyVideo ?? null" :hide-title="true" />
<x-client.content.shared.works-simple :show-nav="true" />
<x-client.catalog.shared.quantity-calculator
    :image="$sizeImage"
    :dinhMuc="$dinhMuc"
    :rate="(float) ($product->dinh_muc ?? $dinhMuc->first()?->value ?? 25)" />
<x-client.content.shared.fabrication-process :images="$config?->images ?? []" />
<x-client.content.shared.outstanding-value />
<x-client.content.shared.custom-design-process :images="$config && is_array($config->images) ? $config->images : []" />
<hr class="md:mb-16 mb-8" />
<x-client.catalog.shared.recommendations
    :related-products="$relatedProducts"
    :show-decor="true"
    route-name="client.products.gach-trang-tri.detail"
    pk-field="gach_trang_tri_ct_id"
    product-type="gach_trang_tri_ct"
/>
<x-client.shared.faq-cta-banner />
<x-client.catalog.shared.weight-calculator-sticky-bar />

</x-client.layouts.main>
