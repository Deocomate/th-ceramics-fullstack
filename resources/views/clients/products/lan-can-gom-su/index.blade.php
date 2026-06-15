<x-client.layouts.main title="Lan Can Gốm Sứ" data-page="products"
    main-class="flex-grow page-lan-can-gom-su bg-background-secondary">

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
            @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap");
            @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Italianno&display=swap");

            @media (max-width: 767.98px) {
                .page-lan-can-gom-su .lan-can-category-title {
                    color: #c76e00;
                    font-size: 24px;
                    font-family: "Archivo", sans-serif;
                    font-weight: 600;
                    text-transform: uppercase;
                    line-height: 32px;
                    overflow-wrap: break-word;
                }

                .page-lan-can-gom-su h2[class*="text-[20px] md:text-3xl font-semibold text-secondary uppercase"]:not(.lan-can-category-title) {
                    color: #c76e00;
                    font-size: 20px;
                    font-family: "Archivo", sans-serif;
                    font-weight: 600;
                    text-transform: uppercase;
                    line-height: 32px;
                    overflow-wrap: break-word;
                }

                .page-lan-can-gom-su .lan-can-brush-title {
                    color: #fff;
                    font-size: 24px;
                    font-family: "Archivo", sans-serif;
                    font-weight: 700;
                    text-transform: uppercase;
                    line-height: 36px;
                    letter-spacing: 1.2px;
                    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4);
                }
            }

            /* Thêm animation mượt mà khi chuyển trang sản phẩm */
            .fade-grid {
                animation: fadeInGrid 0.4s ease-in-out forwards;
            }

            @keyframes fadeInGrid {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endpush

    <x-client.shared.catalog-sticky-btn />

    <!-- Banner Section -->
    <section
        class="relative h-[320px] md:h-[70vh] md:min-h-[500px] md:max-h-[740px] lg:h-[663px] flex items-center justify-center text-neutral-1 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ $config->thumbnail_main ? asset('storage/' . $config->thumbnail_main) : asset('assets/images/about-02.jpg') }}"
                alt="Banner Lan Can Gốm Sứ" class="w-full h-full object-cover">
            <div class="absolute inset-0"
                style="background: linear-gradient(180deg, rgba(0, 0, 0, 0) 16.89%, rgba(21, 21, 21, 0.5) 75.07%, rgba(0, 0, 0, 0.5) 99.72%), linear-gradient(0deg, rgba(46, 47, 42, 0.59) 0%, rgba(46, 47, 42, 0.59) 100%);">
            </div>
        </div>

        <div class="relative z-10 text-center container mx-auto px-4 flex flex-col items-center" data-aos="fade-up"
            data-aos-duration="1000">
            <h1
                class="text-[20px] leading-[28px] tracking-[0.5px] font-semibold mb-[1px] md:text-2xl md:mb-4 md:tracking-wide uppercase text-neutral-1">
                GỐM SỨ THANH HẢI
            </h1>
            <h2
                class="text-[32px] leading-[44px] font-archivo font-extrabold uppercase md:text-[48px] md:leading-[65px] max-w-4xl text-neutral-1">
                LAN CAN GỐM SỨ
            </h2>
        </div>
    </section>

    @php
        // Xử lý dữ liệu động cho Section 1
        $section1Image = $config->section_1_image
            ? asset('storage/' . $config->section_1_image)
            : asset('assets/images/lan-can-giot-le.jpg');
        $section1Title = $config->section_1_title ?? 'LAN CAN GIỌT LỆ';
        $section1ProductIds = is_array($config->section_1_products) ? $config->section_1_products : null;
        $section1Products = $section1ProductIds
            ? $products->whereIn('lan_can_gom_su_ct_id', $section1ProductIds)->values()
            : $products->take(8);

        // Xử lý dữ liệu động cho Section 2
        $section2Image = $config->section_2_image
            ? asset('storage/' . $config->section_2_image)
            : asset('assets/images/lan-can-bau.png');
        $section2Title = $config->section_2_title ?? 'LAN CAN BẦU';
        $section2ProductIds = is_array($config->section_2_products) ? $config->section_2_products : null;
        $section2Products = $section2ProductIds
            ? $products->whereIn('lan_can_gom_su_ct_id', $section2ProductIds)->values()
            : $products->skip(8)->take(8);
    @endphp

    <!-- Danh Mục Sản Phẩm Section 1 -->
    @if ($section1Products->isNotEmpty())
        <section class="w-full md:pb-16 pt-[30px]">
            <div class="flex items-center justify-center mt-0 mb-[30px] lg:mt-8 lg:mb-16" data-aos="fade-up">
                <h2 class="lan-can-category-title text-[20px] md:text-3xl font-semibold text-secondary uppercase">
                    Danh Mục Sản Phẩm
                </h2>
            </div>

            <div class="bg-[#EBCFC2] opacity-80 p-6 lg:p-0" style="margin-left: max(0px, calc((100% - 1320px) / 2))"
                data-aos="fade-up" data-aos-delay="100">
                <div class="flex flex-col-reverse lg:flex-row gap-8 lg:gap-16 max-w-[1320px] lg:py-10 lg:pl-10">
                    <!-- Khối hiển thị sản phẩm bên trái -->
                    <div class="w-full lg:w-[55%] flex flex-col justify-between">

                        <div id="sec1-wrapper">
                            @php $chunks1 = $section1Products->chunk(4); @endphp
                            @foreach ($chunks1 as $index => $chunk)
                                <div
                                    class="sec1-page {{ $index === 0 ? 'grid' : 'hidden' }} grid-cols-2 gap-x-8 lg:gap-x-16 gap-y-8 lg:gap-y-12 mb-10 fade-grid">
                                    @foreach ($chunk as $item)
                                        <x-client.shared.product-card
                                            href="{{ route('client.products.lan-can-gom-su.detail', $item->lan_can_gom_su_ct_id) }}"
                                            image="{{ $item->images ? asset('storage/' . $item->images[0]) : asset('assets/images/lan-can-01.jpg') }}"
                                            title="{{ $item->name }}"
                                            title-class="font-bold text-[#212121] text-[14px] lg:text-[15px] -mb-[5px] tracking-wide transition-colors group-hover:text-secondary"
                                            code="MSP: {{ $item->display_code }}" price="{{ $item->display_price }}"
                                            :show-overlay="true"
                                            product-type="lan_can_gom_su_ct"
                                            :product-id="$item->lan_can_gom_su_ct_id"
                                            :product="$item" />
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <!-- Phần Nút điều hướng cố định (Chỉ hiện khi có > 4 sản phẩm) -->
                        @if ($chunks1->count() > 1)
                            <div class="flex items-center justify-center gap-5 md:mt-10 lg:mt-auto">
                                <button onclick="changePage('sec1', -1)"
                                    class="w-[40px] h-[40px] rounded-full border border-secondary flex items-center justify-center text-secondary hover:bg-secondary hover:text-white transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                </button>
                                <button onclick="changePage('sec1', 1)"
                                    class="w-[40px] h-[40px] rounded-full bg-secondary flex items-center justify-center text-white hover:opacity-90 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Khối hình ảnh Banner nổi bật bên phải -->
                    <div class="w-full lg:w-[45%] flex flex-col justify-stretch">
                        <div
                            class="w-full flex-grow relative shadow-xl overflow-hidden bg-black/5 min-h-[400px] lg:min-h-[500px] border border-black/10">
                            <img src="{{ $section1Image }}" alt="{{ $section1Title }}"
                                class="absolute inset-0 w-full h-full object-cover">
                            <div
                                class="absolute bottom-6 left-1/2 -translate-x-1/2 w-[85%] max-w-[560px] z-10 hover:scale-105 transition-transform duration-300">
                                <div class="relative w-full flex items-center justify-center">
                                    <img src="{{ asset('assets/images/brush.svg') }}" alt="brush background"
                                        class="w-full drop-shadow-xl opacity-90">
                                    <span
                                        class="lan-can-brush-title absolute text-white font-bold text-[24px] md:text-[32px] uppercase tracking-wider"
                                        style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4)">
                                        {{ $section1Title }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Danh Mục Sản Phẩm Section 2 -->
    @if ($section2Products->isNotEmpty())
        <section class="w-full pb-[30px] md:pb-16">
            <div class="bg-[#EBCFC2] opacity-80 p-6 lg:p-0" style="margin-right: max(0px, calc((100% - 1320px) / 2))"
                data-aos="fade-up" data-aos-delay="100">
                <div class="flex flex-col lg:flex-row gap-8 lg:gap-16 max-w-[1320px] ml-auto lg:py-10 lg:pr-10">
                    <!-- Khối hình ảnh Banner nổi bật bên trái -->
                    <div class="w-full lg:w-[45%] flex flex-col justify-stretch">
                        <div
                            class="w-full flex-grow relative shadow-xl overflow-hidden bg-black/5 min-h-[400px] lg:min-h-[500px] border border-black/10">
                            <img src="{{ $section2Image }}" alt="{{ $section2Title }}"
                                class="absolute inset-0 w-full h-full object-cover">
                            <div
                                class="absolute bottom-6 left-1/2 -translate-x-1/2 w-[85%] max-w-[560px] z-10 hover:scale-105 transition-transform duration-300">
                                <div class="relative w-full flex items-center justify-center">
                                    <img src="{{ asset('assets/images/brush.svg') }}" alt="brush background"
                                        class="w-full drop-shadow-xl opacity-90">
                                    <span
                                        class="lan-can-brush-title absolute text-white font-bold text-[24px] md:text-[32px] uppercase tracking-wider"
                                        style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4)">
                                        {{ $section2Title }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Khối hiển thị sản phẩm bên phải -->
                    <div class="w-full lg:w-[55%] flex flex-col justify-between">
                        <div id="sec2-wrapper">
                            @php $chunks2 = $section2Products->chunk(4); @endphp
                            @foreach ($chunks2 as $index => $chunk)
                                <div
                                    class="sec2-page {{ $index === 0 ? 'grid' : 'hidden' }} grid-cols-2 gap-x-4 md:gap-x-8 lg:gap-x-16 gap-y-6 md:gap-y-10 lg:gap-y-12 mb-6 md:mb-10 fade-grid">
                                    @foreach ($chunk as $item)
                                        <x-client.shared.product-card
                                            href="{{ route('client.products.lan-can-gom-su.detail', $item->lan_can_gom_su_ct_id) }}"
                                            image="{{ $item->images ? asset('storage/' . $item->images[0]) : asset('assets/images/lan-can-02.jpg') }}"
                                            title="{{ $item->name }}"
                                            title-class="font-bold text-[#212121] text-[14px] lg:text-[15px] -mb-[5px] tracking-wide transition-colors group-hover:text-secondary"
                                            code="MSP: {{ $item->display_code }}" price="{{ $item->display_price }}"
                                            :show-overlay="true"
                                            product-type="lan_can_gom_su_ct"
                                            :product-id="$item->lan_can_gom_su_ct_id"
                                            :product="$item" />
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <!-- Phần Nút điều hướng cố định (Chỉ hiện khi có > 4 sản phẩm) -->
                        @if ($chunks2->count() > 1)
                            <div class="flex items-center justify-center gap-5 mt-0 md:mt-10 lg:mt-auto">
                                <button onclick="changePage('sec2', -1)"
                                    class="w-[40px] h-[40px] rounded-full border border-secondary flex items-center justify-center text-secondary hover:bg-secondary hover:text-white transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                </button>
                                <button onclick="changePage('sec2', 1)"
                                    class="w-[40px] h-[40px] rounded-full bg-secondary flex items-center justify-center text-white hover:opacity-90 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    @php
        // Lấy 3 sản phẩm không nằm trong 2 section trên (nếu có)
        $usedIds = collect($section1ProductIds)->merge($section2ProductIds)->filter()->unique()->toArray();
        $productListProducts = $usedIds
            ? $products->whereNotIn('lan_can_gom_su_ct_id', $usedIds)->take(3)
            : $products->skip(16)->take(3);
    @endphp

    @if ($productListProducts->isNotEmpty())
        <!-- Product List Section -->
        <section class="relative mx-auto pb-10 lg:pb-16 overflow-visible">
            <div class="relative w-[85%] max-w-[1320px] mx-auto z-10">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 lg:gap-x-8 gap-y-4 lg:gap-y-6" data-aos="fade-up">
                    <!-- Khối ảnh lớn đầu tiên (Chiếm 2 cột) -->
                    @php $firstProduct = $productListProducts->first(); @endphp
                    @if ($firstProduct)
                        <x-client.shared.product-card
                            href="{{ route('client.products.lan-can-gom-su.detail', $firstProduct->lan_can_gom_su_ct_id) }}"
                            class="col-span-2 lg:col-span-2"
                            image="{{ $firstProduct->images ? asset('storage/' . $firstProduct->images[0]) : asset('assets/images/lan-can-giot-le.jpg') }}"
                            title="{{ $firstProduct->name }}"
                            title-class="font-bold text-[#212121] text-[14px] lg:text-[15px] -mb-[5px] tracking-wide transition-colors group-hover:text-secondary"
                            code="MSP: {{ $firstProduct->display_code }}" price="{{ $firstProduct->display_price }}"
                            :show-overlay="true" aspect="aspect-[2/1] md:aspect-[19/10]"
                            product-type="lan_can_gom_su_ct"
                            :product-id="$firstProduct->lan_can_gom_su_ct_id"
                            :product="$firstProduct" />
                    @endif

                    <!-- Khối ảnh nhỏ tiếp theo -->
                    @foreach ($productListProducts->skip(1)->take(2) as $item)
                        <x-client.shared.product-card
                            href="{{ route('client.products.lan-can-gom-su.detail', $item->lan_can_gom_su_ct_id) }}"
                            image="{{ $item->images ? asset('storage/' . $item->images[0]) : asset('assets/images/lan-can-07.jpg') }}"
                            title="{{ $item->name }}"
                            title-class="font-bold text-[#212121] text-[14px] lg:text-[15px] -mb-[5px] tracking-wide transition-colors group-hover:text-secondary"
                            code="MSP: {{ $item->display_code }}" price="{{ $item->display_price }}"
                            :show-overlay="true" aspect="aspect-[1.1/1] md:aspect-[9/10]"
                            product-type="lan_can_gom_su_ct"
                            :product-id="$item->lan_can_gom_su_ct_id"
                            :product="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-client.shared.outstanding-value />

    <x-client.shared.journey-video :video="$config->video" />

    <x-client.shared.works />

    <!-- FAQ Section -->
    <section class="w-full relative pb-16 bg-background-secondary overflow-visible" data-aos="fade-up">
        <x-client.shared.faq-accordion />
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
        <script>
            // --- JS Điều hướng phân trang sản phẩm (Pagination) ---
            let currentPage = {
                sec1: 0,
                sec2: 0
            };

            function changePage(section, direction) {
                const pages = document.querySelectorAll(`.${section}-page`);
                if (pages.length <= 1) return;

                // Ẩn trang hiện tại
                pages[currentPage[section]].classList.remove('grid');
                pages[currentPage[section]].classList.add('hidden');

                // Tính toán trang tiếp theo
                currentPage[section] += direction;
                if (currentPage[section] < 0) {
                    currentPage[section] = pages.length - 1;
                } else if (currentPage[section] >= pages.length) {
                    currentPage[section] = 0;
                }

                // Hiển thị trang mới
                pages[currentPage[section]].classList.remove('hidden');
                pages[currentPage[section]].classList.add('grid');
            }

            // --- Khởi tạo Video Popup ---
            document.addEventListener("DOMContentLoaded", () => {
                if (typeof GLightbox !== "undefined") {
                    GLightbox({
                        touchNavigation: true,
                        loop: true,
                        autoplayVideos: true,
                    });
                }
            });
        </script>
    @endpush

</x-client.layouts.main>
