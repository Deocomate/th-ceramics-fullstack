@php
    $pageLabel = $pageLabel ?? 'Ngói Hài Văn Miếu';
    $productTitle = $product->name ?? $pageLabel;
    $indexRouteName = $indexRouteName ?? 'client.products.ngoi-hai-van-mieu.index';
    $detailRouteName = $detailRouteName ?? 'client.products.ngoi-hai-van-mieu.detail';
    $productType = $productType ?? 'ngoi_hai_van_mieu_ct';
    $productPkField = $productPkField ?? 'ngoi_hai_van_mieu_ct_id';
    $variantPkField = $variantPkField ?? 'mau_sac_ngoi_hai_van_mieu_ct_id';
    $productDetailId = data_get($product, $productPkField);
    $firstVariant = collect($colors ?? [])->first();
    $productPrice = (float) (data_get($firstVariant, 'price') ?? data_get($product, 'price', 0));
    $productSku = data_get($firstVariant, 'code') ?: data_get($product, 'code');
    $priceLabel = $productPrice > 0 ? number_format($productPrice, 0, ',', '.') . ' đ/m²' : 'Liên hệ';
    $sizeImage = \App\Support\AssetPath::url(data_get($product, 'size_image'), 'assets/images/gach-bat-size-1.png');
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
        'sku' => $productSku ?: '',
        'brand' => ['@type' => 'Brand', 'name' => 'Gốm Sứ Thanh Hải'],
        'offers' => [
            '@type' => 'Offer',
            'url' => route($detailRouteName, $productDetailId),
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
    <x-client.shared.breadcrumb :current-label="$productTitle" parent-label="Sản phẩm" parent-href="{{ route($indexRouteName) }}" />
    <hr class="border-t border-black/10 mt-4 w-full" />
</div>

<!-- Product Detail Container -->
<x-client.catalog.shared.product-detail-container
    :title="$productTitle"
    sku="{{ $productSku ?: 'Đang cập nhật' }}"
    price="{{ $priceLabel }}"
    rawPrice="{{ $productPrice }}"
    :images="$product->images ?? []"
    :features="$product->des ?? []"
    :colors="$colors->map(fn($c) => [
        'name' => $c->name,
        'colorCode' => '#D9D9D9',
        'image' => $c->image ? \App\Support\AssetPath::url($c->image) : null,
        'variantId' => data_get($c, $variantPkField),
        'sku' => $c->code,
        'price' => $c->price,
        'priceFormatted' => ((float) $c->price > 0 ? number_format((float) $c->price, 0, ',', '.') . ' đ/m²' : 'Liên hệ'),
    ])->toArray()"
    productType="{{ $productType }}"
    productId="{{ $productDetailId }}"
/>

<x-client.catalog.shared.journey-video :video="$journeyVideo ?? null" :hide-title="true" />
<x-client.content.shared.works-simple :show-nav="true" />
<x-client.catalog.products.ngoi-hai-van-mieu.calculator :image="$sizeImage" :label1="'Ngói trên mái gỗ'" :rate1="$dinhMuc->first() && $dinhMuc->first()->ngoi_tren_mai_go ? $dinhMuc->first()->ngoi_tren_mai_go . ' viên/m²' : '125 viên/m²'" :label2="'Ngói trên mái bê tông'" :rate2="$dinhMuc->first() && $dinhMuc->first()->ngoi_tren_mai_be_tong ? $dinhMuc->first()->ngoi_tren_mai_be_tong . ' viên/m²' : '75 viên/m²'" />
<x-client.content.shared.fabrication-process :images="$parentConfig?->images ?? []" />
<x-client.content.shared.outstanding-value />
<x-client.content.shared.custom-design-process />
<hr class="md:mb-16 mb-8" />
<x-client.catalog.shared.recommendations
    :related-products="$relatedProducts"
    route-name="{{ $detailRouteName }}"
    pk-field="{{ $productPkField }}"
    product-type="{{ $productType }}"
    :compare-table="true"
/>
<x-client.shared.faq-cta-banner />
<x-client.catalog.shared.weight-calculator-sticky-bar />

</x-client.layouts.main>
