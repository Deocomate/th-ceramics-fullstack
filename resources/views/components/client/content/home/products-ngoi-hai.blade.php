@props(['ngoiHais' => collect()])

<x-client.content.home.product-section
    section-class="py-[25px] md:py-16 lg:pt-20"
    section-title="Ngói hài văn miếu"
    :desktop-link-href="route('client.products.ngoi-hai-van-mieu.index')"
    detail-route-name="client.products.ngoi-hai-van-mieu.detail"
    :products="$ngoiHais"
/>
