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
                        neutral: { 1: "#EFE4DE", 2: "#F5EDE7" },
                        background: { primary: "#FFF", secondary: "#EFE4DE" },
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
</head>

<body class="min-h-screen flex flex-col font-archivo text-primary">

    <x-client.layouts.header />

    @if(session('success'))
    <div class="bg-green-50 border-b border-green-200 text-green-800 px-4 py-3 text-center text-sm font-archivo">
        {{ session('success') }}
    </div>
    @endif

    <main @if($dataPage) data-page="{{ $dataPage }}" @endif
          class="flex-grow {{ $mainClass ?? 'bg-white' }}">
        {{ $slot }}
    </main>

    <x-client.layouts.footer :hide-newsletter="$hideNewsletter" />

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
