@props([
    'trangChu' => null,
    'ngoiAmDuongs' => null,
    'ngoiHais' => null,
    'gachHoas' => null,
    'about' => null,
    'factory' => null,
    'showroomImages' => null,
    'showroomContent' => null,
    'news' => null,
    'article' => null,
    'articles' => null,
    'relatedArticles' => null,
    'historyArticles' => null,
    'projects' => null,
    'project' => null,
    'relatedProjects' => null,
    'categories' => null,
    'selectedCategory' => null,
    'currentCategory' => null,
    'config' => null,
    'products' => null,
    'boNocChuVanProducts' => null,
    'relatedProducts' => null,
    'product' => null,
    'colors' => null,
    'dinhMuc' => null,
    'giaTriVuotTroi' => null,
    'parentConfig' => null,
    'pageLabel' => null,
    'indexRouteName' => null,
    'categoryType' => null,
    'categoryLabel' => null,
    'denGomProducts' => null,
    'denSuProducts' => null,
    'featuredProducts' => null,
    'collectionProducts' => null,
    'ngheProducts' => null,
    'linhVatProducts' => null,
    'bgImage' => null,
    'activeOrder' => false,
    'activeAccount' => false,
    'activeCatalog' => false,
    'activeGuide' => false,
    'activeProcess' => false,
    'activePrivacy' => false,
    'activeReturn' => false,
    'activeShipping' => false,
    'image' => null,
    'label1' => null,
    'rate1' => null,
    'label2' => null,
    'rate2' => null,
    'sectionId' => null,
    'sectionClass' => null,
    'sectionTitle' => null,
    'desktopLinkHref' => null,
    'detailRouteName' => null,
    'wrapperClass' => null,
    'titleClass' => null,
    'title' => null,
    'subtitle' => null,
    'description' => null,
    'items' => null,
])
@if ($boNocChuVanProducts->isNotEmpty())
    @php
        $assetUrl = fn(?string $path, ?string $fallback = null) => \App\Support\AssetPath::url($path, $fallback);
        $productImageUrl = fn($product, string $fallback) => $assetUrl(data_get($product, 'images.0'), $fallback);
    @endphp

    <section class="w-full pb-[30px] md:pb-16">
        <div class="md:bg-[#EBCFC2] opacity-80 p-6 lg:p-0" style="margin-right: max(0px, calc((100% - 1320px) / 2))"
            data-aos="fade-up" data-aos-delay="100">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-16 max-w-[1320px] ml-auto lg:py-10 lg:pr-10">
                <div class="w-full lg:w-[45%] flex flex-col justify-stretch">
                    <div
                        class="w-full flex-grow relative shadow-lg overflow-hidden bg-black/5 min-h-[400px] lg:min-h-[500px]">
                        <img src="{{ $assetUrl($config->sec2_image, 'assets/images/dao-kim.png') }}" alt=""
                            class="absolute inset-0 w-full h-full object-cover">

                        <div
                            class="absolute bottom-6 left-1/2 -translate-x-1/2 w-[95%] max-w-[560px] z-10 hover:scale-105 transition-transform duration-300">
                            <div class="relative w-full flex items-center justify-center">
                                <img src="{{ asset('assets/images/brush.svg') }}" alt=""
                                    class="w-full drop-shadow-xl">
                                <span
                                    class="absolute text-white font-bold text-[24px] md:text-[32px] uppercase tracking-wider"
                                    style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4)">
                                    {{ $config->sec2_title ?: 'BỜ NÓC CHỮ VẠN' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-[55%] flex flex-col justify-between">
                    <div class="lg:hidden" data-product-section>
                        <div class="flex overflow-x-auto scroll-smooth snap-x snap-mandatory scrollbar-hide"
                            data-product-carousel>
                            @foreach ($boNocChuVanProducts->chunk(4) as $chunk)
                                <article class="flex-shrink-0 w-full snap-start pb-1" data-product-slide>
                                    <div class="grid grid-cols-2 gap-4">
                                        @foreach ($chunk as $product)
                                            <x-client.shared.product-card
                                                href="{{ route('client.products.phu-kien-ngoi.bo-noc-chu-van.detail', $product->phu_kien_ngoi_ct_id) }}"
                                                image="{{ $productImageUrl($product, 'assets/images/pk-03.jpg') }}"
                                                title="{{ $product->name }}" code="MSP: {{ $product->display_code }}"
                                                price="{{ $product->display_price }}"
                                                detail-route-name="client.products.phu-kien-ngoi.bo-noc-chu-van.detail"
                                                :product="$product" />
                                        @endforeach
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-5 flex justify-center gap-[7px]" aria-label="Product mobile pagination">
                            @foreach ($boNocChuVanProducts->chunk(4) as $index => $chunk)
                                <button
                                    class="product-section-dot h-2 w-2 rounded-full {{ $index === 0 ? 'bg-secondary' : 'bg-[#C76E00]/30' }}"
                                    data-product-dot="{{ $index }}" type="button"
                                    aria-label="Slide {{ $index + 1 }}"
                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
                            @endforeach
                        </div>
                    </div>

                    <!-- BẮT ĐẦU: Desktop Slider Bờ Nóc Chữ Vạn -->
                    <div class="hidden lg:block relative min-h-[450px]" data-desktop-slider="chu-van">
                        <div class="slider-pages-container relative pb-[40px]">
                            @foreach ($boNocChuVanProducts->chunk(4) as $index => $chunk)
                                <div class="grid grid-cols-2 gap-x-8 lg:gap-x-16 gap-y-10 lg:gap-y-12 mb-10 {{ $index === 0 ? '' : 'hidden' }}"
                                    data-slider-page="{{ $index }}">
                                    @foreach ($chunk as $product)
                                        <x-client.shared.product-card
                                            href="{{ route('client.products.phu-kien-ngoi.bo-noc-chu-van.detail', $product->phu_kien_ngoi_ct_id) }}"
                                            image="{{ $productImageUrl($product, 'assets/images/pk-03.jpg') }}"
                                            title="{{ $product->name }}"
                                            title-class="font-bold text-[#212121] text-[14px] lg:text-[15px] uppercase -mb-[5px] tracking-wide transition-colors group-hover:text-secondary"
                                            code="MSP: {{ $product->display_code }}"
                                            price="{{ $product->display_price }}" :show-overlay="true"
                                            detail-route-name="client.products.phu-kien-ngoi.bo-noc-chu-van.detail"
                                            :product="$product" />
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        @if ($boNocChuVanProducts->count() > 4)
                            <div
                                class="hidden lg:flex items-center justify-center gap-5 mt-10 lg:mt-auto absolute bottom-0 left-0 right-0">
                                <button data-btn-prev
                                    class="w-[40px] h-[40px] rounded-full border border-secondary flex items-center justify-center text-secondary hover:bg-secondary hover:text-white transition-all duration-300 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-transparent disabled:hover:text-secondary"
                                    type="button" aria-label="Sản phẩm trước">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                </button>
                                <button data-btn-next
                                    class="w-[40px] h-[40px] rounded-full bg-secondary flex items-center justify-center text-white hover:opacity-90 transition-all duration-300 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-secondary"
                                    type="button" aria-label="Sản phẩm tiếp theo">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                    <!-- KẾT THÚC: Desktop Slider Bờ Nóc Chữ Vạn -->
                </div>
            </div>
        </div>
    </section>
@endif
