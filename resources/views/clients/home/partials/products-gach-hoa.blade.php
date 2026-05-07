@component('clients.home.partials.product-section', [
    'sectionClass' => 'py-[25px] md:py-16 lg:py-20',
    'sectionTitle' => 'Gạch hoa thông gió',
    'desktopLinkHref' => '/products/gach-hoa-thong-gio/',
    'image1' => asset('assets/images/ngoi-01.jpg'),
    'image2' => asset('assets/images/ngoi-07.jpg'),
    'image3' => asset('assets/images/ngoi-05.jpg'),
    'image4' => asset('assets/images/ngoi-06.jpg'),
    'cardTitle' => 'Rimmers Âm Dương Sen Bầu',
    'cardCode' => 'MSP: 1234RDS',
    'cardPrice' => '675.000 đ/m2',
])
  <article class="flex-shrink-0 w-full snap-start pb-1" data-product-slide>
    <div class="grid grid-cols-2 gap-4">
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/gach-hoa-thong-gio/detail.html', 'image' => asset('assets/images/gach-hoa-01.jpg'), 'title' => 'Gạch hoa thông gió mẫu 01', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/gach-hoa-thong-gio/detail.html', 'image' => asset('assets/images/gach-hoa-02.jpg'), 'title' => 'Gạch hoa thông gió mẫu 02', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/gach-hoa-thong-gio/detail.html', 'image' => asset('assets/images/gach-hoa-03.jpg'), 'title' => 'Gạch hoa thông gió mẫu 03', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/gach-hoa-thong-gio/detail.html', 'image' => asset('assets/images/gach-hoa-04.jpg'), 'title' => 'Gạch hoa thông gió mẫu 04', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
    </div>
  </article>

  <article class="flex-shrink-0 w-full snap-start pb-1" data-product-slide>
    <div class="grid grid-cols-2 gap-4">
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/gach-hoa-thong-gio/detail.html', 'image' => asset('assets/images/gach-hoa-05.jpg'), 'title' => 'Gạch hoa thông gió mẫu 05', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/gach-hoa-thong-gio/detail.html', 'image' => asset('assets/images/gach-hoa-06.jpg'), 'title' => 'Gạch hoa thông gió mẫu 06', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/gach-hoa-thong-gio/detail.html', 'image' => asset('assets/images/gach-hoa-07.jpg'), 'title' => 'Gạch hoa thông gió mẫu 07', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
      @include('clients.home.partials.mobile-product-card', ['href' => '/products/gach-hoa-thong-gio/detail.html', 'image' => asset('assets/images/gach-hoa-08.jpg'), 'title' => 'Gạch hoa thông gió mẫu 08', 'code' => 'MSP: 1234RDS', 'price' => '675.000 đ/m2'])
    </div>
  </article>
@endcomponent