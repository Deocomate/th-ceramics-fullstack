@component('clients.home.partials.product-section', [
    'sectionClass' => 'py-[25px] md:py-16 lg:py-20',
    'sectionTitle' => 'Gạch hoa thông gió',
    'desktopLinkHref' => route('client.products.gach-hoa-thong-gio.index'),
    'detailRouteName' => 'client.products.gach-hoa-thong-gio.detail',
    'products' => $gachHoas,
])
@endcomponent
