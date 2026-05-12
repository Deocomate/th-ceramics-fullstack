@component('clients.home.partials.product-section', [
    'sectionClass' => 'py-[25px] md:py-16 lg:pt-20',
    'sectionTitle' => 'Ngói hài văn miếu',
    'desktopLinkHref' => route('client.products.ngoi-hai-van-mieu.index'),
    'detailRouteName' => 'client.products.ngoi-hai-van-mieu.detail',
    'products' => $ngoiHais,
])
@endcomponent
