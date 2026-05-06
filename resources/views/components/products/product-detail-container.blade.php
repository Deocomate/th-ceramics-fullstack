@props(['title' => 'Chi tiết sản phẩm', 'price' => null, 'sku' => null, 'features' => null, 'colors' => null, 'variants' => null, 'images' => []])

<section class="w-full md:w-[85%] max-w-[1320px] mx-auto grid grid-cols-1 lg:grid-cols-5 md:gap-4 lg:gap-6 xl:gap-8 pb-8 md:pb-10 lg:pb-24 pt-0 md:pt-4">
  <x-products.product-image-swiper :images="$images" />

  <div class="flex flex-col lg:col-span-2 w-[85%] md:w-full mx-auto md:mt-0 pt-[18px] md:pt-0">
    <div class="flex items-center gap-2 md:gap-4 mb-3 md:mb-8 text-[12px] md:text-[16px] order-2 md:order-1 mt-1 md:mt-0">
      <span class="text-[#656663] md:text-primary font-light md:font-normal">Mã SP:</span>
      <span class="text-[#656663] md:text-primary font-semibold">{{ $sku ?? 'THC 100-GDS' }}</span>
    </div>

    <h1 class="text-[20px] text-[#C76E00] md:text-2xl lg:text-[32px] font-semibold md:text-secondary uppercase leading-[30px] md:!leading-normal mb-0 md:mb-14 pb-0 md:pb-1 tracking-tight order-1 md:order-2">
      {!! $title !!}
    </h1>

    <p class="text-[16px] text-black md:text-2xl md:text-[32px] font-semibold md:text-primary mb-4 md:mb-16 leading-[20px] md:leading-normal order-3 mt-0.5 md:mt-0">
      {{ isset($price) ? $price : '675.000 đ/m²' }}
    </p>

    <hr class="border-t border-black/10 md:border-black/10 mb-4 md:mb-8 w-full order-4 hidden md:block" />
    <hr class="border-t border-black/10 w-full order-4 md:hidden mb-4" />

    <ul class="list-disc pl-5 space-y-0 md:space-y-4 mb-[15px] md:mb-16 text-[#2E2F2A] md:text-primary font-medium text-[14px] md:text-lg lg:text-xl leading-[24px] md:leading-relaxed order-5">
      @isset($features)
        @foreach ($features as $feature)
          <li>{{ $feature }}</li>
        @endforeach
      @else
        <li>Timeless beauty to be treasured</li>
        <li>High-quality and classic design, suitable for decoration</li>
        <li>Beginner friendly and improves intelligence</li>
      @endisset
    </ul>

    @isset($colors)
    <div class="grid grid-cols-4 md:flex md:flex-wrap items-start md:items-center gap-6 md:gap-8 mb-[15px] md:mb-8 order-[6] md:order-[none] w-full">
      @foreach ($colors as $color)
      <div class="flex flex-col items-center gap-[11px] md:gap-3 cursor-pointer group">
        <div class="w-[70px] h-[35px] md:w-[90px] md:h-[45px] {{ isset($color['colorCode']) ? 'bg-[' . $color['colorCode'] . ']' : 'bg-[#D9D9D9]' }} shadow-[0px_1px_2px_rgba(0,0,0,0.05)] md:shadow-sm group-hover:opacity-80 transition-opacity">
          @isset($color['image'])
          <img src="{{ $color['image'] }}" alt="{{ $color['name'] }}" class="w-full h-full object-cover" />
          @endisset
        </div>
        <span class="text-[13px] text-nowrap md:text-sm text-[#2E2F2A] md:text-primary font-medium leading-[19.5px] md:leading-normal text-center px-1">
          {{ $color['name'] }}
        </span>
      </div>
      @endforeach
    </div>
    @endisset

    @isset($variants)
    <div class="flex flex-wrap gap-3.5 md:gap-4 mb-6 md:mb-16 w-full xl:w-[95%] order-[6] md:order-[none]">
      @foreach ($variants as $variant)
      <button class="{{ isset($variant['class']) ? $variant['class'] : 'flex-1 md:w-auto md:min-w-[200px]' }} border border-black/20 text-primary uppercase text-[10px] md:text-[13px] font-medium md:py-3 py-2 px-1 text-center hover:border-black/50 hover:bg-black/5 transition-all outline-none flex items-center justify-center leading-tight">
        {{ $variant['name'] }}
      </button>
      @endforeach
    </div>
    @endisset

    <div class="flex flex-col md:flex-row items-start md:items-center gap-6 mb-[15px] md:mb-16 order-[7] md:order-[none] w-full">
      <div class="flex items-center gap-[16px] md:gap-4 text-[#2E2F2A] md:text-primary pl-0.5 md:pl-0">
        <button class="w-6 h-6 flex items-center justify-center text-[20px] md:text-xl focus:outline-none md:hover:text-secondary transition-colors">
          -
        </button>
        <div class="w-12 h-12 flex items-center justify-center rounded-full text-[16px] md:text-base font-normal shadow-[0px_1px_2px_rgba(0,0,0,0.05)] md:shadow-sm outline outline-1 outline-black/40 outline-offset-[-1px] md:outline-none md:border md:border-black/40 bg-transparent">
          1
        </div>
        <button class="w-6 h-6 flex items-center justify-center text-[20px] md:text-xl focus:outline-none md:hover:text-secondary transition-colors">
          +
        </button>
      </div>

      <button class="w-full md:w-auto bg-[#C16A00] hover:bg-secondary text-[#EFE4DE] px-8 py-4 font-semibold md:transition-colors md:shadow-md rounded-[2px] flex items-center justify-center text-[14px] md:text-sm tracking-[0.28px] md:tracking-normal md:ml-4">
        THÊM VÀO GIỎ HÀNG
      </button>
    </div>

    <div class="hidden md:flex flex-col gap-5 mt-2 order-[8] md:order-[none]">
      <div class="flex items-center gap-5">
        <div class="w-[66px] h-[66px] rounded-full bg-[#EBDDD0] flex items-center justify-center text-secondary flex-shrink-0 shadow-sm border border-secondary/10">
          <img src="{{ asset('assets/images/phone-call.svg') }}" alt="Phone Call" class="w-6 h-6" />
        </div>
        <div>
          <p class="text-base text-secondary">Đặt hàng ngay</p>
          <p class="text-secondary font-semibold text-lg md:text-xl">Hotline: 0966 55 8808</p>
        </div>
      </div>
      <div class="flex items-center gap-5">
        <div class="w-[66px] h-[66px] rounded-full bg-[#EBDDD0] flex items-center justify-center text-secondary flex-shrink-0 shadow-sm border border-secondary/10">
          <img src="{{ asset('assets/images/zalo.png') }}" alt="Zalo" class="w-[80%] h-[80%] object-cover" />
        </div>
        <div>
          <p class="text-secondary font-semibold text-lg md:text-xl">Chat với chúng tôi</p>
        </div>
      </div>
    </div>
  </div>
</section>