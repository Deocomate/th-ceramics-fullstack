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
    $bannerImage = \App\Support\AssetPath::url(data_get($parentConfig ?? null, 'thumbnail_main'), 'assets/images/detail-banner.png');
    $sizeImage = \App\Support\AssetPath::url(data_get($product, 'size_image'), 'assets/images/gach-bat-size-1.png');
@endphp

<x-layouts.client title="{{ $pageLabel }}" data-page="products" main-class="bg-background-secondary pb-14 md:pb-20" :hide-newsletter="true">

@push('styles')
<style>
    @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Italianno&display=swap");
</style>
@endpush

<!-- Top Banner for Detail -->
<section class="hidden md:flex relative w-full h-[180px] md:h-[210px] items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ $bannerImage }}" alt="{{ $product->name ?? $pageLabel }} Banner" class="w-full h-full object-cover" />
    </div>
    <div class="relative z-10 text-center text-white px-4 pt-4">
        <h1 class="text-2xl md:text-3xl font-bold mb-2.5 uppercase">{{ $pageLabel }}</h1>
        <p class="text-xs md:text-sm text-white/80">
            <a href="{{ route('client.home') }}" class="hover:text-white transition-colors">Trang chủ</a>
            <svg class="w-2.5 h-2.5 inline-block mx-2 fill-current opacity-80" viewBox="0 0 35 35" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.5797 31.4214C11.1695 31.0111 10.9391 30.4548 10.9391 29.8747C10.9391 29.2946 11.1695 28.7383 11.5797 28.3281L22.4078 17.5L11.5797 6.67184C11.1937 6.25726 10.9836 5.70915 10.9936 5.14283C11.0036 4.5765 11.2328 4.03612 11.6331 3.63539C12.0334 3.23465 12.5735 3.0048 13.1399 2.99421C13.7062 2.98361 14.2545 3.1931 14.6695 3.57858L27.046 15.9516C27.4561 16.3618 27.6865 16.9182 27.6865 17.4983C27.6865 18.0783 27.4561 18.6347 27.046 19.0449L14.6729 31.4214C14.2627 31.8315 13.7064 32.0619 13.1263 32.0619C12.5462 32.0619 11.9899 31.8315 11.5797 31.4214Z"
                    fill="currentColor" />
            </svg>
            <a href="{{ route($indexRouteName) }}" class="hover:text-white transition-colors">{{ $pageLabel }}</a>
        </p>
    </div>
</section>

<!-- Sub Breadcrumb -->
<div class="hidden md:block w-[85%] max-w-[1320px] mx-auto py-8">
    <x-products.breadcrumb current-label="{{ $pageLabel }}" parent-label="Sản phẩm" parent-href="{{ route($indexRouteName) }}" />
    <hr class="border-t border-black/10 mt-4 w-full" />
</div>

<!-- Product Detail Container -->
<x-products.product-detail-container
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

<x-products.hai-vm-calculator
    image="{{ $sizeImage }}"
    label1="Ngói trên mái gỗ"
    rate1="{{ $dinhMuc->first() && $dinhMuc->first()->ngoi_tren_mai_go ? $dinhMuc->first()->ngoi_tren_mai_go . ' viên/m²' : '125 viên/m²' }}"
    label2="Ngói trên mái bê tông"
    rate2="{{ $dinhMuc->first() && $dinhMuc->first()->ngoi_tren_mai_be_tong ? $dinhMuc->first()->ngoi_tren_mai_be_tong . ' viên/m²' : '75 viên/m²' }}"
/>

<x-products.fabrication-process :images="$parentConfig?->images ?? []" />
<x-products.journey-video :hide-title="true" />
<x-products.trang-tri-process />
<hr class="md:mb-16 mb-8" />
<x-products.works-simple :show-nav="true" />
<x-products.recommendations
    :related-products="$relatedProducts"
    route-name="{{ $detailRouteName }}"
    pk-field="{{ $productPkField }}"
    product-type="{{ $productType }}"
    :compare-table="true"
/>
<x-products.faq2 />

</x-layouts.client>
