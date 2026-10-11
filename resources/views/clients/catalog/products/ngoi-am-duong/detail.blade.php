@php
    $productTitle = $product->name ?? 'Ngói Âm Dương';
    $sizeImage = \App\Support\AssetPath::url($product->size_image, 'assets/images/ngoi-am-duong-size.webp');
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
            'url' => route('client.products.ngoi-am-duong.detail', $product->ngoi_am_duong_ct_id),
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

    .size-options-scroll {
        scroll-behavior: smooth;
    }
</style>
@endpush

<!-- Sub Breadcrumb -->
<div class="hidden md:block w-[85%] max-w-[1320px] mx-auto py-8">
    <x-client.shared.breadcrumb :current-label="$productTitle" parent-label="Sản phẩm" parent-href="{{ route('client.products.ngoi-am-duong.index') }}" />
    <hr class="border-t border-black/10 mt-4 w-full" />
</div>

<!-- Product Detail Container -->
<x-client.catalog.shared.product-detail-container
    :title="$productTitle"
    price="{{ $product->price > 0 ? number_format($product->price, 0, ',', '.') . ' đ/m²' : 'Liên hệ' }}"
    rawPrice="{{ $product->price }}"
    sku="{{ $product->code ?? '' }}"
    :features="$product->des ?? null"
    :images="$product->images ?? []"
    productType="ngoi_am_duong_ct"
    productId="{{ $product->ngoi_am_duong_ct_id }}"
/>

<x-client.catalog.shared.journey-video :video="$journeyVideo ?? null" :hide-title="true" />

<x-client.content.shared.works />

<section id="bang-kich-thuoc" class="w-[85%] max-w-[1320px] mx-auto pb-[40px] md:pb-16 pt-1" data-aos="fade-up">
    <h2
        class="text-[20px] leading-[32px] tracking-[0.6px] md:text-3xl md:leading-normal md:tracking-wide font-semibold text-center text-secondary mb-6 md:mb-12 uppercase break-words">
        Bảng kích thước
    </h2>
    <div class="size-options-scroll mobile-scroll-visible w-full pb-2 overflow-x-scroll md:overflow-x-hidden">
        <img src="{{ $sizeImage }}" alt="Bảng kích thước {{ $productTitle }}"
            class="h-auto object-contain max-w-none w-[200%] md:w-full"
            onload="window.dispatchEvent(new Event('resize'))" />
    </div>
</section>

@if ($colors->isNotEmpty())
    <x-client.catalog.shared.color-palette :colors="$colors" />
@endif

<x-client.shared.product-guide-tabs>
    <x-slot:install>
        <x-client.catalog.products.ngoi-am-duong.installation-guide :hide-title="true" />
    </x-slot:install>
    <x-slot:applications>
        <x-client.catalog.products.ngoi-am-duong.applications :hide-title="true" />
    </x-slot:applications>
</x-client.shared.product-guide-tabs>

<x-client.catalog.products.ngoi-am-duong.weight-calculator :dinh-muc="$dinhMuc" />

<x-client.content.shared.outstanding-value />

<x-client.catalog.shared.recommendations
    :related-products="$relatedProducts"
    route-name="client.products.ngoi-am-duong.detail"
    pk-field="ngoi_am_duong_ct_id"
    product-type="ngoi_am_duong_ct"
/>
<x-client.shared.faq-cta-banner />

<x-client.catalog.shared.weight-calculator-sticky-bar />

</x-client.layouts.main>
