@props(['title' => null, 'description' => null, 'dataPage' => null, 'mainClass' => null, 'hideNewsletter' => false])

<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ $title ? $title . ' - Thanh Hải Ceramics' : 'Thanh Hải Ceramics - Ngói và Gạch Hoa Truyền Thống' }}</title>
    @if($description)
    <meta name="description" content="{{ $description }}" />
    @endif

    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/logo.png') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#2E2F2A",
                        secondary: "#C76E00",
                        textPrimary: "#2E2F2A",
                        neutral: { 1: "#EFE4DE", 2: "#F5EDE9" },
                        background: { primary: "#FFF", secondary: "#F5EDE9" },
                    },
                    fontFamily: {
                        archivo: ['"Archivo"', "sans-serif"],
                        italianno: ['"Italianno"', "cursive"],
                        arima: ['"Arima"', "system-ui"],
                        arbutus: ['"Arbutus Slab"', "serif"],
                        charm: ['"Charm"', "cursive"],
                        lavishly: ['"Lavishly Yours"', "cursive"],
                        ephesis: ['"Ephesis"', "cursive"],
                        carattere: ['"Carattere"', "cursive"],
                        advent: ['"Advent Pro"', "sans-serif"],
                    },
                    keyframes: {
                        marquee: {
                            "0%": { transform: "translateX(0)" },
                            "100%": { transform: "translateX(-50%)" },
                        },
                    },
                    animation: {
                        marquee: "marquee 15s linear infinite",
                    },
                    order: {
                        13: "13", 14: "14", 15: "15", 16: "16",
                        17: "17", 18: "18", 19: "19", 20: "20",
                    },
                },
            },
        }
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />

    @stack('styles')
    <style>
        .awards-swiper .swiper-slide,
        .product-main-swiper .swiper-slide,
        .showroomSwiper .swiper-slide,
        .showroomSwiper2 .swiper-slide,
        .detail2-gallery-swiper .swiper-slide,
        .factory-swiper .swiper-slide,
        .slider-final-swiper .swiper-slide,
        .section3-swiper .swiper-slide,
        .section4-swiper .swiper-slide,
        .projects-slider .swiper-slide,
        .product-collage-swiper .swiper-slide {
            cursor: zoom-in;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col font-archivo text-primary">

    <x-client.layouts.header />

    @if(session('success'))
    <div class="bg-green-50 border-b border-green-200 text-green-800 px-4 py-3 text-center text-sm font-archivo">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-b border-red-200 text-red-800 px-4 py-3 text-center text-sm font-archivo">
        {{ session('error') }}
    </div>
    @endif

    <main @if($dataPage) data-page="{{ $dataPage }}" @endif
          class="flex-grow {{ $mainClass ?? 'bg-white' }}">
        {{ $slot }}
    </main>

    <x-client.layouts.footer :hide-newsletter="$hideNewsletter" />

    <x-client.shared.cart-toast />
    <x-client.shared.cart-modal />
    @unless ($isEcommerceEnabled ?? true)
        <x-client.shared.consultation-modal />
    @endunless

    <!-- Global Lightbox -->
    <div id="global-lightbox" class="fixed inset-0 z-[9999] bg-[#0c0c0b]/95 backdrop-blur-md flex flex-col justify-between opacity-0 pointer-events-none transition-all duration-300 ease-out" aria-hidden="true">
        <!-- Top Bar: Counter & Close -->
        <div class="flex justify-between items-center px-6 py-4 text-white z-20">
            <div id="global-lightbox-counter" class="text-sm font-semibold tracking-wider text-white/80"></div>
            <button id="global-lightbox-close" type="button" aria-label="Close image preview" class="w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 transition-all duration-300 text-white focus:outline-none hover:rotate-90 hover:scale-105 active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Main Swiper Container -->
        <div class="swiper global-lightbox-swiper w-full flex-grow flex items-center justify-center overflow-hidden relative">
            <div class="swiper-wrapper h-full">
                <!-- Dynamic slides injected by JS -->
            </div>

            <!-- Navigation Buttons -->
            <button type="button" class="global-lightbox-prev absolute left-4 md:left-8 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white border border-white/10 transition-all duration-300 hover:scale-110 active:scale-90 cursor-pointer select-none focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button type="button" class="global-lightbox-next absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white border border-white/10 transition-all duration-300 hover:scale-110 active:scale-90 cursor-pointer select-none focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

        <!-- Bottom Bar: Caption -->
        <div class="w-full text-center px-6 py-6 text-white bg-gradient-to-t from-black/90 to-transparent z-10">
            <p id="global-lightbox-caption" class="text-sm md:text-base font-medium max-w-3xl mx-auto line-clamp-2 text-white/95 leading-relaxed tracking-wide"></p>
        </div>
    </div>

    <script type="module" src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 800, once: true, offset: 100 });
        }
    </script>

    @stack('scripts')
</body>

</html>
