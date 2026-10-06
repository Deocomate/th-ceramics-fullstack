@props(['gachHoas' => collect()])

<x-client.content.home.product-section
    section-class="py-[25px] md:py-16 lg:py-20"
    section-title="Gạch hoa thông gió"
    :desktop-link-href="route('client.products.gach-hoa-thong-gio.index')"
    detail-route-name="client.products.gach-hoa-thong-gio.detail"
    :products="$gachHoas"
/>
