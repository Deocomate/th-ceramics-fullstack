@if ($items->isNotEmpty())
    <section
        class="w-full pb-0 md:pb-16 bg-background-secondary relative recommendations-section {{ $showDecor ? 'overflow-visible' : '' }}"
        @if ($compareTable) data-compare-table="true" @endif data-aos="fade-up">
        @if ($showDecor)
            <img src="{{ asset('assets/images/gtt-decorate-right.svg') }}"
                class="absolute -left-[15%] md:-left-[20%] lg:-left-[20%] -translate-y-1/2 lg:top-[5%] w-[45%] md:w-[40%] lg:w-[35%] opacity-60 pointer-events-none z-0"
                alt="" />
        @endif

        <div class="max-w-[1320px] w-[85%] mx-auto relative z-10">
            <h2
                class="text-[20px] md:text-[32px] font-semibold text-secondary text-center uppercase mb-5 md:mb-[30px] font-archivo leading-[62.5px]">
                CÓ THỂ BẠN QUAN TÂM
            </h2>

            <div class="relative">
                <div class="overflow-x-auto pb-6 md:pb-[19px] custom-recommend-scrollbar mobile-scroll-visible"
                    data-scroll-indicator-init="true">
                    <div class="min-w-[900px] md:min-w-[1000px] w-max">
                        <!-- Row 1: Thẻ sản phẩm -->
                        <div class="flex mb-4 md:mb-[15px]">
                            <div class="w-0 h-full shrink-0 sticky left-0 z-20 overflow-visible"></div>
                            <div class="flex gap-4 md:gap-[40px]">
                                @foreach ($items as $product)
                                    <x-client.shared.product-card href="{{ $product['url'] }}"
                                        image="{{ $product['image'] }}" title="{{ $product['name'] }}"
                                        class="w-[175px] md:w-[222px] shrink-0 text-left flex flex-col"
                                        aspect="aspect-square bg-gray-100 shadow-sm mb-2 md:mb-4"
                                        title-class="font-bold text-[12px] md:text-base leading-snug mb-0 md:mb-1 text-[#2162A1] hover:text-secondary transition-colors text-left w-full"
                                        :show-overlay="true"
                                        :product-type="$product['type']"
                                        :product-id="$product['id']"
                                        :variant-id="$product['variant_id']"
                                        add-to-cart-variant="filled" />
                                @endforeach
                            </div>
                        </div>

                        <!-- Row 2: So sánh Giá -->
                        <div class="flex border-t border-black/10 group mobile-compare-row">
                            <div class="w-0 h-full shrink-0 sticky left-0 z-20 overflow-visible">
                                <span class="mobile-compare-label">Giá</span>
                            </div>
                            <div class="flex gap-4 md:gap-[40px]">
                                @foreach ($items as $product)
                                    <div class="compare-cell-text w-[175px] md:w-[222px] shrink-0 text-[12px] text-primary/80">
                                        {{ $product['price_display'] }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Row 3: So sánh Màu sắc -->
                        <div class="flex border-t border-black/10 group mobile-compare-row">
                            <div class="w-0 h-full shrink-0 sticky left-0 z-20 overflow-visible">
                                <span class="mobile-compare-label">Màu sắc</span>
                            </div>
                            <div class="flex gap-4 md:gap-[40px]">
                                @foreach ($items as $product)
                                    <div class="compare-cell-text w-[175px] md:w-[222px] shrink-0 text-[12px] text-primary/80">
                                        {{ $product['color'] }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Row 4: So sánh Kích thước -->
                        <div class="flex border-y border-black/10 group mobile-compare-row">
                            <div class="w-0 h-full shrink-0 sticky left-0 z-20 overflow-visible">
                                <span class="mobile-compare-label">Kích thước</span>
                            </div>
                            <div class="flex gap-4 md:gap-[40px]">
                                @foreach ($items as $product)
                                    <div class="compare-cell-text w-[175px] md:w-[222px] shrink-0 text-[12px] text-primary/80 leading-relaxed">
                                        {{ $product['size'] }}
                                    </div>
                                @endforeach
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
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin !important;
                scrollbar-color: rgba(199, 110, 0, 0.35) rgba(250, 250, 250, 0.8) !important;
            }

            .custom-recommend-scrollbar::-webkit-scrollbar {
                height: 12px !important;
                display: block !important;
            }

            .custom-recommend-scrollbar::-webkit-scrollbar-track {
                background: rgba(250, 250, 250, 0.8) !important;
                border: 1px solid rgba(199, 110, 0, 0.2) !important;
                border-radius: 6px !important;
            }

            .custom-recommend-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(199, 110, 0, 0.35) !important;
                border-radius: 6px !important;
                border: 2px solid rgba(250, 250, 250, 0.8) !important;
            }

            .custom-recommend-scrollbar::-webkit-scrollbar-thumb:hover {
                background: rgba(199, 110, 0, 0.55) !important;
            }

            /* Disable arrow buttons for a clean, modern look */
            .custom-recommend-scrollbar::-webkit-scrollbar-button {
                display: none !important;
            }

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

            /* Custom overrides to match Figma styles */
            .recommendations-section h3 span {
                text-transform: none !important;
                text-transform: capitalize !important;
                font-family: Archivo, sans-serif !important;
                font-weight: 600 !important;
                font-size: 11px !important;
                line-height: 1.6 !important;
                color: #2162A1 !important;
                text-align: left !important;
                display: block !important;
            }

            .recommendations-section h3 span:hover {
                color: #C76E00 !important;
            }

            @media (min-width: 768px) {
                .recommendations-section h3 span {
                    font-size: 16px !important;
                    line-height: 1.55 !important;
                }

                .recommendations-section .mobile-compare-label {
                    font-size: 14px;
                    line-height: 24px;
                }

                .recommendations-section .compare-cell-text {
                    font-size: 16px;
                    line-height: 1.5;
                }
            }
        </style>
    @endpush
@endif
