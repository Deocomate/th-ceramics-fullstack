@props(['title' => 'Chi tiết sản phẩm', 'price' => null, 'rawPrice' => null, 'sku' => null, 'features' => null, 'colors' => null, 'variants' => null, 'images' => [], 'productType' => null, 'productId' => null])

@php
  $featureItems = collect($features)->filter(fn ($feature) => filled($feature))->values();
  $titleText = html_entity_decode(preg_replace('/<br\s*\/?>/i', "\n", (string) $title), ENT_QUOTES, 'UTF-8');
  $contactHotline = data_get($globalContact ?? null, 'hotline', '0966 55 8808');
  $zaloLink = data_get($globalContact ?? null, 'zalo_link', 'https://zalo.me/0966558808');
@endphp

<section class="w-full md:w-[85%] max-w-[1320px] mx-auto grid grid-cols-1 lg:grid-cols-5 md:gap-4 lg:gap-6 xl:gap-8 pb-8 md:pb-10 lg:pb-24 pt-0 md:pt-4">
  <x-products.product-image-swiper :images="$images" />

  <div class="flex flex-col lg:col-span-2 w-[85%] md:w-full mx-auto md:mt-0 pt-[18px] md:pt-0">
    <div class="flex items-center gap-2 md:gap-4 mb-3 md:mb-8 text-[12px] md:text-[16px] order-2 md:order-1 mt-1 md:mt-0">
      <span class="text-[#656663] md:text-primary font-light md:font-normal">Mã SP:</span>
      <span id="dynamic-sku" class="text-[#656663] md:text-primary font-semibold">{{ $sku ?? 'THC 100-GDS' }}</span>
    </div>

    <h1 class="text-[20px] text-[#C76E00] md:text-2xl lg:text-[32px] font-semibold md:text-secondary uppercase leading-[30px] md:!leading-normal mb-0 md:mb-14 pb-0 md:pb-1 tracking-tight order-1 md:order-2">
      {!! nl2br(e(strip_tags($titleText))) !!}
    </h1>

    <p id="dynamic-price" class="text-[16px] text-black md:text-2xl md:text-[32px] font-semibold md:text-primary mb-4 md:mb-16 leading-[20px] md:leading-normal order-3 mt-0.5 md:mt-0">
      {{ isset($price) ? $price : '675.000 đ/m²' }}
    </p>

    <hr class="border-t border-black/10 md:border-black/10 mb-4 md:mb-8 w-full order-4 hidden md:block" />
    <hr class="border-t border-black/10 w-full order-4 md:hidden mb-4" />

    <ul class="list-disc pl-5 space-y-0 md:space-y-4 mb-[15px] md:mb-16 text-[#2E2F2A] md:text-primary font-medium text-[14px] md:text-lg lg:text-xl leading-[24px] md:leading-relaxed order-5">
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
      <div class="flex flex-col items-center gap-[11px] md:gap-3 cursor-pointer group variant-item {{ $loop->first ? 'selected' : '' }}"
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
      <button class="variant-item {{ isset($variant['class']) ? $variant['class'] : 'flex-1 md:w-auto md:min-w-[200px]' }} border border-black/20 text-primary uppercase text-[10px] md:text-[13px] font-medium md:py-3 py-2 px-1 text-center hover:border-black/50 hover:bg-black/5 transition-all outline-none flex items-center justify-center leading-tight"
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
        <button type="button" class="w-6 h-6 flex items-center justify-center text-[20px] md:text-xl focus:outline-none md:hover:text-secondary transition-colors qty-decrease-detail">
          -
        </button>
        <div class="w-12 h-12 flex items-center justify-center rounded-full text-[16px] md:text-base font-normal shadow-[0px_1px_2px_rgba(0,0,0,0.05)] md:shadow-sm outline outline-1 outline-black/40 outline-offset-[-1px] md:outline-none md:border md:border-black/40 bg-transparent" id="detail-quantity-display">
          1
        </div>
        <button type="button" class="w-6 h-6 flex items-center justify-center text-[20px] md:text-xl focus:outline-none md:hover:text-secondary transition-colors qty-increase-detail">
          +
        </button>
      </div>

      @php
        $canAdd = isset($rawPrice) && $rawPrice > 0;
        // For products with variants, we check if there are colors with price data
        $hasVariantPricing = false;
        if (isset($colors)) {
            foreach ($colors as $c) {
                if (isset($c['variantId']) || isset($c['price'])) {
                    $hasVariantPricing = true;
                    break;
                }
            }
        }
        if (isset($variants)) {
            foreach ($variants as $v) {
                if (isset($v['variantId']) || isset($v['price'])) {
                    $hasVariantPricing = true;
                    break;
                }
            }
        }
        $showBtn = $canAdd || $hasVariantPricing;
      @endphp

      <button id="btn-add-to-cart" type="button"
          class="w-full md:w-auto {{ $showBtn ? 'bg-[#C16A00] hover:bg-secondary cursor-pointer' : 'bg-gray-400 cursor-not-allowed' }} text-[#EFE4DE] px-8 py-4 font-semibold md:transition-colors md:shadow-md rounded-[2px] flex items-center justify-center text-[14px] md:text-sm tracking-[0.28px] md:tracking-normal md:ml-4">
          @if($showBtn)
              THÊM VÀO GIỎ HÀNG
          @else
              LIÊN HỆ ĐẶT HÀNG
          @endif
      </button>
    </div>

    <div class="hidden md:flex flex-col gap-5 mt-2 order-[8] md:order-[none]">
      <div class="flex items-center gap-5">
        <div class="w-[66px] h-[66px] rounded-full bg-[#EBDDD0] flex items-center justify-center text-secondary flex-shrink-0 shadow-sm border border-secondary/10">
          <img src="{{ asset('assets/images/phone-call.svg') }}" alt="Phone Call" class="w-6 h-6" />
        </div>
        <div>
          <p class="text-base text-secondary">Đặt hàng ngay</p>
          <p class="text-secondary font-semibold text-lg md:text-xl">Hotline: {{ $contactHotline }}</p>
        </div>
      </div>
      <a href="{{ $zaloLink }}" target="_blank" rel="noopener" class="flex items-center gap-5">
        <div class="w-[66px] h-[66px] rounded-full bg-[#EBDDD0] flex items-center justify-center text-secondary flex-shrink-0 shadow-sm border border-secondary/10">
          <img src="{{ asset('assets/images/zalo.png') }}" alt="Zalo" class="w-[80%] h-[80%] object-cover" />
        </div>
        <div>
          <p class="text-secondary font-semibold text-lg md:text-xl">Chat với chúng tôi</p>
        </div>
      </a>
    </div>
  </div>
</section>

<input type="hidden" id="product_type" value="{{ $productType ?? '' }}">
<input type="hidden" id="product_id" value="{{ $productId ?? '' }}">
<input type="hidden" id="detail-quantity-input" name="qty" value="1">

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const formatPrice = (raw) => {
        const value = Number.parseFloat(raw || '0');
        if (!value) return 'Liên hệ';
        return new Intl.NumberFormat('vi-VN').format(value) + ' đ/m²';
    };

    const sameUrl = (left, right) => {
        if (!left || !right) return false;
        try {
            return new URL(left, window.location.href).href === new URL(right, window.location.href).href;
        } catch (error) {
            return left === right;
        }
    };

    const switchGalleryToImage = (imageUrl) => {
        if (!imageUrl) return;
        const mainSwiperEl = document.querySelector('.product-main-swiper');
        const swiper = mainSwiperEl?.swiper;
        if (!swiper) return;

        const slides = Array.from(mainSwiperEl.querySelectorAll('.swiper-slide'));
        const targetIndex = slides.findIndex((slide) => {
            const image = slide.querySelector('img');
            return sameUrl(image?.currentSrc || image?.src, imageUrl);
        });

        if (targetIndex >= 0) {
            swiper.slideTo(targetIndex);
        }
    };

    // Variant/Color selection
    document.querySelectorAll('.variant-item').forEach(el => {
        el.addEventListener('click', function() {
            const parent = this.parentElement;
            parent.querySelectorAll('.variant-item').forEach(e => e.classList.remove('selected'));
            this.classList.add('selected');

            const skuOutput = document.getElementById('dynamic-sku');
            const priceOutput = document.getElementById('dynamic-price');

            if (skuOutput && this.dataset.sku) {
                skuOutput.textContent = this.dataset.sku;
            }

            if (priceOutput) {
                priceOutput.textContent = this.dataset.priceFormatted || formatPrice(this.dataset.price);
            }

            switchGalleryToImage(this.dataset.image);
        });
    });

    const btnAdd = document.getElementById('btn-add-to-cart');
    if (!btnAdd || btnAdd.classList.contains('cursor-not-allowed')) return;

    // Quantity controls
    const qtyDisplay = document.getElementById('detail-quantity-display');
    const qtyInput = document.getElementById('detail-quantity-input');
    let currentQty = 1;

    const setQuantity = (value) => {
        currentQty = Math.max(1, parseInt(value || 1, 10) || 1);
        if (qtyDisplay) qtyDisplay.textContent = currentQty;
        if (qtyInput) qtyInput.value = currentQty;
    };

    document.querySelector('.qty-decrease-detail')?.addEventListener('click', function() {
        setQuantity(currentQty - 1);
    });

    document.querySelector('.qty-increase-detail')?.addEventListener('click', function() {
        setQuantity(currentQty + 1);
    });

    qtyInput?.addEventListener('input', function() {
        setQuantity(this.value);
    });

    qtyInput?.addEventListener('change', function() {
        setQuantity(this.value);
    });

    btnAdd.addEventListener('click', function() {
        const type = document.getElementById('product_type').value;
        const id = document.getElementById('product_id').value;
        const qty = parseInt(qtyInput?.value || currentQty, 10) || 1;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        if (!type || !id) {
            alert('Thông tin sản phẩm không đầy đủ.');
            return;
        }

        // Check if variants exist on page but none selected
        const variantElements = document.querySelectorAll('.variant-item');
        const selectedVariant = document.querySelector('.variant-item.selected');
        let variantId = null;

        if (variantElements.length > 0) {
            if (!selectedVariant) {
                alert('Vui lòng chọn màu sắc/phân loại trước khi thêm vào giỏ hàng!');
                return;
            }
            variantId = selectedVariant.dataset.variantId || null;
        }

        fetch('{{ route("client.cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                product_type: type,
                product_id: parseInt(id),
                variant_id: variantId ? parseInt(variantId) : null,
                qty: qty
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Đã thêm vào giỏ hàng!');
            } else {
                alert(data.message || 'Có lỗi xảy ra.');
            }
        })
        .catch(() => alert('Lỗi kết nối. Vui lòng thử lại.'));
    });
});
</script>
@endpush
