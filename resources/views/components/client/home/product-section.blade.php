@props([
    'sectionClass' => '',
    'sectionTitle' => '',
    'desktopLinkHref' => '',
    'products' => collect(),
    'detailRouteName' => '',
])

@php
    if (!function_exists('getProductImageUrl')) {
        function getProductImageUrl($product)
        {
            $productImage = !empty($product->images) ? $product->images[0] : null;
            return $productImage
                ? (Str::startsWith($productImage, 'assets/')
                    ? asset($productImage)
                    : asset('storage/' . $productImage))
                : asset('assets/images/placeholder.jpg');
        }
    }

    if (!function_exists('getHomeProductCode')) {
        function getHomeProductCode($product)
        {
            $variantCode = $product->relationLoaded('mauSacs') ? optional($product->mauSacs->first())->code : null;

            return $product->code ?? ($variantCode ?? '—');
        }
    }

    if (!function_exists('getHomeProductPrice')) {
        function getHomeProductPrice($product)
        {
            $basePrice = (float) ($product->price ?? 0);
            $variantPrice = $product->relationLoaded('mauSacs')
                ? (float) (optional($product->mauSacs->first())->price ?? 0)
                : 0;

            $price = $basePrice > 0 ? $basePrice : $variantPrice;

            return $price > 0 ? number_format($price, 0, ',', '.') . ' đ/m²' : 'Liên hệ';
        }
    }

    if (!function_exists('getHomeProductUrl')) {
        function getHomeProductUrl($product, $detailRouteName)
        {
            if (!$detailRouteName || !\Illuminate\Support\Facades\Route::has($detailRouteName)) {
                return '#';
            }

            return route($detailRouteName, $product->getKey());
        }
    }
@endphp

<section class="bg-neutral-2 {{ $sectionClass }}" data-product-section>
    <div class="w-[85%] max-w-[1320px] mx-auto">
        <div class="flex justify-between items-center mb-6 lg:mb-12" data-aos="fade-up">
            <h2 class="text-primary text-[20px] leading-[32px] lg:text-4xl lg:leading-10 font-bold uppercase">
                {{ $sectionTitle }}
            </h2>
            <a href="{{ $desktopLinkHref }}"
                class="hidden lg:flex text-secondary font-bold text-base uppercase hover:opacity-80 transition-opacity items-center gap-2">
                Xem thêm
                <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M10.707 2.29292C10.5184 2.11076 10.2658 2.00997 10.0036 2.01224C9.7414 2.01452 9.49059 2.11969 9.30518 2.3051C9.11977 2.49051 9.0146 2.74132 9.01233 3.00352C9.01005 3.26571 9.11084 3.51832 9.293 3.70692L12.586 6.99992H1C0.734784 6.99992 0.48043 7.10528 0.292893 7.29281C0.105357 7.48035 0 7.7347 0 7.99992C0 8.26514 0.105357 8.51949 0.292893 8.70703C0.48043 8.89456 0.734784 8.99992 1 8.99992H12.586L9.293 12.2929C9.19749 12.3852 9.12131 12.4955 9.0689 12.6175C9.01649 12.7395 8.9889 12.8707 8.98775 13.0035C8.9866 13.1363 9.0119 13.268 9.06218 13.3909C9.11246 13.5138 9.18671 13.6254 9.28061 13.7193C9.3745 13.8132 9.48615 13.8875 9.60905 13.9377C9.73194 13.988 9.86362 14.0133 9.9964 14.0122C10.1292 14.011 10.2604 13.9834 10.3824 13.931C10.5044 13.8786 10.6148 13.8024 10.707 13.7069L15.707 8.70692C15.8945 8.51939 15.9998 8.26508 15.9998 7.99992C15.9998 7.73475 15.8945 7.48045 15.707 7.29292L10.707 2.29292Z"
                        fill="currentColor" />
                </svg>
            </a>
        </div>

        @if ($products->isNotEmpty())
            <div class="lg:hidden" data-product-carousel-shell>
                <div class="flex overflow-x-auto scroll-smooth snap-x snap-mandatory scrollbar-hide"
                    data-product-carousel>
                    @foreach ($products->chunk(4) as $chunk)
                        <article class="flex-shrink-0 w-full snap-start pb-1" data-product-slide>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach ($chunk as $product)
                                    @php
                                        $imageUrl = getProductImageUrl($product);
                                        $detailUrl = getHomeProductUrl($product, $detailRouteName);
                                    @endphp
                                    <x-client.shared.product-card
                                        href="{{ $detailUrl }}"
                                        image="{{ $imageUrl }}"
                                        title="{{ $product->name }}"
                                        code="MSP: {{ getHomeProductCode($product) }}"
                                        price="{{ getHomeProductPrice($product) }}"
                                        :blend="false"
                                        :product="$product"
                                        :detail-route-name="$detailRouteName"
                                    />
                                @endforeach
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-5 flex justify-center gap-[7px]" aria-label="Product mobile pagination">
                    @foreach ($products->chunk(4) as $index => $chunk)
                        <button
                            class="product-section-dot h-2 w-2 rounded-full {{ $index === 0 ? 'bg-secondary' : 'bg-[#C76E00]/30' }}"
                            data-product-dot="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            </div>

            <div class="hidden lg:grid grid-cols-4 gap-6" data-aos="fade-up" data-aos-delay="200">
                @foreach ($products->take(4) as $product)
                    @php
                        $imageUrl = getProductImageUrl($product);
                        $detailUrl = getHomeProductUrl($product, $detailRouteName);
                    @endphp
                    <div class="flex flex-col">
                        <x-client.shared.product-card href="{{ $detailUrl }}" image="{{ $imageUrl }}"
                            title="{{ $product->name }}" code="MSP: {{ getHomeProductCode($product) }}"
                            price="Giá: {{ getHomeProductPrice($product) }}" :blend="false" :show-overlay="true"
                            :product="$product" :detail-route-name="$detailRouteName" />
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500 py-12">Chưa có sản phẩm nào.</p>
        @endif
    </div>
</section>

@push('scripts')
    <script>
        (function() {
            document.querySelectorAll('[data-product-section]').forEach(function(section) {
                const dots = Array.from(section.querySelectorAll('[data-product-dot]'));
                const carousel = section.querySelector('[data-product-carousel]');
                if (!carousel || !dots.length) return;

                const updateDots = function() {
                    const idx = Math.round(carousel.scrollLeft / carousel.offsetWidth);
                    dots.forEach(function(dot, i) {
                        if (i === idx) {
                            dot.classList.add('bg-secondary');
                            dot.classList.remove('bg-[#C76E00]/30');
                        } else {
                            dot.classList.remove('bg-secondary');
                            dot.classList.add('bg-[#C76E00]/30');
                        }
                    });
                };

                carousel.addEventListener('scroll', updateDots);

                dots.forEach(function(dot, idx) {
                    dot.addEventListener('click', function() {
                        carousel.scrollTo({
                            left: carousel.offsetWidth * idx,
                            behavior: 'smooth'
                        });
                    });
                });
            });
        })();
    </script>
@endpush
