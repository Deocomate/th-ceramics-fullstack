<x-client.layouts.main title="Gạch Cổ Bát Tràng" data-page="products" main-class="bg-background-secondary pb-14 md:pb-20" :hide-newsletter="true">

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
    parent-label="Sản phẩm" current-class="text-primary font-semibold pb-1" current-label="Gạch Cổ Bát Tràng" />
    <hr class="border-t border-black/10 mt-4 w-full" />
</div>

<!-- Product Detail Container -->
<x-client.shared.product-detail-container
title="{!! $product->name !!}"
price="{{ $product->price > 0 ? number_format($product->price) . 'đ' : 'Liên hệ' }}"
rawPrice="{{ $product->price }}"
sku="{{ $product->code }}"
:features="$product->des"
productType="gach_co_bat_trang_ct"
productId="{{ $product->gach_co_bat_trang_ct_id }}"
/>

<x-client.shared.journey-video :video="$journeyVideo ?? null" :hide-title="true" />
<x-client.shared.works-simple :show-nav="true" />
<x-client.shared.quantity-calculator
    image="{{ !empty($product->size_image) ? asset('storage/' . $product->size_image) : asset('assets/images/gtt-size.png') }}"
    :dinhMuc="$dinhMuc"
    :rate="$dinhMuc->first()?->value" />
<x-client.shared.fabrication-process />
<x-client.shared.outstanding-value />
<x-client.shared.custom-design-process />
<hr class="md:mb-16 mb-8" />
<x-client.shared.recommendations
    :related-products="$relatedProducts"
    :show-decor="true"
    route-name="client.products.gach-co-bat-trang.detail"
    pk-field="gach_co_bat_trang_ct_id"
    product-type="gach_co_bat_trang_ct"
/>
<x-client.shared.faq-cta-banner />
<x-client.shared.weight-calculator-sticky-bar />

</x-client.layouts.main>
