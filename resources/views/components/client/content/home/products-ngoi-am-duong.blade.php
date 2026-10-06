@props(['ngoiAmDuongs' => collect()])

<x-client.content.home.product-section
    section-class="py-[25px] md:py-16 lg:pt-20"
    section-title="Ngói âm dương"
    :desktop-link-href="route('client.products.ngoi-am-duong.index')"
    detail-route-name="client.products.ngoi-am-duong.detail"
    :products="$ngoiAmDuongs"
/>
