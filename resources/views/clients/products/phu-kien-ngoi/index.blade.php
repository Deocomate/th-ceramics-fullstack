<x-client.layouts.main title="Phụ Kiện Ngói" data-page="products"
    main-class="bg-background-secondary overflow-hidden page-phu-kien-ngoi" :hide-newsletter="true">

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
            @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap");
            @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Italianno&display=swap");

            @keyframes fadeInSlider {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in-slider {
                animation: fadeInSlider 0.4s ease-out forwards;
            }
        </style>
    @endpush

    @php
        $assetUrl = fn(?string $path, ?string $fallback = null) => \App\Support\AssetPath::url($path, $fallback);
        $productImageUrl = fn($product, string $fallback) => $assetUrl(data_get($product, 'images.0'), $fallback);
        $productId = fn($product) => data_get($product, 'phu_kien_ngoi_ct_id');
        $productType = fn($product) => data_get($product, 'category_type') === \App\Models\PhuKienNgoiCt::TYPE_CHU_VAN
            ? 'chu_van'
            : 'bo_noc';
        $productCode = fn($product) => $productType($product) === 'bo_noc'
            ? 'PKN-BN' . $productId($product)
            : 'PKN-CV' . $productId($product);
        $productUrl = fn($product) => $productType($product) === 'chu_van'
            ? route('client.products.phu-kien-ngoi.bo-noc-chu-van.detail', $productId($product))
            : route('client.products.phu-kien-ngoi.ngoi-bo-noc.detail', $productId($product));
    @endphp

    <x-client.shared.catalog-sticky-btn />

    <section class="relative w-full">
        <div
            class="relative w-full aspect-[4/3] md:aspect-[8/6] lg:aspect-auto h-full lg:[clip-path:inset(40px_0_0_0)] lg:-mt-[40px]">
            <img src="{{ $assetUrl($config->thumbnail_main, 'assets/images/pk-banner.png') }}" alt="Phụ Kiện Ngói"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 flex flex-col items-center pt-[5%] md:pt-[5%] lg:pt-[5%]" data-aos="fade-up"
                data-aos-delay="100">
                <div class="text-center text-white px-4 w-[85%] max-w-[1320px] mx-auto">
                    <h1
                        class="font-sans text-[26px] md:text-4xl lg:text-[44px] font-bold uppercase mb-2 md:mb-6 drop-shadow-md">
                        PHỤ KIỆN NGÓI
                    </h1>
                    <p
                        class="font-italianno text-xl md:text-[34px] lg:text-[48px] font-light leading-none tracking-wide drop-shadow-sm text-white/95">
                        {{ $config->banner_text_1 ?: 'Nâng niu nét chạm trổ, sắt son cùng thời gian' }}
                    </p>
                    <p
                        class="font-italianno text-xl md:text-[34px] lg:text-[48px] font-light leading-none tracking-wide drop-shadow-sm text-white/95">
                        {{ $config->banner_text_2 ?: 'Phụ kiện Thanh Hải: Điểm nhấn tâm linh, hoàn thiện dáng hình kiến trúc Việt' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <x-client.products.phu-kien-ngoi.category-showcase-bo-noc :ngoi-bo-noc-products="$ngoiBoNocProducts" :config="$config ?? null" />
    <x-client.products.phu-kien-ngoi.category-showcase-chu-van :bo-noc-chu-van-products="$boNocChuVanProducts" :config="$config ?? null" />

    @php
        $masonryProducts = $ngoiBoNocProducts->concat($boNocChuVanProducts)->take(6)->values();
        $masonryItems = [
            [
                'wrapper' => 'col-span-2 lg:col-start-2 lg:col-span-3',
                'card' => 'aspect-[2/1] lg:aspect-[2.8/1]',
                'delay' => '0',
            ],
            ['wrapper' => 'col-span-2 lg:col-span-2', 'card' => 'aspect-[2/1] md:aspect-[19/10]', 'delay' => '100'],
            ['wrapper' => '', 'card' => 'aspect-[1.1/1] md:aspect-[9/10]', 'delay' => '200'],
            ['wrapper' => '', 'card' => 'aspect-[1.1/1] md:aspect-[9/10]', 'delay' => '300'],
            ['wrapper' => '', 'card' => 'aspect-[1.1/1] md:aspect-[9/10]', 'delay' => '400'],
            ['wrapper' => '', 'card' => 'aspect-[1.1/1] md:aspect-[9/10]', 'delay' => '500'],
        ];
    @endphp

    @if ($masonryProducts->isNotEmpty())
        <section class="relative mx-auto pb-10 lg:pb-16 overflow-visible">
            <img src="{{ asset('assets/images/pk-decorate-left.svg') }}" alt=""
                class="absolute top-10 lg:-top-[5%] left-0 w-[60%] max-w-[400px] lg:max-w-[620px] opacity-70 z-0 pointer-events-none"
                data-aos="fade-right" data-aos-duration="1000">
            <img src="{{ asset('assets/images/pk-decorate-right.svg') }}" alt=""
                class="absolute bottom-[-5%] lg:bottom-[-18%] right-0 w-[70%] max-w-[500px] lg:max-w-[780px] opacity-70 z-0 pointer-events-none"
                data-aos="fade-left" data-aos-duration="1000">

            <div class="relative w-[85%] max-w-[1320px] mx-auto z-10">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 lg:gap-x-8 gap-y-4 lg:gap-y-6" data-aos="fade-up">
                    @foreach ($masonryProducts as $product)
                        @php
                            $item = $masonryItems[$loop->index] ?? $masonryItems[5];
                            $id = $productId($product);
                            $type = $productType($product);
                        @endphp
                        <x-client.shared.product-card href="{{ $productUrl($product) }}" class="{{ $item['wrapper'] }}"
                            image="{{ $productImageUrl($product, 'assets/images/pk-01.jpg') }}"
                            title="{{ $product->name }}"
                            title-class="font-bold text-[#212121] text-[14px] lg:text-[15px] -mb-[5px] tracking-wide transition-colors group-hover:text-secondary"
                            code="MSP: {{ $product->display_code }}" price="{{ $product->display_price }}"
                            :show-overlay="true" aspect="{{ $item['card'] }} bg-white shadow mb-4 lg:mb-6"
                            data-aos="fade-up" data-aos-delay="{{ $item['delay'] }}"
                            product-type="phu_kien_ngoi_ct"
                            :product-id="$product->phu_kien_ngoi_ct_id"
                            :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="relative w-full pb-10 lg:pb-16 overflow-hidden">
        <div class="relative z-10 w-[85%] max-w-[1320px] mx-auto mt-1">
            <div class="text-center mb-8 lg:mb-16" data-aos="fade-up">
                <h2 class="text-[20px] lg:text-3xl font-semibold text-[#C47526] uppercase">
                    Dấu Ấn Trên Những Công Trình
                </h2>
            </div>

            @php
                $galleries = is_array($config->images) ? $config->images : [];
                $galleryFallbacks = [
                    'assets/images/pk-07.jpg',
                    'assets/images/pk-08.jpg',
                    'assets/images/pk-03.jpg',
                    'assets/images/gach-hoa-02.png',
                    'assets/images/dao-kim.png',
                    'assets/images/pk-04.jpg',
                    'assets/images/pk-01.jpg',
                    'assets/images/pk-02.jpg',
                    'assets/images/pk-05.jpg',
                    'assets/images/pk-06.jpg',
                    'assets/images/lan-can-01.jpg',
                    'assets/images/lan-can-02.jpg',
                ];
                $allImages = count($galleries) > 0 ? $galleries : $galleryFallbacks;
                $chunks = collect($allImages)->chunk(7);

                // Pad the last chunk if it has < 7 items and we have multiple pages
                if ($chunks->count() > 1 && $chunks->last()->count() < 7) {
                    $lastChunk = $chunks->last();
                    $needed = 7 - $lastChunk->count();
                    for ($i = 0; $i < $needed; $i++) {
                        $lastChunk->push($galleryFallbacks[$i % count($galleryFallbacks)]);
                    }
                }
            @endphp

            <div class="relative w-full" data-gallery-slider>
                <div class="slider-pages-container">
                    @foreach ($chunks as $chunkIndex => $chunk)
                        @php
                            $chunkValues = $chunk->values();
                            $getImageUrl = fn(int $idx) => $assetUrl($chunkValues[$idx] ?? null, $galleryFallbacks[$idx]);
                        @endphp
                        <div class="flex flex-col gap-2 md:gap-4 {{ $chunkIndex === 0 ? '' : 'hidden' }}" data-slider-page="{{ $chunkIndex }}">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4 items-end">
                                <div class="col-span-1" data-aos="fade-up" data-aos-delay="0">
                                    <a href="{{ $getImageUrl(0) }}"
                                        class="glightbox w-full aspect-[1.1/1] shadow overflow-hidden relative group block">
                                        <img src="{{ $getImageUrl(0) }}" alt=""
                                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </a>
                                </div>
                                <div class="contents md:flex md:flex-col md:gap-4">
                                    <div class="w-full aspect-[19/10] bg-[#BD724F] shadow" data-aos="fade-up" data-aos-delay="100">
                                    </div>
                                    <a href="{{ $getImageUrl(1) }}"
                                        class="glightbox w-full aspect-[8/10] shadow overflow-hidden relative group block"
                                        data-aos="fade-up" data-aos-delay="200">
                                        <img src="{{ $getImageUrl(1) }}" alt=""
                                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </a>
                                </div>
                                <div class="col-span-2 hidden md:block" data-aos="fade-up" data-aos-delay="200">
                                    <a href="{{ $getImageUrl(2) }}"
                                        class="glightbox w-full aspect-[11/5] shadow overflow-hidden relative group block">
                                        <img src="{{ $getImageUrl(2) }}" alt=""
                                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </a>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:hidden gap-2 md:gap-4" data-aos="fade-up" data-aos-delay="200">
                                <div class="col-span-1">
                                    <a href="{{ $getImageUrl(2) }}"
                                        class="glightbox w-full aspect-[22/10] shadow overflow-hidden relative group block">
                                        <img src="{{ $getImageUrl(2) }}" alt=""
                                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </a>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4 items-start">
                                <div class="col-span-1 row-span-2 md:row-span-1 h-full" data-aos="fade-up" data-aos-delay="0">
                                    <a href="{{ $getImageUrl(3) }}"
                                        class="glightbox w-full md:aspect-[8/10] shadow overflow-hidden relative group block">
                                        <img src="{{ $getImageUrl(3) }}" alt=""
                                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </a>
                                </div>
                                <div class="contents md:flex md:flex-col md:gap-4">
                                    <a href="{{ $getImageUrl(4) }}"
                                        class="glightbox w-full aspect-[8/10] shadow overflow-hidden relative group block"
                                        data-aos="fade-up" data-aos-delay="100">
                                        <img src="{{ $getImageUrl(4) }}" alt=""
                                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </a>
                                    <div class="w-full aspect-[19/10] bg-[#BD724F] shadow" data-aos="fade-up" data-aos-delay="200">
                                    </div>
                                </div>
                                <div class="col-span-1" data-aos="fade-up" data-aos-delay="200">
                                    <a href="{{ $getImageUrl(5) }}"
                                        class="glightbox w-full aspect-[8/10] shadow overflow-hidden relative group block">
                                        <img src="{{ $getImageUrl(5) }}" alt=""
                                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </a>
                                </div>
                                <div class="col-span-1" data-aos="fade-up" data-aos-delay="300">
                                    <a href="{{ $getImageUrl(6) }}"
                                        class="glightbox w-full aspect-[8/10] shadow overflow-hidden relative group block">
                                        <img src="{{ $getImageUrl(6) }}" alt=""
                                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($chunks->count() > 1)
                    <!-- Navigation Buttons -->
                    <button data-btn-prev class="absolute top-0 left-0 z-20 w-10 h-10 md:w-12 md:h-12 rounded-full border border-secondary bg-transparent text-secondary flex items-center justify-center hover:bg-secondary hover:text-white transition-all duration-300 shadow-md disabled:opacity-30 disabled:cursor-not-allowed" type="button" aria-label="Slide trước">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button data-btn-next class="absolute top-0 right-0 z-20 w-10 h-10 md:w-12 md:h-12 rounded-full border border-secondary bg-transparent text-secondary flex items-center justify-center hover:bg-secondary hover:text-white transition-all duration-300 shadow-md disabled:opacity-30 disabled:cursor-not-allowed" type="button" aria-label="Slide tiếp theo">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                @endif
            </div>

            <div class="mt-6 md:mt-8 flex justify-end items-center" data-aos="fade-up">
                <a href="{{ route('client.projects.index') }}"
                    class="font-archivo text-sm md:text-base font-bold uppercase tracking-[0.08em] text-secondary transition-opacity duration-300 hover:opacity-70 inline-flex items-center gap-2">
                    Xem thêm
                    <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.707 2.29292C10.5184 2.11076 10.2658 2.00997 10.0036 2.01224C9.7414 2.01452 9.49059 2.11969 9.30518 2.3051C9.11977 2.49051 9.0146 2.74132 9.01233 3.00352C9.01005 3.26571 9.11084 3.51832 9.293 3.70692L12.586 6.99992H1C0.734784 6.99992 0.48043 7.10528 0.292893 7.29281C0.105357 7.48035 0 7.7347 0 7.99992C0 8.26514 0.105357 8.51949 0.292893 8.70703C0.48043 8.89456 0.734784 8.99992 1 8.99992H12.586L9.293 12.2929C9.19749 12.3852 9.12131 12.4953 9.0689 12.6175C9.01649 12.7395 8.9889 12.8707 8.98775 13.0035C8.9866 13.1363 9.0119 13.268 9.06218 13.3909C9.11246 13.5138 9.18671 13.6254 9.28061 13.7193C9.3745 13.8132 9.48615 13.8875 9.60905 13.9377C9.73194 13.988 9.86362 14.0133 9.9964 14.0122C10.1292 14.011 10.2604 13.9834 10.3824 13.931C10.5044 13.8786 10.6148 13.8024 10.707 13.7069L15.707 8.70692C15.8945 8.51939 15.9998 8.26508 15.9998 7.99992C15.9998 7.73475 15.8945 7.48045 15.707 7.29292L10.707 2.29292Z"
                            fill="currentColor"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <x-client.shared.outstanding-value />

    <section class="w-full relative pb-[70px] md:pb-32 bg-background-secondary overflow-visible" data-aos="fade-up">
        <div class="absolute z-[2] lg:-top-[23%] lg:left-[11.5%] -left-[10%] w-[120%] lg:w-[77%] pointer-events-none"
            data-aos="fade-up-right">
            <img src="{{ asset('assets/images/pk-decorate.svg') }}" alt=""
                class="w-full origin-center md:opacity-80 opacity-60 drop-shadow-sm">
        </div>
        <x-client.shared.faq-accordion />
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Logic cho Gallery Slider (Cả cụm)
                const gallerySlider = document.querySelector('[data-gallery-slider]');
                if (gallerySlider) {
                    const pages = gallerySlider.querySelectorAll('[data-slider-page]');
                    const btnPrev = gallerySlider.querySelector('[data-btn-prev]');
                    const btnNext = gallerySlider.querySelector('[data-btn-next]');

                    if (pages.length > 1 && btnPrev && btnNext) {
                        let currentPage = 0;
                        const totalPages = pages.length;

                        const updateSlider = () => {
                            pages.forEach((page, index) => {
                                if (index === currentPage) {
                                    page.classList.remove('hidden');
                                    page.classList.add('flex', 'animate-fade-in-slider');
                                } else {
                                    page.classList.add('hidden');
                                    page.classList.remove('flex', 'animate-fade-in-slider');
                                }
                            });

                            btnPrev.disabled = (currentPage === 0);
                            btnNext.disabled = (currentPage === totalPages - 1);
                        };

                        btnPrev.addEventListener('click', () => {
                            if (currentPage > 0) {
                                currentPage--;
                                updateSlider();
                            }
                        });

                        btnNext.addEventListener('click', () => {
                            if (currentPage < totalPages - 1) {
                                currentPage++;
                                updateSlider();
                            }
                        });

                        updateSlider();
                    }
                }

                // Logic cho Desktop Product Sliders
                const desktopSliders = document.querySelectorAll('[data-desktop-slider]');

                desktopSliders.forEach(slider => {
                    const pages = slider.querySelectorAll('[data-slider-page]');
                    const btnPrev = slider.querySelector('[data-btn-prev]');
                    const btnNext = slider.querySelector('[data-btn-next]');

                    // Bỏ qua nếu không có đủ số lượng trang hoặc không có nút
                    if (pages.length <= 1 || !btnPrev || !btnNext) return;

                    let currentPage = 0;
                    const totalPages = pages.length;

                    const updateSlider = () => {
                        pages.forEach((page, index) => {
                            if (index === currentPage) {
                                page.classList.remove('hidden');
                                page.classList.add('grid', 'animate-fade-in-slider');
                            } else {
                                page.classList.add('hidden');
                                page.classList.remove('grid', 'animate-fade-in-slider');
                            }
                        });

                        // Xử lý trạng thái Nút bấm (Disabled/Enabled)
                        btnPrev.disabled = (currentPage === 0);
                        btnNext.disabled = (currentPage === totalPages - 1);
                    };

                    btnPrev.addEventListener('click', () => {
                        if (currentPage > 0) {
                            currentPage--;
                            updateSlider();
                        }
                    });

                    btnNext.addEventListener('click', () => {
                        if (currentPage < totalPages - 1) {
                            currentPage++;
                            updateSlider();
                        }
                    });

                    // Initialize state
                    updateSlider();
                });

                if (typeof GLightbox !== "undefined") {
                    document.querySelectorAll(".glightbox").forEach((anchor) => {
                        const image = anchor.querySelector("img");
                        if (image) {
                            anchor.setAttribute("href", image.currentSrc || image.src);
                        }
                    });

                    GLightbox({
                        touchNavigation: true,
                        loop: true,
                    });
                }
            });
        </script>
    @endpush

</x-client.layouts.main>
