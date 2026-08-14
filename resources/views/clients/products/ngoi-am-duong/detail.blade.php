<x-client.layouts.main title="Ngói Âm Dương" data-page="products" main-class="bg-background-secondary pb-14 md:pb-20" :hide-newsletter="true">

@push('styles')
<style>
    @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Italianno&display=swap");

    .size-options-scroll {
        scroll-behavior: smooth;
    }
</style>
@endpush

@php
    $sizeImage = \App\Support\AssetPath::url($product->size_image, 'assets/images/ngoi-am-duong-size.png');
@endphp

<!-- Sub Breadcrumb -->
<div class="hidden md:block w-[85%] max-w-[1320px] mx-auto py-8">
    <x-client.shared.breadcrumb current-label="NGÓI ÂM DƯƠNG" parent-label="Sản phẩm" parent-href="{{ route('client.products.ngoi-am-duong.index') }}" />
    <hr class="border-t border-black/10 mt-4 w-full" />
</div>

<!-- Product Detail Container -->
<x-client.shared.product-detail-container
    title="{{ $product->name }}"
    price="{{ $product->price > 0 ? number_format($product->price, 0, ',', '.') . ' đ/m²' : 'Liên hệ' }}"
    rawPrice="{{ $product->price }}"
    sku="{{ $product->code ?? '' }}"
    :features="$product->des ?? null"
    :images="$product->images ?? []"
    productType="ngoi_am_duong_ct"
    productId="{{ $product->ngoi_am_duong_ct_id }}"
/>

<x-client.shared.journey-video :video="$journeyVideo ?? null" :hide-title="true" />

<x-client.shared.works />

<section id="bang-kich-thuoc" class="w-[85%] max-w-[1320px] mx-auto pb-[40px] md:pb-16 pt-1" data-aos="fade-up">
    <h2
        class="text-[20px] leading-[32px] tracking-[0.6px] md:text-3xl md:leading-normal md:tracking-wide font-semibold text-center text-secondary mb-6 md:mb-12 uppercase break-words">
        Bảng kích thước
    </h2>
    <div class="size-options-scroll mobile-scroll-visible w-full pb-2 overflow-x-scroll md:overflow-x-hidden">
        <img src="{{ $sizeImage }}" alt="Bảng kích thước {{ $product->name }}"
            class="h-auto object-contain max-w-none w-[200%] md:w-full"
            onload="window.dispatchEvent(new Event('resize'))" />
    </div>
</section>

@if ($colors->isNotEmpty())
    <x-client.shared.color-palette :colors="$colors" />
@endif

<x-client.shared.product-guide-tabs>
    <x-slot:install>
        <x-client.products.ngoi-am-duong.installation-guide :hide-title="true" />
    </x-slot:install>
    <x-slot:applications>
        <x-client.products.ngoi-am-duong.applications :hide-title="true" />
    </x-slot:applications>
</x-client.shared.product-guide-tabs>

<x-client.products.ngoi-am-duong.weight-calculator :dinh-muc="$dinhMuc" />

<x-client.shared.outstanding-value />

<x-client.shared.recommendations
    :related-products="$relatedProducts"
    route-name="client.products.ngoi-am-duong.detail"
    pk-field="ngoi_am_duong_ct_id"
    product-type="ngoi_am_duong_ct"
/>
<x-client.shared.faq-cta-banner />

<x-client.shared.weight-calculator-sticky-bar />

</x-client.layouts.main>
