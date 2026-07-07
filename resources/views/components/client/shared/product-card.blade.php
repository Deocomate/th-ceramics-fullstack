<div {{ $attributes->merge(['class' => 'flex flex-col group cursor-pointer']) }}>
    <a href="{{ $href }}" class="block">
        <div
            class="product-card relative bg-white rounded-[10px] overflow-hidden mb-2 md:mb-3 transition-all duration-300 group-hover:-translate-y-1 {{ $aspect }}">
            <img src="{{ $image ?? asset('assets/images/ngoi-01.jpg') }}" alt="{{ $alt ?: $title }}"
                class="w-full h-full object-cover {{ $blend ? 'mix-blend-multiply' : '' }}"
                onerror="this.onerror=null; this.src='{{ asset('assets/images/ngoi-01.jpg') }}'" />
            @if ($showOverlay)
                <div class="product-overlay">
                    <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
                    <span>Xem chi tiết</span>
                </div>
            @endif
        </div>
    </a>

    <div class="flex flex-col gap-2 min-w-0 flex-1">
        <a href="{{ $href }}" class="block">
            <h3
                class="{{ $titleClass ?: 'text-[#101010] font-medium text-[16px] leading-[25px] transition-colors group-hover:text-secondary' }}">
                <span class="block lowercase first-letter:uppercase">
                    {{ $title }}
                </span>
            </h3>
        </a>

        @if ($price || $code || $shouldShowAddToCart)
            @if ($price || $code || ($shouldShowAddToCart && $isEcommerceEnabled))
                <div @class([
                    'flex items-center gap-2',
                    'justify-end' => $shouldShowAddToCart && $isEcommerceEnabled && !$price && !$code && !$addToCartAlignStart,
                    'justify-start' => $shouldShowAddToCart && $isEcommerceEnabled && !$price && !$code && $addToCartAlignStart,
                ])>
                    @if ($code || $price)
                        <div class="flex flex-col gap-2 min-w-0 flex-1">
                            @if ($code)
                                <p class="text-[#3C4043] font-light text-[13px] leading-[18px]">
                                    {{ $code }}
                                </p>
                            @endif
                            @if ($price)
                                <p class="text-secondary font-bold text-[14px] md:text-[15px] leading-[20px] min-w-0 truncate">
                                    {{ $price }}
                                </p>
                            @endif
                        </div>
                    @endif

                    @if ($shouldShowAddToCart && $isEcommerceEnabled)
                        <button type="button"
                            class="js-add-to-cart shrink-0 self-center {{ $addToCartButtonClasses }}"
                            data-product-type="{{ $resolvedProductType }}"
                            data-product-id="{{ $resolvedProductId }}"
                            data-product-name="{{ $title }}"
                            @if ($resolvedVariantId) data-variant-id="{{ $resolvedVariantId }}" @endif
                            aria-label="Thêm vào giỏ">
                            @if ($showAddToCartLabel)
                                <span>Thêm vào giỏ</span>
                            @endif
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M15.2045 3.2H4.04707L3.15928 0.544003C3.10562 0.385106 3.00339 0.247095 2.86705 0.149476C2.7307 0.0518578 2.56713 -0.000430263 2.39945 2.66683e-06H0V1.6H1.82358L4.65493 10.104C4.76088 10.4233 4.96475 10.7011 5.23756 10.8978C5.51037 11.0946 5.83825 11.2003 6.17458 11.2H12.2452C12.5707 11.1994 12.8882 11.0996 13.1555 10.9137C13.4227 10.7279 13.6269 10.465 13.7408 10.16L15.9483 4.28C15.994 4.15943 16.0097 4.02958 15.9942 3.9016C15.9787 3.77361 15.9324 3.65129 15.8593 3.54512C15.7861 3.43895 15.6884 3.35209 15.5743 3.29198C15.4603 3.23188 15.3334 3.20031 15.2045 3.2ZM6.40653 12.8C5.98228 12.8 5.57541 12.9686 5.27542 13.2686C4.97543 13.5687 4.8069 13.9757 4.8069 14.4C4.8069 14.8243 4.97543 15.2313 5.27542 15.5314C5.57541 15.8314 5.98228 16 6.40653 16C6.83078 16 7.23765 15.8314 7.53764 15.5314C7.83763 15.2313 8.00616 14.8243 8.00616 14.4C8.00616 13.9757 7.83763 13.5687 7.53764 13.2686C7.23765 12.9686 6.83078 12.8 6.40653 12.8ZM12.0052 12.8C11.581 12.8 11.1741 12.9686 10.8741 13.2686C10.5741 13.5687 10.4056 13.9757 10.4056 14.4C10.4056 14.8243 10.5741 15.2313 10.8741 15.5314C11.1741 15.8314 11.581 16 12.0052 16C12.4295 16 12.8364 15.8314 13.1364 15.5314C13.4363 15.2313 13.6049 14.8243 13.6049 14.4C13.6049 13.9757 13.4363 13.5687 13.1364 13.2686C12.8364 12.9686 12.4295 12.8 12.0052 12.8Z" />
                            </svg>
                        </button>
                    @endif
                </div>
            @endif

            @if ($shouldShowAddToCart && !$isEcommerceEnabled)
                <div class="w-full">
                    <button type="button"
                        class="js-open-consultation {{ $consultationButtonClasses }}"
                        data-product-type="{{ $resolvedProductType }}"
                        data-product-id="{{ $resolvedProductId }}"
                        data-product-name="{{ $title }}"
                        @if ($resolvedVariantId) data-variant-id="{{ $resolvedVariantId }}" @endif
                        aria-label="Liên hệ">
                        <span>Liên hệ</span>
                    </button>
                </div>
            @endif
        @endif
    </div>

    @if (!$price && !$code && !$shouldShowAddToCart && $slot->isNotEmpty())
        <div class="mt-2">
            {{ $slot }}
        </div>
    @endif
</div>
