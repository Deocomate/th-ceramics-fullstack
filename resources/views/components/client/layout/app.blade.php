<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Title & Meta -->
    <title>Trang chủ - Thanh Hải Ceramics</title>

    <!-- Global CSS & Fonts -->
    <link rel="icon" type="image/svg+xml" href="/assets/images/logo.png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />

    <!-- BEGIN: Style riêng của từng page -->
    @stack("styles")
    <!-- END: Style riêng của từng page -->
    
    <x-client.layout.styles></x-client.layout.styles>
</head>

<body class="min-h-screen flex flex-col font-archivo text-primary">
    <x-client.layout.header></x-client.layout.header>

    <!-- BEGIN: Main Content: Đón toàn bộ biến động (data-page, class nền, v.v...) -->
    {{ $slot }}
    <!-- END: Main Content: Đón toàn bộ biến động (data-page, class nền, v.v...) -->

    <x-client.layout.footer></x-client.layout.footer>

    <x-client.layout.scripts></x-client.layout.scripts>

    <!-- BEGIN: Script riêng của từng page -->
    @stack("scripts")
    <!-- END: Script riêng của từng page -->
</body>

</html>
