<x-client.layouts.main title="Gạch Trang Trí" data-page="products" main-class="bg-background-secondary" :hide-newsletter="true">

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
            @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap");
            @import url("https://fonts.googleapis.com/css2?family=Lavishly+Yours&display=swap");
            @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Italianno&display=swap");

            :root {
                --slider-main-w: 850px;
                --slider-main-h: 781px;
                --slider-side-w: 112px;
                --slider-side-h: 781px;
                /* Side slides height equals main slide height */
                --slider-offset-1: 513px;
                --slider-offset-2: 657px;
                --slider-radius: 80px;
                --slider-motion-duration: 1.15s;
                --slider-motion-ease: cubic-bezier(0.33, 1, 0.68, 1);
                --slider-content-duration: 0.95s;
            }

            @media (min-width: 768px) and (max-width: 1279px) {
                :root {
                    --slider-main-w: 520px;
                    --slider-main-h: 480px;
                    --slider-side-w: 80px;
                    --slider-side-h: 480px;
                    /* Side slides height equals main slide height */
                    --slider-offset-1: 320px;
                    --slider-offset-2: 420px;
                    --slider-radius: 40px;
                }
            }

            @media (max-width: 767px) {
                :root {
                    --slider-main-w: min(82vw, 320px);
                    --slider-main-h: 380px;
                    --slider-side-w: min(82vw, 320px);
                    --slider-side-h: 380px;
                    /* Side slides height equals main slide height */
                    --slider-offset-1: calc(min(82vw, 320px) + 12px);
                    --slider-offset-2: calc(2 * min(82vw, 320px) + 24px);
                    --slider-radius: 30px;
                }
            }

            #custom-project-slider {
                touch-action: pan-y;
            }

            .custom-project-slide {
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                border-radius: var(--slider-radius);
                overflow: hidden;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
                will-change: width, height, transform, opacity;
                opacity: 0;
                pointer-events: none;
                transition: none;
            }

            .custom-project-slide.slide-center,
            .custom-project-slide.slide-left-1,
            .custom-project-slide.slide-left-2,
            .custom-project-slide.slide-right-1,
            .custom-project-slide.slide-right-2 {
                transition: width var(--slider-motion-duration) var(--slider-motion-ease),
                    height var(--slider-motion-duration) var(--slider-motion-ease),
                    transform var(--slider-motion-duration) var(--slider-motion-ease),
                    opacity var(--slider-motion-duration) var(--slider-motion-ease),
                    border-radius var(--slider-motion-duration) var(--slider-motion-ease),
                    filter 0.55s ease;
                pointer-events: auto;
                opacity: 1;
            }

            .custom-project-slide.slide-left-1,
            .custom-project-slide.slide-left-2,
            .custom-project-slide.slide-right-1,
            .custom-project-slide.slide-right-2 {
                cursor: pointer;
            }

            .custom-project-slide.slide-left-1:hover,
            .custom-project-slide.slide-left-2:hover,
            .custom-project-slide.slide-right-1:hover,
            .custom-project-slide.slide-right-2:hover {
                filter: brightness(1.05);
            }

            .custom-project-slide.slide-center {
                width: var(--slider-main-w);
                height: var(--slider-main-h);
                transform: translate(-50%, -50%);
                z-index: 10;
            }

            .custom-project-slide.slide-left-1 {
                width: var(--slider-side-w);
                height: var(--slider-side-h);
                transform: translate(calc(-50% - var(--slider-offset-1)), -50%);
                z-index: 5;
            }

            .custom-project-slide.slide-left-2 {
                width: var(--slider-side-w);
                height: var(--slider-side-h);
                transform: translate(calc(-50% - var(--slider-offset-2)), -50%);
                z-index: 4;
            }

            .custom-project-slide.slide-right-1 {
                width: var(--slider-side-w);
                height: var(--slider-side-h);
                transform: translate(calc(-50% + var(--slider-offset-1)), -50%);
                z-index: 5;
            }

            .custom-project-slide.slide-right-2 {
                width: var(--slider-side-w);
                height: var(--slider-side-h);
                transform: translate(calc(-50% + var(--slider-offset-2)), -50%);
                z-index: 4;
            }

            .custom-project-slide.slide-outer-left {
                width: var(--slider-side-w);
                height: var(--slider-side-h);
                transform: translate(calc(-50% - var(--slider-offset-2) - 150px), -50%);
                z-index: 1;
                opacity: 0;
            }

            .custom-project-slide.slide-outer-right {
                width: var(--slider-side-w);
                height: var(--slider-side-h);
                transform: translate(calc(-50% + var(--slider-offset-2) + 150px), -50%);
                z-index: 1;
                opacity: 0;
            }

            .custom-project-slide.slide-outer-left,
            .custom-project-slide.slide-outer-right {
                transition: transform var(--slider-motion-duration) var(--slider-motion-ease),
                    opacity calc(var(--slider-motion-duration) * 0.85) var(--slider-motion-ease);
            }

            .custom-project-slide .slide-content {
                opacity: 0;
                transform: translateY(20px);
                pointer-events: none;
                transition: opacity var(--slider-content-duration) var(--slider-motion-ease),
                    transform var(--slider-content-duration) var(--slider-motion-ease);
                transition-delay: 0s;
                z-index: 10;
            }

            .custom-project-slide.slide-center .slide-content {
                opacity: 1;
                transform: translateY(0);
                pointer-events: auto;
                transition-delay: 0.2s;
            }

            .project-dot {
                background-color: rgba(199, 110, 0, 0.3);
                transition: width 0.5s var(--slider-motion-ease), background-color 0.5s var(--slider-motion-ease);
            }

            .project-dot.active {
                background-color: #c76e00;
                width: 16px;
            }
        </style>
    @endpush

    @php
        $mediaUrl = function (?string $path, string $fallback = '') {
            if (empty($path)) {
                return $fallback;
            }

            if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            if (\Illuminate\Support\Str::startsWith($path, 'assets/')) {
                return asset($path);
            }

            return asset('storage/' . $path);
        };

        $applicationSlots = [
            'main' => 'Tường trang trí',
            'sub_1' => 'Lát nền',
            'sub_2' => 'Phòng khách',
            'sub_3' => 'Ngoài trời',
            'sub_4' => 'Phòng tắm',
        ];
        $applications = $config && is_array($config->ung_dung_da_dang) ? $config->ung_dung_da_dang : [];
        $hasApplications = collect($applicationSlots)
            ->keys()
            ->contains(function ($slot) use ($applications) {
                $item = is_array($applications[$slot] ?? null) ? $applications[$slot] : [];

                return !empty($item['title']) || !empty($item['image']);
            });
    @endphp

    <x-client.shared.catalog-sticky-btn />

    <!-- Top Banner -->
    <section
        class="relative w-full min-h-[322px] md:min-h-[500px] lg:min-h-[600px] flex items-center md:pb-8 overflow-hidden">
        <!-- Background Image with Dark Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="{{ $config && $config->thumbnail_main ? asset('storage/' . $config->thumbnail_main) : asset('assets/images/gach-trang-tri-banner.png') }}"
                alt="Gạch Trang Trí Banner" class="w-full h-full object-cover object-center" />
            <!-- Slight dark overlay to make text readable -->
            <div class="absolute inset-0 bg-black/30"></div>
        </div>

        <!-- Content Container -->
        <div class="relative z-10 xl:w-[50%] w-[85%] max-w-[1320px] mx-auto text-white">
            <div data-aos="fade-up" data-aos-duration="1000">
                <h1
                    class="text-6xl md:text-8xl lg:text-[130px] leading-none mb-6 md:mb-8 drop-shadow-md font-lavishly font-normal">
                    Gạch trang trí
                </h1>
                <p
                    class="text-[14px] leading-[22.75px] md:text-base lg:text-lg max-w-lg mb-6 drop-shadow md:leading-relaxed font-archivo text-white">
                    Cùng bạn viết nên tuyệt tác<br />
                    trên những mảng tường.
                </p>
                <a href="#"
                    class="inline-flex items-center justify-center w-[116px] h-[36px] border-2 border-white text-white font-archivo font-bold text-[12px] uppercase hover:bg-white hover:text-black transition-colors duration-300">TÌM
                    HIỂU THÊM</a>
            </div>
        </div>
    </section>

    @if ($hasApplications)
        <!-- Ứng dụng đa dạng Section -->
        <section class="max-w-[1320px] w-[85%] mx-auto pb-4 pt-8 md:py-12" data-aos="fade-up">
            <h2
                class="font-archivo text-[20px] sm:text-[24px] md:text-[32px] font-semibold uppercase text-secondary leading-[36px] sm:leading-[44px] md:leading-[80px] mb-5 sm:mb-6 md:mb-10 text-left">
                Ứng dụng đa dạng
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 sm:gap-6 md:gap-8 lg:gap-16">
                @php
                    $mainItem = is_array($applications['main'] ?? null) ? $applications['main'] : [];
                @endphp
                <div class="flex flex-col">
                    <div class="w-full aspect-[23/25] bg-[#E2E2E2] shadow-lg overflow-hidden">
                        @if (!empty($mainItem['image']))
                            <img src="{{ $mediaUrl($mainItem['image']) }}"
                                alt="{{ $mainItem['title'] ?? $applicationSlots['main'] }}"
                                class="w-full h-full object-cover">
                        @endif
                    </div>
                    <p
                        class="mt-0 sm:mt-[11px] flex h-[44px] sm:h-[50px] md:h-[55px] w-full md:w-[286px] flex-col justify-center text-left text-[14px] leading-[20px] sm:text-base md:text-lg lg:text-xl font-archivo font-semibold uppercase text-primary">
                        {{ $mainItem['title'] ?? $applicationSlots['main'] }}
                    </p>
                </div>

                <div class="flex flex-col justify-between gap-0 sm:gap-5 h-full">
                    @foreach ([['sub_1', 'sub_2'], ['sub_3', 'sub_4']] as $row)
                        <div class="grid grid-cols-2 gap-[10px] sm:gap-4 md:gap-8 lg:gap-16">
                            @foreach ($row as $slot)
                                @php
                                    $item = is_array($applications[$slot] ?? null) ? $applications[$slot] : [];
                                @endphp
                                <div class="flex flex-col">
                                    <div
                                        class="w-full aspect-[179/192] sm:aspect-square bg-[#E2E2E2] shadow-lg overflow-hidden">
                                        @if (!empty($item['image']))
                                            <img src="{{ $mediaUrl($item['image']) }}"
                                                alt="{{ $item['title'] ?? $applicationSlots[$slot] }}"
                                                class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <p
                                        class="mt-0 sm:mt-[11px] mx-auto flex h-[44px] sm:h-[50px] md:h-[55px] w-full md:w-[286px] flex-col justify-center text-center text-[14px] leading-[20px] sm:text-base md:text-lg lg:text-xl font-archivo font-semibold uppercase text-primary">
                                        {{ $item['title'] ?? $applicationSlots[$slot] }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- BREADCRUMB & PRODUCT FILTER -->
    <x-client.shared.product-breadcrumb-filter current-label="Gạch Trang Trí" />
    <x-client.shared.product-grid category="gach-trang-tri" :products="$products"
        routeName="client.products.gach-trang-tri.detail" />
    <x-client.shared.custom-design-process :images="$config && is_array($config->images) ? $config->images : []" />

    <!-- Công đoạn chế tác -->
    <section class="relative w-full overflow-hidden py-12 md:py-20" data-aos="fade-up">
        <!-- Họa tiết trang trí background -->
        <div
            class="absolute right-0 top-1/3 -translate-y-1/2 w-[100px] md:w-[200px] lg:w-[300px] pointer-events-none z-0 opacity-90">
            <img src="{{ asset('assets/images/gtt-decorate.svg') }}" alt="Decor Background" class="w-full h-auto" />
        </div>

        <!-- Content Container -->
        <div class="max-w-[1320px] w-[85%] mx-auto relative z-10">
            <h2
                class="font-archivo text-[20px] md:text-[32px] font-semibold uppercase text-secondary leading-[36px] md:leading-[45px] mb-8 md:mb-12 text-left">
                Công đoạn chế tác
            </h2>
            <div class="flex justify-center items-center w-full">
                <img src="{{ asset('assets/images/gtt-process.svg') }}" alt="Công đoạn chế tác"
                    class="w-full md:max-w-[95%] h-auto relative z-10 drop-shadow-sm scale-[1.1]" />
            </div>
        </div>
    </section>

    <x-client.shared.journey-video :hide-title="true" />

    <!-- Nâng tầm giá trị nghệ thuật Section -->
    <section class="max-w-[1320px] w-[85%] mx-auto md:pb-10" data-aos="fade-up">
        <h2
            class="font-archivo text-[20px] md:text-[32px] font-semibold uppercase text-secondary leading-[36px] md:leading-[45px] mb-8 md:mb-12 text-left">
            Nâng tầm giá trị nghệ thuật
        </h2>

        <div class="swiper art-value-swiper -mx-[7.5vw] px-[7.5vw] md:mx-0 md:px-0 md:overflow-visible pb-12 md:pb-0">
            <div class="swiper-wrapper md:!grid md:grid-cols-3 md:gap-8 lg:gap-14 md:items-start">
                <!-- Card 1 -->
                <div
                    class="swiper-slide h-auto md:!w-auto bg-white px-8 md:px-12 pt-8 md:pt-12 pb-8 md:pb-12 shadow-lg flex flex-col items-center text-center transition-transform hover:-translate-y-2 duration-300 group">
                    <img src="{{ asset('assets/images/gtt-hand.svg') }}" alt="Chế tác thủ công"
                        class="w-16 md:w-20 mb-6 object-contain transition-transform group-hover:scale-110 duration-300" />
                    <h3 class="text-[18px] leading-[28px] md:text-xl font-bold text-primary mb-4">Chế tác thủ công</h3>
                    <p
                        class="font-archivo text-[15px] font-light leading-[25px] tracking-[0.3px] text-black text-justify">
                        Mỗi viên gạch đều được tạo hình, tráng men và chăm chút tỉ mỉ bằng tay. Quy trình thủ công thuần
                        túy tạo nên
                        chiều sâu và cá tính riêng biệt. Chúng tôi trân trọng vẻ đẹp từ những khiếm khuyết tự nhiên.
                    </p>
                </div>

                <!-- Card 2 -->
                <div
                    class="swiper-slide h-auto md:!w-auto bg-white pt-8 md:pt-12 px-8 md:px-12 pb-8 md:pb-12 shadow-lg flex flex-col items-center text-center transition-transform hover:-translate-y-2 duration-300 group">
                    <img src="{{ asset('assets/images/gtt-fire.svg') }}" alt="Tinh hoa hỏa biến"
                        class="w-16 md:w-20 mb-6 object-contain transition-transform group-hover:scale-110 duration-300" />
                    <h3 class="text-[18px] leading-[28px] md:text-xl font-bold text-primary mb-4">Tinh hoa hỏa biến</h3>
                    <p
                        class="font-archivo text-[15px] font-light leading-[25px] tracking-[0.3px] text-black text-justify">
                        Sử dụng nguồn đất tuyển chọn kỹ lưỡng, gạch được nung ở nhiệt độ cao lên đến 1300°C. Quy trình
                        này giúp xương
                        gạch đạt độ cứng vượt trội, chống thấm và chịu được những điều kiện thời tiết khắc nghiệt nhất.
                        Ngoài ra,
                        nhiệt độ cao và không đồng đều trong lò nung cũng tạo ra sự biến chuyển cho màu sắc độc đáo.
                    </p>
                </div>

                <!-- Card 3 -->
                <div
                    class="swiper-slide h-auto md:!w-auto bg-white px-8 md:px-12 pt-8 md:pt-12 pb-8 md:pb-12 shadow-lg flex flex-col items-center text-center transition-transform hover:-translate-y-2 duration-300 group">
                    <img src="{{ asset('assets/images/gtt-brush.svg') }}" alt="Tùy biến độc bản"
                        class="w-16 md:w-20 mb-6 object-contain transition-transform group-hover:scale-110 duration-300" />
                    <h3 class="text-[18px] leading-[28px] md:text-xl font-bold text-primary mb-4">Tùy biến độc bản</h3>
                    <p
                        class="font-archivo text-[15px] font-light leading-[25px] tracking-[0.3px] text-black text-justify">
                        Chúng tôi kiến tạo những mảng gạch tùy chỉnh theo mong muốn và yêu cầu của bạn, phá vỡ mọi giới
                        hạn nghệ
                        thuật. Với khả năng tùy biến không giới hạn về màu sắc và hình khối, mỗi không gian sẽ trở thành
                        một tác phẩm
                        thị giác sống động, đậm chất cá nhân.
                    </p>
                </div>
            </div>
            <div class="swiper-pagination art-value-pagination md:!hidden !bottom-0"></div>
        </div>
    </section>

    <!-- Dấu ấn trên những công trình Section -->
    <section class="w-full overflow-hidden mt-12 pb-12" data-aos="fade-up">
        <!-- Header/Title -->
        <div class="max-w-[1320px] w-[85%] mx-auto mb-[21px] md:mb-12">
            <h2
                class="font-archivo text-[20px] md:text-[32px] font-semibold uppercase text-secondary leading-[36px] md:leading-tight text-left">
                Dấu ấn trên những công trình
            </h2>
        </div>

        <!-- Custom Slider Container -->
        <div class="relative w-full h-[380px] md:h-[500px] lg:h-[800px] overflow-hidden select-none"
            id="custom-project-slider">
            <div class="slider-inner absolute inset-0 flex items-center justify-center">
                @if (isset($projects) && $projects->count() > 0)
                    @foreach ($projects as $index => $project)
                        @php
                            $projectImages = is_array($project->images) ? $project->images : [];
                            $projectImage = $projectImages[0] ?? null;
                        @endphp
                        <div class="custom-project-slide cursor-pointer" data-index="{{ $index }}">
                            <img src="{{ $mediaUrl($projectImage, asset('assets/images/trang-tri-slide-01.jpg')) }}"
                                alt="{{ $project->ten_du_an ?? 'Công trình' }}"
                                class="w-full h-full object-cover transition-transform duration-1000 ease-out hover:scale-[1.03]" />
                            <div class="absolute inset-0 pointer-events-none z-[2]"
                                style="background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(0, 0, 0, 0.80) 100%)">
                            </div>
                            <div
                                class="slide-content absolute bottom-[25px] left-[20px] md:bottom-12 md:left-12 lg:bottom-16 lg:left-16 text-white z-10 max-w-[85%]">
                                <h3
                                    class="font-archivo font-bold text-[16px] md:text-lg lg:text-[20px] uppercase tracking-wider mb-1 lg:mb-2 leading-[20px] md:leading-tight">
                                    {{ $project->ten_du_an ?? '' }}
                                </h3>
                                <div
                                    class="font-archivo text-[12px] md:text-sm lg:text-[16px] leading-[16px] md:leading-relaxed">
                                    <p class="mt-[4px] md:mt-0"><span class="font-bold">Địa điểm:</span><span
                                            class="font-normal"> {{ $project->dia_diem ?? '' }}</span></p>
                                    <p class="mt-[2px] md:mt-0"><span class="font-bold">Sản phẩm:</span><span
                                            class="font-normal"> {{ $project->san_pham ?? '' }}</span></p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Fallback static slides when no project data --}}
                    @php
                        $fallbackProjects = [
                            [
                                'ten_du_an' => 'CHÙA BÁI ĐÍNH',
                                'dia_diem' => 'Ninh Bình',
                                'san_pham' => 'Ngói âm dương nâu đen',
                                'image' => asset('assets/images/trang-tri-slide-01.jpg'),
                            ],
                            [
                                'ten_du_an' => 'CHÙA BÁI ĐÍNH 2',
                                'dia_diem' => 'Ninh Bình',
                                'san_pham' => 'Ngói âm dương nâu đen',
                                'image' => asset('assets/images/trang-tri-slide-01.jpg'),
                            ],
                            [
                                'ten_du_an' => 'CHÙA BÁI ĐÍNH 3',
                                'dia_diem' => 'Ninh Bình',
                                'san_pham' => 'Ngói âm dương nâu đen',
                                'image' => asset('assets/images/trang-tri-slide-01.jpg'),
                            ],
                            [
                                'ten_du_an' => 'CHÙA BÁI ĐÍNH 4',
                                'dia_diem' => 'Ninh Bình',
                                'san_pham' => 'Ngói âm dương nâu đen',
                                'image' => asset('assets/images/trang-tri-slide-01.jpg'),
                            ],
                            [
                                'ten_du_an' => 'CHÙA BÁI ĐÍNH 5',
                                'dia_diem' => 'Ninh Bình',
                                'san_pham' => 'Ngói âm dương nâu đen',
                                'image' => asset('assets/images/trang-tri-slide-01.jpg'),
                            ],
                        ];
                    @endphp
                    @foreach ($fallbackProjects as $index => $project)
                        <div class="custom-project-slide cursor-pointer" data-index="{{ $index }}">
                            <img src="{{ $project['image'] }}" alt="{{ $project['ten_du_an'] }}"
                                class="w-full h-full object-cover transition-transform duration-1000 ease-out hover:scale-[1.03]" />
                            <div class="absolute inset-0 pointer-events-none z-[2]"
                                style="background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(0, 0, 0, 0.80) 100%)">
                            </div>
                            <div
                                class="slide-content absolute bottom-[25px] left-[20px] md:bottom-12 md:left-12 lg:bottom-16 lg:left-16 text-white z-10 max-w-[85%]">
                                <h3
                                    class="font-archivo font-bold text-[16px] md:text-lg lg:text-[20px] uppercase tracking-wider mb-1 lg:mb-2 leading-[20px] md:leading-tight">
                                    {{ $project['ten_du_an'] }}
                                </h3>
                                <div
                                    class="font-archivo text-[12px] md:text-sm lg:text-[16px] leading-[16px] md:leading-relaxed">
                                    <p class="mt-[4px] md:mt-0"><span class="font-bold">Địa điểm:</span><span
                                            class="font-normal"> {{ $project['dia_diem'] }}</span></p>
                                    <p class="mt-[2px] md:mt-0"><span class="font-bold">Sản phẩm:</span><span
                                            class="font-normal"> {{ $project['san_pham'] }}</span></p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Custom Pagination Dots -->
        <div class="flex justify-center gap-[7px] md:gap-3 mt-6 md:hidden" id="custom-project-dots">
            <!-- Will be populated dynamically via JavaScript -->
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="w-full relative pt-[40px] pb-20 md:pb-[120px] bg-background-secondary overflow-hidden"
        data-aos="fade-up">
        <img src="{{ asset('assets/images/gtt-decorate-left.svg') }}"
            class="absolute top-[38%] -translate-y-1/3 md:left-[-5rem] -left-1/2 w-[42%] object-contain opacity-50 pointer-events-none z-0"
            alt="" />
        <img src="{{ asset('assets/images/gtt-decorate-right.svg') }}"
            class="absolute top-[38%] -translate-y-1/3 md:right-[-5rem] -right-1/2 w-[42%] object-contain opacity-50 pointer-events-none z-0"
            alt="" />
        <x-client.shared.faq-accordion />
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // --- Init GLightbox ---
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
                        autoplayVideos: true,
                    });
                }

                // --- Init Custom Project Showcase Slider ---
                var customSliderContainer = document.getElementById("custom-project-slider");
                if (customSliderContainer) {
                    var wrapper = customSliderContainer.querySelector(".slider-inner");
                    var originalSlides = Array.from(customSliderContainer.querySelectorAll(".custom-project-slide"));
                    var slides = [...originalSlides];

                    // We need at least 7 slides for a smooth circular loop transition
                    var minSlidesRequired = 7;
                    if (originalSlides.length > 0 && originalSlides.length < minSlidesRequired) {
                        var cloneIndex = 0;
                        while (slides.length < minSlidesRequired) {
                            var originalSlide = originalSlides[cloneIndex % originalSlides.length];
                            var clone = originalSlide.cloneNode(true);
                            wrapper.appendChild(clone);
                            slides.push(clone);
                            cloneIndex++;
                        }
                    }

                    var currentIndex = 0;
                    var N = slides.length;

                    // Populate pagination dots dynamically
                    var dotsContainer = document.getElementById("custom-project-dots");
                    if (dotsContainer && originalSlides.length > 0) {
                        dotsContainer.innerHTML = "";
                        originalSlides.forEach(function(_, index) {
                            var dot = document.createElement("button");
                            dot.className =
                                "project-dot w-2 h-2 md:w-3 md:h-3 rounded-full transition-all duration-500 cursor-pointer" +
                                (index === 0 ? " active" : "");
                            dot.setAttribute("data-index", index);
                            dotsContainer.appendChild(dot);
                        });
                    }
                    var dots = dotsContainer ? dotsContainer.querySelectorAll(".project-dot") : [];
                    var hoverTimer = null;

                    function promoteSideSlide(slide) {
                        if (!slide || slide.classList.contains("slide-center")) {
                            return false;
                        }

                        if (slide.classList.contains("slide-left-1")) {
                            currentIndex = (currentIndex - 1 + N) % N;
                        } else if (slide.classList.contains("slide-right-1")) {
                            currentIndex = (currentIndex + 1) % N;
                        } else if (slide.classList.contains("slide-left-2")) {
                            currentIndex = (currentIndex - 2 + N) % N;
                        } else if (slide.classList.contains("slide-right-2")) {
                            currentIndex = (currentIndex + 2) % N;
                        } else {
                            return false;
                        }

                        updateSlider();
                        resetAutoPlay();
                        return true;
                    }

                    function scheduleSideSlidePromotion(slide) {
                        clearTimeout(hoverTimer);
                        hoverTimer = setTimeout(function() {
                            promoteSideSlide(slide);
                        }, 220);
                    }

                    function cancelSideSlidePromotion() {
                        clearTimeout(hoverTimer);
                    }

                    function updateSlider() {
                        slides.forEach(function(slide, i) {
                            // Remove previous classes
                            slide.classList.remove(
                                "slide-center", "slide-left-1", "slide-left-2",
                                "slide-right-1", "slide-right-2", "slide-outer-left", "slide-outer-right"
                            );

                            // Calculate circular relative index
                            var diff = i - currentIndex;
                            if (diff < -Math.floor(N / 2)) {
                                diff += N;
                            } else if (diff > Math.floor((N - 1) / 2)) {
                                diff -= N;
                            }

                            // Assign class based on relative position
                            if (diff === 0) {
                                slide.classList.add("slide-center");
                            } else if (diff === -1) {
                                slide.classList.add("slide-left-1");
                            } else if (diff === -2) {
                                slide.classList.add("slide-left-2");
                            } else if (diff === 1) {
                                slide.classList.add("slide-right-1");
                            } else if (diff === 2) {
                                slide.classList.add("slide-right-2");
                            } else if (diff < -2) {
                                slide.classList.add("slide-outer-left");
                            } else if (diff > 2) {
                                slide.classList.add("slide-outer-right");
                            }
                        });

                        // Update active dot based on original data index
                        if (dots.length > 0) {
                            var currentSlide = slides[currentIndex];
                            var originalIndex = parseInt(currentSlide.getAttribute("data-index")) || 0;
                            dots.forEach(function(dot, idx) {
                                dot.classList.toggle("active", idx === originalIndex);
                            });
                        }
                    }

                    // Click and hover navigation for side slides
                    slides.forEach(function(slide) {
                        slide.addEventListener("click", function() {
                            promoteSideSlide(slide);
                        });

                        slide.addEventListener("mouseenter", function() {
                            scheduleSideSlidePromotion(slide);
                        });

                        slide.addEventListener("mouseleave", cancelSideSlidePromotion);
                    });

                    // Pagination dots click handler
                    dots.forEach(function(dot) {
                        dot.addEventListener("click", function() {
                            var targetIdx = parseInt(dot.getAttribute("data-index"));
                            // Find the first slide in slides array matching targetIdx
                            for (var i = 0; i < N; i++) {
                                var slideIdx = parseInt(slides[i].getAttribute("data-index"));
                                if (slideIdx === targetIdx) {
                                    // Make sure it goes to the closest matching slide
                                    var diff = i - currentIndex;
                                    if (diff < -Math.floor(N / 2)) diff += N;
                                    else if (diff > Math.floor((N - 1) / 2)) diff -= N;

                                    currentIndex = (currentIndex + diff + N) % N;
                                    updateSlider();
                                    resetAutoPlay();
                                    break;
                                }
                            }
                        });
                    });

                    // Auto play setup
                    var autoPlayInterval;

                    function startAutoPlay() {
                        autoPlayInterval = setInterval(function() {
                            currentIndex = (currentIndex + 1) % N;
                            updateSlider();
                        }, 5000);
                    }

                    function stopAutoPlay() {
                        clearInterval(autoPlayInterval);
                    }

                    // Auto play control functions
                    function resetAutoPlay() {
                        stopAutoPlay();
                        startAutoPlay();
                    }

                    // Pause on hover
                    customSliderContainer.addEventListener("mouseenter", stopAutoPlay);
                    customSliderContainer.addEventListener("mouseleave", startAutoPlay);

                    // Swipe and Drag gesture support (mouse and touch)
                    var startX = 0;
                    var diffX = 0;
                    var isDragging = false;

                    function handleStart(clientX) {
                        startX = clientX;
                        isDragging = true;
                        diffX = 0;
                    }

                    function handleMove(clientX) {
                        if (!isDragging) return;
                        diffX = clientX - startX;
                    }

                    function handleEnd() {
                        if (!isDragging) return;
                        isDragging = false;

                        var swipeThreshold = 50;
                        if (diffX < -swipeThreshold) {
                            currentIndex = (currentIndex + 1) % N;
                            updateSlider();
                            resetAutoPlay();
                        } else if (diffX > swipeThreshold) {
                            currentIndex = (currentIndex - 1 + N) % N;
                            updateSlider();
                            resetAutoPlay();
                        }
                        diffX = 0;
                    }

                    // Touch events
                    customSliderContainer.addEventListener("touchstart", function(e) {
                        handleStart(e.touches[0].clientX);
                    }, {
                        passive: true
                    });

                    customSliderContainer.addEventListener("touchmove", function(e) {
                        handleMove(e.touches[0].clientX);
                    }, {
                        passive: true
                    });

                    customSliderContainer.addEventListener("touchend", function(e) {
                        handleEnd();
                    }, {
                        passive: true
                    });

                    customSliderContainer.addEventListener("touchcancel", function(e) {
                        handleEnd();
                    }, {
                        passive: true
                    });

                    // Mouse events for desktop dragging
                    customSliderContainer.addEventListener("mousedown", function(e) {
                        // Only drag on left click
                        if (e.button !== 0) return;
                        handleStart(e.clientX);
                        // Prevent text/image selection/dragging
                        e.preventDefault();
                    });

                    window.addEventListener("mousemove", function(e) {
                        if (isDragging) {
                            handleMove(e.clientX);
                        }
                    });

                    window.addEventListener("mouseup", function(e) {
                        if (isDragging) {
                            handleEnd();
                        }
                    });

                    // Initialize Custom Slider
                    updateSlider();
                    startAutoPlay();
                }

                // --- Init Art Value Swiper ---
                var artValueSwiperEl = document.querySelector(".art-value-swiper");
                if (artValueSwiperEl && typeof Swiper !== "undefined") {
                    var artValueSwiperInstance = null;

                    function initArtValueSwiper() {
                        var isMobile = window.innerWidth < 768;
                        if (isMobile && !artValueSwiperInstance) {
                            artValueSwiperInstance = new Swiper(artValueSwiperEl, {
                                slidesPerView: 1.15,
                                spaceBetween: 20,
                                grabCursor: true,
                                pagination: {
                                    el: ".art-value-pagination",
                                    clickable: true,
                                },
                            });
                        } else if (!isMobile && artValueSwiperInstance) {
                            artValueSwiperInstance.destroy(true, true);
                            artValueSwiperInstance = null;
                        }
                    }

                    initArtValueSwiper();
                    window.addEventListener("resize", initArtValueSwiper);
                }
            });
        </script>
    @endpush

</x-client.layouts.main>
