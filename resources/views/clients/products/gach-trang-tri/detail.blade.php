<x-client.layouts.main title="Gạch Trang Trí" data-page="products" main-class="bg-background-secondary pb-14 md:pb-20" :hide-newsletter="true">

@push('styles')
<style>
    @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Italianno&display=swap");
</style>
@endpush

<!-- Top Banner for Detail -->
<section class="hidden md:flex relative w-full h-[180px] md:h-[210px] items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ !empty($product->images) && is_array($product->images) && isset($product->images[0]) ? asset('storage/' . $product->images[0]) : asset('assets/images/detail-banner.png') }}" alt="{{ $product->name ?? 'Gạch Trang Trí' }} Banner" class="w-full h-full object-cover" />
    </div>
    <div class="relative z-10 text-center text-white px-4 pt-4">
        <h1 class="text-2xl md:text-3xl font-bold mb-2.5 uppercase">
            Gạch Trang Trí
        </h1>
        <p class="text-xs md:text-sm text-white/80">
            <a href="{{ route('client.home') }}" class="hover:text-white transition-colors">Trang chủ</a>
            <svg class="w-2.5 h-2.5 inline-block mx-2 fill-current opacity-80" viewBox="0 0 35 35" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.5797 31.4214C11.1695 31.0111 10.9391 30.4548 10.9391 29.8747C10.9391 29.2946 11.1695 28.7383 11.5797 28.3281L22.4078 17.5L11.5797 6.67184C11.1937 6.25726 10.9836 5.70915 10.9936 5.14283C11.0036 4.5765 11.2328 4.03612 11.6331 3.63539C12.0334 3.23465 12.5735 3.0048 13.1399 2.99421C13.7062 2.98361 14.2545 3.1931 14.6695 3.57858L27.046 15.9516C27.4561 16.3618 27.6865 16.9182 27.6865 17.4983C27.6865 18.0783 27.4561 18.6347 27.046 19.0449L14.6729 31.4214C14.2627 31.8315 13.7064 32.0619 13.1263 32.0619C12.5462 32.0619 11.9899 31.8315 11.5797 31.4214Z"
                    fill="currentColor" />
            </svg>
            <a href="{{ route('client.products.gach-trang-tri.index') }}" class="hover:text-white transition-colors">Gạch trang trí</a>
        </p>
    </div>
</section>

<!-- Sub Breadcrumb -->
<div class="hidden md:block w-[85%] max-w-[1320px] mx-auto py-8">
    <x-client.shared.breadcrumb text-class="font-semibold text-primary/60 uppercase text-[14px] md:text-base"
    link-class="hover:text-primary transition-colors" separator-class="mx-1" parent-href="{{ route('client.products.gach-trang-tri.index') }}"
    parent-label="Sản phẩm" current-class="text-primary font-semibold pb-1" current-label="Gạch Trang Trí" />
    <hr class="border-t border-black/10 mt-4 w-full" />
</div>

<!-- Product Detail Container -->
<x-client.shared.product-detail-container
title="{{ $product->name ?? 'GẠCH TRANG TRÍ' }}"
price="{{ $product->price > 0 ? number_format($product->price) . 'đ' : 'Liên hệ' }}"
rawPrice="{{ $product->price }}"
sku="{{ $product->code ?? '' }}"
productType="gach_trang_tri_ct"
productId="{{ $product->gach_trang_tri_ct_id }}"
/>

<div class="flex flex-col md:block">
    <div class="order-2 md:order-none">
        <x-client.shared.quantity-calculator
            image="{{ $product->size_image ? asset('storage/' . $product->size_image) : asset('assets/images/gtt-size.png') }}"
            :dinhMuc="$dinhMuc"
            :rate="$dinhMuc->first()?->value" />
    </div>
    <div class="order-3 md:order-none">
        <x-client.shared.fabrication-process />
    </div>
    <div class="order-1 md:order-none">
        <x-client.shared.journey-video :hide-title="true" />
    </div>
</div>

<x-client.shared.custom-design-process />

<hr class="md:mb-16 mb-8" />

<x-client.shared.works-simple :show-nav="true" />
<x-client.shared.recommendations
    :related-products="$relatedProducts"
    :show-decor="true"
    route-name="client.products.gach-trang-tri.detail"
    pk-field="gach_trang_tri_ct_id"
    product-type="gach_trang_tri_ct"
/>
<x-client.shared.faq-cta-banner />

</x-client.layouts.main>
