<a
  href="{{ isset($href) ? $href : (isset($category) ? '/products/' . $category . '/' . (isset($link) ? $link : 'detail.html') : '#') }}"
  class="{{ isset($wrapperClass) ? $wrapperClass : 'flex flex-col group cursor-pointer' }}"
>
  <div
    class="{{ isset($imageContainerClass) ? $imageContainerClass : 'product-card relative bg-white rounded-sm shadow-lg overflow-hidden mb-4 aspect-square transition-all duration-300 group-hover:-translate-y-1' }}"
  >
    <img
      src="{{ isset($image) ? $image : asset('assets/images/' . (isset($imageName) ? $imageName : (isset($fallbackImageName) ? $fallbackImageName : 'ngoi-01.jpg'))) }}"
      alt="{{ isset($alt) ? $alt : (isset($title) ? $title : 'Riềm âm dương sen bầu') }}"
      class="{{ isset($imageClass) ? $imageClass : 'w-full h-full object-cover mix-blend-multiply' }}"
    />
    @isset($showOverlay)
    <div class="product-overlay">
      <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
      <span>{{ isset($overlayLabel) ? $overlayLabel : 'Xem chi tiết' }}</span>
    </div>
    @endisset
  </div>
  <h3
    class="{{ isset($titleClass) ? $titleClass : 'text-primary font-semibold text-sm uppercase mb-2 transition-colors group-hover:text-secondary' }}"
  >
    <span class="block lowercase first-letter:uppercase md:uppercase">
      {{ isset($title) ? $title : 'Riềm âm dương sen bầu' }}
    </span>
  </h3>
  <p
    class="{{ isset($codeClass) ? $codeClass : 'text-gray-500 text-[13px] mb-2' }}"
  >
    {{ isset($code) ? $code : 'MSP: 1234RDS' }}
  </p>
  <p
    class="{{ isset($priceClass) ? $priceClass : 'text-secondary font-bold text-[14px]' }}"
  >
    @isset($pricePrefix){{ $pricePrefix }}: @endisset{{ isset($price) ? $price : '675.000 đ/m2' }}
  </p>
</a>