@component('clients.home.partials.product-section', [
    'sectionClass' => 'py-[25px] md:py-16 lg:pt-20',
    'sectionTitle' => 'Ngói âm dương',
    'desktopLinkHref' => route('client.products.ngoi-am-duong.index'),
    'detailRouteName' => 'client.products.ngoi-am-duong.detail',
    'products' => $ngoiAmDuongs,
])
@endcomponent
