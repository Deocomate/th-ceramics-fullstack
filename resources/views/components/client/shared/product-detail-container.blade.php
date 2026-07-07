<section
  class="w-full md:w-[85%] max-w-[1320px] mx-auto grid grid-cols-1 lg:grid-cols-5 md:gap-4 lg:gap-6 xl:gap-8 pb-8 md:pb-10 lg:pb-24 pt-0 md:pt-4"
  data-product-detail-container
  data-add-to-cart-url="{{ route('client.cart.add') }}"
  data-product-type="{{ $productType ?? '' }}"
  data-product-id="{{ $productId ?? '' }}"
>
  <x-client.shared.product-image-swiper :images="$images" />

  <div class="flex flex-col lg:col-span-2 w-[85%] md:w-full mx-auto md:mt-0 pt-[18px] md:pt-0">
    <div class="flex items-center gap-2 md:gap-4 mb-3 md:mb-8 text-[12px] md:text-[16px] order-2 md:order-1 mt-1 md:mt-0">
      <span class="text-[#656663] md:text-[#101010] font-light md:font-normal font-archivo md:leading-[23px]">Mã SP:</span>
      <span data-detail-sku class="text-[#656663] md:text-[#101010] font-semibold font-archivo md:leading-[23px]">{{ $sku ?? 'THC 100-GDS' }}</span>
    </div>

    <h1 class="text-[20px] text-[#C76E00] md:text-2xl lg:text-[32px] font-semibold md:text-secondary uppercase leading-[30px] lg:leading-[40px] font-archivo mb-0 md:mb-14 pb-0 md:pb-1 tracking-tight order-1 md:order-2">
      {!! nl2br(e($titleText)) !!}
    </h1>

    <p data-detail-price class="text-[16px] text-black md:text-2xl md:text-[32px] font-semibold md:text-[#2E2F2A] font-archivo mb-4 md:mb-16 leading-[20px] md:leading-[32px] order-3 mt-0.5 md:mt-0">
      {{ $price ?? '675.000 đ/m²' }}
    </p>

    <hr class="border-t border-black/10 md:border-black/10 mb-4 md:mb-8 w-full order-4 hidden md:block" />
    <hr class="border-t border-black/10 w-full order-4 md:hidden mb-4" />

    <ul class="list-disc pl-5 space-y-0 md:space-y-4 mb-[15px] md:mb-16 text-[#2E2F2A] md:text-[#101010] font-medium md:font-normal text-[14px] md:text-lg lg:text-[20px] font-archivo leading-[24px] md:leading-[40px] order-5">
      @if ($featureItems->isNotEmpty())
        @foreach ($featureItems as $feature)
          <li>{{ $feature }}</li>
        @endforeach
      @else
        <li>Timeless beauty to be treasured</li>
        <li>High-quality and classic design, suitable for decoration</li>
        <li>Beginner friendly and improves intelligence</li>
      @endif
    </ul>

    @isset($colors)
    <div class="grid grid-cols-4 md:flex md:flex-wrap items-start md:items-center gap-6 md:gap-8 mb-[15px] md:mb-8 order-[6] md:order-[none] w-full">
      @foreach ($colors as $color)
      <div class="flex flex-col items-center gap-[11px] md:gap-3 cursor-pointer group variant-item {{ $loop->first ? 'selected' : '' }} [&.selected>div:first-child]:ring-2 [&.selected>div:first-child]:ring-secondary [&.selected>div:first-child]:ring-offset-2 [&.selected>span]:font-semibold [&.selected>span]:text-secondary md:[&.selected>span]:text-secondary"
           role="button"
           tabindex="0"
           aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
           data-product-variant
           data-name="{{ $color['name'] }}"
           @isset($color['variantId']) data-variant-id="{{ $color['variantId'] }}" @endisset
           @isset($color['sku']) data-sku="{{ $color['sku'] }}" @endisset
           @isset($color['price']) data-price="{{ $color['price'] }}" @endisset
           @isset($color['priceFormatted']) data-price-formatted="{{ $color['priceFormatted'] }}" @endisset
           @isset($color['image']) data-image="{{ $color['image'] }}" @endisset>
        <div class="w-[70px] h-[35px] md:w-[90px] md:h-[45px] bg-[#D9D9D9] shadow-[0px_1px_2px_rgba(0,0,0,0.05)] md:shadow-sm group-hover:opacity-80 transition-opacity" style="background-color: {{ $color['colorCode'] ?? '#D9D9D9' }}">
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
      <button class="variant-item {{ isset($variant['class']) ? $variant['class'] : 'flex-1 md:w-auto md:min-w-[200px]' }} {{ $loop->first ? 'selected' : '' }} border border-black/20 text-primary uppercase text-[10px] md:text-[13px] font-medium md:py-3 py-2 px-1 text-center hover:border-black/50 hover:bg-black/5 transition-all outline-none flex items-center justify-center leading-tight [&.selected]:border-black/50 [&.selected]:bg-black/5 [&.selected]:font-semibold"
              type="button"
              aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
              data-product-variant
              data-name="{{ $variant['name'] }}"
              @isset($variant['variantId']) data-variant-id="{{ $variant['variantId'] }}" @endisset
              @isset($variant['sku']) data-sku="{{ $variant['sku'] }}" @endisset
              @isset($variant['price']) data-price="{{ $variant['price'] }}" @endisset
              @isset($variant['priceFormatted']) data-price-formatted="{{ $variant['priceFormatted'] }}" @endisset
              @isset($variant['image']) data-image="{{ $variant['image'] }}" @endisset>
        {{ $variant['name'] }}
      </button>
      @endforeach
    </div>
    @endisset

    <div class="flex flex-col md:flex-row items-start md:items-center gap-6 mb-[15px] md:mb-16 order-[7] md:order-[none] w-full">
      <div class="flex items-center gap-[16px] md:gap-4 text-[#2E2F2A] md:text-primary pl-0.5 md:pl-0">
        <button type="button" class="w-6 h-6 flex items-center justify-center text-[20px] md:text-xl focus:outline-none md:hover:text-secondary transition-colors" data-detail-quantity-decrease>
          -
        </button>
        <div class="w-12 h-12 flex items-center justify-center rounded-full text-[16px] md:text-[#101010] font-normal shadow-[0px_1px_2px_rgba(0,0,0,0.05)] md:shadow-sm outline outline-1 outline-black/40 outline-offset-[-1px] md:outline-none md:border md:border-black/40 bg-transparent font-archivo md:leading-[23px]" data-detail-quantity-display>
          1
        </div>
        <button type="button" class="w-6 h-6 flex items-center justify-center text-[20px] md:text-xl focus:outline-none md:hover:text-secondary transition-colors" data-detail-quantity-increase>
          +
        </button>
      </div>

      @if (!$isEcommerceEnabled)
      <button type="button"
          data-open-consultation
          data-product-type="{{ $productType ?? '' }}"
          data-product-id="{{ $productId ?? '' }}"
          data-product-name="{{ strip_tags(str_replace(["\r", "\n"], ' ', $titleText)) }}"
          class="w-full md:w-auto bg-[#C16A00] hover:bg-secondary cursor-pointer text-[#EFE4DE] px-8 py-4 font-semibold md:transition-colors md:shadow-md rounded-[2px] flex items-center justify-center text-[14px] md:text-sm tracking-[0.28px] md:tracking-normal font-archivo md:leading-[18px] md:ml-4">
          LIÊN HỆ ĐẶT HÀNG
      </button>
      @else
      <button type="button"
          data-detail-add-to-cart
          @disabled(! $showButton)
          class="w-full md:w-auto {{ $showButton ? 'bg-[#C16A00] hover:bg-secondary cursor-pointer' : 'bg-gray-400 cursor-not-allowed' }} text-[#EFE4DE] px-8 py-4 font-semibold md:transition-colors md:shadow-md rounded-[2px] flex items-center justify-center text-[14px] md:text-sm tracking-[0.28px] md:tracking-normal font-archivo md:leading-[18px] md:ml-4">
          @if($showButton)
              THÊM VÀO GIỎ HÀNG
          @else
              LIÊN HỆ ĐẶT HÀNG
          @endif
      </button>
      @endif
    </div>

    <input type="hidden" data-detail-quantity-input name="qty" value="1">

    <div class="hidden md:flex flex-col gap-5 mt-2 order-[8] md:order-[none]">
      <div class="flex items-center gap-5">
        <div class="w-[66px] h-[66px] rounded-full bg-[#EBDDD0] flex items-center justify-center text-secondary flex-shrink-0 shadow-sm border border-secondary/10">
          <img src="{{ asset('assets/images/phone-call.svg') }}" alt="Phone Call" class="w-6 h-6" />
        </div>
        <div>
          <p class="text-base text-secondary font-normal font-archivo leading-[23px]">Đặt hàng ngay</p>
          <p class="text-secondary font-semibold text-lg md:text-[20px] font-archivo leading-[32px]">Hotline: {{ $contactHotline }}</p>
        </div>
      </div>
      <a href="{{ $zaloLink }}" target="_blank" rel="noopener" class="flex items-center gap-5">
        <div class="w-[66px] h-[66px] rounded-full bg-[#EBDDD0] flex items-center justify-center text-secondary flex-shrink-0 shadow-sm border border-secondary/10">
          <img src="{{ asset('assets/images/zalo.png') }}" alt="Zalo" class="w-[80%] h-[80%] object-cover" />
        </div>
        <div>
          <p class="text-secondary font-semibold text-lg md:text-[20px] font-archivo leading-[32px]">Chat với chúng tôi</p>
        </div>
      </a>
    </div>
  </div>

  <input type="hidden" id="product_type" value="{{ $productType ?? '' }}">
  <input type="hidden" id="product_id" value="{{ $productId ?? '' }}">
</section>

@push('styles')
    <style>
        @media (min-width: 768px) {
            .product-thumb-swiper .swiper-wrapper {
                display: flex !important;
                transform: none !important;
                justify-content: flex-start !important;
                gap: 20px !important;
            }
            .product-thumb-swiper .swiper-slide {
                width: calc((100% - 6 * 20px) / 7) !important;
                margin-right: 0 !important;
                flex-shrink: 0 !important;
            }
        }
    </style>
@endpush
