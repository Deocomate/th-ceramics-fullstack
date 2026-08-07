@php
    $pageLabel = $pageLabel ?? 'Ngói Hài Văn Miếu';
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
@endphp

<x-client.layouts.main title="{{ $pageLabel }}" data-page="products" main-class="bg-background-secondary pb-14 md:pb-20" :hide-newsletter="true">

@push('styles')
<style>
    @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Italianno&display=swap");
</style>
@endpush

<!-- Sub Breadcrumb -->
<div class="hidden md:block w-[85%] max-w-[1320px] mx-auto py-8">
    <x-client.shared.breadcrumb current-label="{{ $pageLabel }}" parent-label="Sản phẩm" parent-href="{{ route($indexRouteName) }}" />
    <hr class="border-t border-black/10 mt-4 w-full" />
</div>

<!-- Product Detail Container -->
<x-client.shared.product-detail-container
    title="{{ $product->name ?? $pageLabel }}"
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

<x-client.products.ngoi-hai-van-mieu.calculator :image="$sizeImage" :label1="'Ngói trên mái gỗ'" :rate1="$dinhMuc->first() && $dinhMuc->first()->ngoi_tren_mai_go ? $dinhMuc->first()->ngoi_tren_mai_go . ' viên/m²' : '125 viên/m²'" :label2="'Ngói trên mái bê tông'" :rate2="$dinhMuc->first() && $dinhMuc->first()->ngoi_tren_mai_be_tong ? $dinhMuc->first()->ngoi_tren_mai_be_tong . ' viên/m²' : '75 viên/m²'" />

<x-client.shared.fabrication-process :images="$parentConfig?->images ?? []" />
<x-client.shared.outstanding-value />
<x-client.shared.journey-video :hide-title="true" />
<x-client.shared.custom-design-process />
<hr class="md:mb-16 mb-8" />
<x-client.shared.works-simple :show-nav="true" />
<x-client.shared.recommendations
    :related-products="$relatedProducts"
    route-name="{{ $detailRouteName }}"
    pk-field="{{ $productPkField }}"
    product-type="{{ $productType }}"
    :compare-table="true"
/>
<x-client.shared.faq-cta-banner />

</x-client.layouts.main>
