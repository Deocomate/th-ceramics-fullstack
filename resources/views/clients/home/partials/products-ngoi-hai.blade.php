@component('clients.home.partials.product-section', [
    'sectionClass' => 'py-[25px] md:py-16 lg:pt-20',
    'sectionTitle' => 'Ngói hài văn miếu',
    'desktopLinkHref' => '/products/ngoi-hai-van-mieu/',
    'image1' => asset('assets/images/ngoi-01.jpg'),
    'image2' => asset('assets/images/ngoi-08.jpg'),
    'image3' => asset('assets/images/ngoi-05.jpg'),
    'image4' => asset('assets/images/ngoi-06.jpg'),
    'cardTitle' => 'Rimmers Âm Dương Sen Bầu',
    'cardCode' => 'MSP: 1234RDS',
    'cardPrice' => '675.000 đ/m2',
])
  <article class="flex-shrink-0 w-full snap-start pb-1" data-product-slide>
    <div class="grid grid-cols-2 gap-4">
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/ngoi-hai-van-mieu/ngoi-hai-co-nau-den.html', 'image' => asset('assets/images/ngoi-hai-detail.png'), 'title' => 'Ngói hài cổ nâu đen', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/ngoi-hai-van-mieu/ngoi-van-mieu-ghi-anh-kim.html', 'image' => asset('assets/images/ngoi-van-mieu-detail.png'), 'title' => 'Ngói văn miếu ghi ánh kim', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/ngoi-hai-van-mieu/ngoi-hai-co-nau-den.html', 'image' => asset('assets/images/ngoi-hai-01.png'), 'title' => 'Ngói hài cổ', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/ngoi-hai-van-mieu/ngoi-van-mieu-ghi-anh-kim.html', 'image' => asset('assets/images/ngoi-hai-02.png'), 'title' => 'Ngói hài văn miếu', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
    </div>
  </article>

  <article class="flex-shrink-0 w-full snap-start pb-1" data-product-slide>
    <div class="grid grid-cols-2 gap-4">
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/ngoi-hai-van-mieu/ngoi-hai-co-nau-den.html', 'image' => asset('assets/images/ngoi-hai-03.png'), 'title' => 'Ngói hài cổ nâu đen', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/ngoi-hai-van-mieu/ngoi-van-mieu-ghi-anh-kim.html', 'image' => asset('assets/images/ngoi-hai-detail.png'), 'title' => 'Ngói văn miếu ghi ánh kim', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/ngoi-hai-van-mieu/ngoi-hai-co-nau-den.html', 'image' => asset('assets/images/ngoi-van-mieu-detail.png'), 'title' => 'Ngói hài cổ', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/ngoi-hai-van-mieu/ngoi-van-mieu-ghi-anh-kim.html', 'image' => asset('assets/images/ngoi-hai-03.png'), 'title' => 'Ngói hài văn miếu', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
    </div>
  </article>
@endcomponent