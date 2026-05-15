@props([
    'relatedProducts' => collect(),
    'showDecor' => false,
    'routeName' => 'client.products.ngoi-am-duong.detail',
    'pkField' => 'id',
    'productType' => null,
    'compareTable' => false,
])

@php
    $items = collect($relatedProducts)->values();
@endphp

@if ($items->isNotEmpty())
    <section class="w-full pb-0 md:pb-16 bg-background-secondary relative {{ $showDecor ? 'overflow-visible' : '' }}"
        @if ($compareTable) data-compare-table="true" @endif
        data-aos="fade-up">
        @if ($showDecor)
            <img src="{{ asset('assets/images/gtt-decorate-right.svg') }}"
                class="absolute -left-[15%] md:-left-[20%] lg:-left-[20%] -translate-y-1/2 lg:top-[5%] w-[45%] md:w-[40%] lg:w-[35%] opacity-60 pointer-events-none z-0"
                alt="" />
        @endif

        <div class="max-w-[1320px] w-[85%] mx-auto relative z-10">
            <h2 class="text-[20px] md:text-3xl font-semibold text-secondary text-center uppercase mb-5 md:mb-16">
                CÓ THỂ BẠN QUAN TÂM
            </h2>

            <div class="relative">
                <div class="overflow-x-auto pb-6 custom-recommend-scrollbar mobile-scroll-visible" data-scroll-indicator-init="true">
                    <div class="min-w-[900px] md:min-w-[1000px] w-max">
                        <div class="flex gap-0 md:gap-10 mb-4 md:mb-8">
                            <div class="hidden md:block w-[140px] shrink-0 sticky left-0 bg-background-secondary z-10">
                            </div>

                            <div class="flex-grow flex gap-4 md:gap-10">
                                @foreach ($items as $product)
                                    @php
                                        $productId =
                                            data_get($product, $pkField) ??
                                            (data_get($product, 'id') ?? (is_object($product) ? $product->getKey() : null));
                                        $accessoryType = data_get($product, 'category_type') ?: data_get($product, 'accessory_type');
                                        $targetRouteName = $routeName;
                                        if ($routeName === 'client.products.phu-kien-ngoi.detail' && $accessoryType) {
                                            $targetRouteName = $accessoryType === 'chu_van'
                                                ? 'client.products.phu-kien-ngoi.bo-noc-chu-van.detail'
                                                : 'client.products.phu-kien-ngoi.ngoi-bo-noc.detail';
                                        }
                                        $routeParams = $productId;
                                        $productUrl =
                                            data_get($product, 'url') ?:
                                            ($targetRouteName &&
                                            $productId &&
                                            \Illuminate\Support\Facades\Route::has($targetRouteName)
                                                ? route($targetRouteName, $routeParams)
                                                : '#');
                                        $rawImage =
                                            data_get($product, 'image') ?:
                                            collect(data_get($product, 'images', []))->first();
                                        $productImage = \App\Support\AssetPath::url(
                                            $rawImage,
                                            'assets/images/gach-co-work-2.jpg',
                                        );
                                        $productName = data_get($product, 'name', 'Sản phẩm');
                                        $productPrice =
                                            (float) (data_get($product, 'price') ?? data_get($product, 'min_price', 0));
                                        $rawColor = data_get($product, 'color') ?? data_get($product, 'color_name');
                                        $productColor = filled($rawColor) ? $rawColor : 'Tự chọn';
                                        $rawSize = data_get($product, 'size') ?? data_get($product, 'dimension');
                                        $productSize = filled($rawSize) ? $rawSize : '--';
                                        $resolvedType = $productType ?: (is_object($product) ? str($product::class)->classBasename()->snake() : null);
                                    @endphp
                                    <div class="w-[175px] md:w-[220px] shrink-0 flex flex-col items-start">
                                        <a href="{{ $productUrl }}" class="w-full group">
                                            <div
                                                class="product-card relative aspect-square w-full mb-2 md:mb-4 bg-gray-100 rounded-sm overflow-hidden">
                                                <img src="{{ $productImage }}" alt="{{ $productName }}"
                                                    class="object-cover w-full h-full"
                                                    onerror="this.onerror=null; this.src='{{ asset('assets/images/ngoi-01.jpg') }}'" />
                                                <div class="product-overlay">
                                                    <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
                                                    <span>Xem chi tiết</span>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="{{ $productUrl }}"
                                            class="text-[#004B8D] hover:text-secondary transition-colors">
                                            <h3
                                                class="font-bold text-[12px] md:text-base leading-snug h-6 md:h-12 overflow-hidden mb-0 md:mb-2">
                                                {{ $productName }}
                                            </h3>
                                        </a>
                                        @if ($productPrice > 0 && $resolvedType && $resolvedType !== 'den_vuon_gom_su_ct')
                                            <button type="button"
                                                class="border border-secondary text-secondary text-[9px] md:text-[13px] font-bold py-1 md:py-2 px-4 md:px-6 rounded-full hover:bg-secondary hover:text-white transition-all whitespace-nowrap js-add-to-cart"
                                                data-product-type="{{ $resolvedType }}"
                                                data-product-id="{{ $productId }}"
                                                data-product-name="{{ $productName }}">
                                                Thêm vào giỏ
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex flex-col border-t border-black/10">
                            <div class="flex gap-0 md:gap-10 group mobile-compare-row">
                                <div
                                    class="hidden md:flex w-[140px] shrink-0 text-base font-bold text-primary items-start sticky left-0 bg-background-secondary z-10">
                                    <span class="compare-cell-text">Giá</span>
                                </div>
                                <div class="flex-grow flex md:gap-10 items-start">
                                    <div class="md:hidden w-0 h-full shrink-0 sticky left-0 z-20 overflow-visible">
                                        <span class="mobile-compare-label">Giá</span>
                                    </div>
                                    @foreach ($items as $product)
                                        <div
                                            class="compare-cell-text w-[175px] md:w-[220px] shrink-0 text-[12px] md:text-base text-primary/80 mr-4 md:mr-auto">
                                            {{ (data_get($product, 'price') ?? data_get($product, 'min_price', 0)) > 0 ? number_format((float) (data_get($product, 'price') ?? data_get($product, 'min_price', 0)), 0, ',', '.') . ' đ/m²' : 'Liên hệ' }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex gap-0 md:gap-10 border-t border-black/10 group mobile-compare-row">
                                <div
                                    class="hidden md:flex w-[140px] shrink-0 text-base font-bold text-primary items-start sticky left-0 bg-background-secondary z-10">
                                    <span class="compare-cell-text">Màu sắc</span>
                                </div>
                                <div class="flex-grow flex md:gap-10 items-start">
                                    <div class="md:hidden w-0 h-full shrink-0 sticky left-0 z-20 overflow-visible">
                                        <span class="mobile-compare-label">Màu sắc</span>
                                    </div>
                                    @foreach ($items as $product)
                                        <div
                                            class="compare-cell-text w-[175px] md:w-[220px] shrink-0 text-[12px] md:text-base text-primary/80 mr-4 md:mr-auto">
                                            @php
                                                $rawColor = data_get($product, 'color') ?? data_get($product, 'color_name');
                                            @endphp
                                            {{ filled($rawColor) ? $rawColor : 'Tự chọn' }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex md:gap-10 border-y border-black/10 group mobile-compare-row">
                                <div
                                    class="hidden md:flex w-[140px] shrink-0 text-base font-bold text-primary items-start sticky left-0 bg-background-secondary z-10">
                                    <span class="compare-cell-text">Kích thước</span>
                                </div>
                                <div class="flex-grow flex md:gap-10 items-start">
                                    <div class="md:hidden w-0 h-full shrink-0 sticky left-0 z-20 overflow-visible">
                                        <span class="mobile-compare-label">Kích thước</span>
                                    </div>
                                    @foreach ($items as $product)
                                        <div
                                            class="compare-cell-text w-[175px] md:w-[220px] shrink-0 text-[12px] md:text-base text-primary/80 leading-relaxed mr-4 md:mr-auto">
                                            @php
                                                $rawSize = data_get($product, 'size') ?? data_get($product, 'dimension');
                                            @endphp
                                            {{ filled($rawSize) ? $rawSize : '--' }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .custom-recommend-scrollbar {
                scrollbar-color: rgba(199, 110, 0, 0.8) rgba(0, 0, 0, 0.08);
                scrollbar-width: thin;
                -webkit-overflow-scrolling: touch;
            }

            .custom-recommend-scrollbar::-webkit-scrollbar {
                height: 6px !important;
                -webkit-appearance: none;
            }

            .custom-recommend-scrollbar::-webkit-scrollbar-track {
                background: rgba(0, 0, 0, 0.08) !important;
                border-radius: 9999px;
            }

            .custom-recommend-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(199, 110, 0, 0.8) !important;
                border-radius: 9999px;
            }

            .custom-recommend-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #c76e00 !important;
            }

            /* CSS UI so sánh */
            .compare-cell-text {
                margin-top: 27px;
                margin-bottom: 7px;
                padding-left: 0;
                text-align: left;
                text-indent: 0;
            }

            .mobile-compare-label {
                position: absolute;
                top: 5px;
                left: 0;
                color: #2e2f2a;
                font-size: 12px;
                font-family: Archivo, sans-serif;
                font-weight: 700;
                line-height: 21px;
                word-wrap: break-word;
                white-space: nowrap;
            }

            @media (min-width: 768px) {
                .mobile-compare-row {
                    height: auto;
                    align-items: center;
                }

                .mobile-compare-row>div {
                    align-items: center;
                }

                .compare-cell-text {
                    margin-top: 0;
                    margin-bottom: 0;
                    padding-top: 16px;
                    padding-bottom: 16px;
                    display: flex;
                    align-items: center;
                    justify-content: flex-start;
                }
            }
        </style>
    @endpush
@endif
