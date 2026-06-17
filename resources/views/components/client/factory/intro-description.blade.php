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

@php
    $introBlocks = is_array($factory->intro_description ?? null) ? $factory->intro_description : [];
@endphp

@push('styles')
    <style>
        /* ─────────────────────────────────────────────────────────────────────────
               FACTORY INTRO SECTION CUSTOM STYLES
               ───────────────────────────────────────────────────────────────────────── */
        .factory-intro-section {
            background-color: #F5EDE8;
            position: relative;
            overflow: hidden;
            color: #2E2F2A;
        }

        /* 1. Grid Overlay & Blueprint Lines */
        .factory-intro-section .grid-overlay {
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }

        .factory-intro-section .grid-overlay-container {
            width: 85%;
            max-width: 1320px;
            margin-left: auto;
            margin-right: auto;
            height: 100%;
            position: relative;
        }

        .factory-intro-section .line {
            position: absolute;
            background-color: rgba(0, 0, 0, 0.1);
            display: none;
            /* Ẩn mặc định ở mobile */
        }

        @media (min-width: 768px) {
            .factory-intro-section .line {
                display: block;
                /* Chỉ hiện thị trên màn hình md trở lên */
            }
        }

        .factory-intro-section .line-v {
            width: 1px;
        }

        .factory-intro-section .line-h {
            height: 1px;
        }

        /* Định vị tọa độ các đường line chính xác theo bản vẽ */
        .factory-intro-section .line-a {
            left: 0;
            top: 5%;
            height: 35%;
        }

        .factory-intro-section .line-b {
            top: 48%;
            left: 20vw;
            width: 70vw;
        }

        .factory-intro-section .line-bottom {
            top: 85%;
            left: 25%;
            width: 100vw;
        }

        .factory-intro-section .line-c {
            left: 33.3333%;
            top: 23%;
            height: 29%;
        }

        .factory-intro-section .line-d {
            right: 0;
            top: 30%;
            height: 66%;
        }

        /* 2. Content Layout */
        .factory-intro-section .content-container {
            position: relative;
            z-index: 10;
            width: 85%;
            max-width: 1320px;
            margin-left: auto;
            margin-right: auto;
        }

        .factory-intro-section .row-1,
        .factory-intro-section .row-2 {
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 768px) {

            .factory-intro-section .row-1,
            .factory-intro-section .row-2 {
                flex-direction: row;
            }
        }

        /* Title Column */
        .factory-intro-section .title-col {
            width: 100%;
            padding-top: 40px;
            padding-bottom: 48px;
            padding-left: 16px;
            padding-right: 16px;
        }

        @media (min-width: 768px) {
            .factory-intro-section .title-col {
                width: 33.3333%;
                padding-top: 128px;
                padding-bottom: 128px;
                padding-left: 40px;
                padding-right: 32px;
            }
        }

        .factory-intro-section .title-h2 {
            font-size: 36px;
            line-height: 40px;
            letter-spacing: -0.015em;
            font-weight: 500;
            font-family: "Arima", system-ui, sans-serif;
        }

        @media (min-width: 768px) {
            .factory-intro-section .title-h2 {
                font-size: 52px;
                line-height: 1.2;
            }
        }

        @media (min-width: 1024px) {
            .factory-intro-section .title-h2 {
                font-size: 64px;
            }
        }

        /* Info Column */
        .factory-intro-section .info-col {
            width: 100%;
            padding-top: 0;
            padding-bottom: 0;
            padding-left: 16px;
            padding-right: 16px;
        }

        @media (min-width: 768px) {
            .factory-intro-section .info-col {
                width: 66.6667%;
                padding-top: 96px;
                padding-bottom: 70px;
                padding-left: 64px;
                padding-right: 48px;
            }
        }

        .factory-intro-section .subtitle-h3 {
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 40px;
            line-height: 24px;
        }

        @media (min-width: 768px) {
            .factory-intro-section .subtitle-h3 {
                font-size: 20px;
                margin-bottom: 24px;
            }
        }

        /* Paragraph Blocks inside Info */
        .factory-intro-section .info-p {
            font-size: 16px;
            font-weight: 200;
            font-family: Archivo, sans-serif;
            text-align: left;
            line-height: 24px;
            margin-bottom: 16px;
        }

        @media (min-width: 768px) {
            .factory-intro-section .info-p {
                color: #101010;
                font-size: 16px;
                font-family: Archivo, sans-serif;
                font-weight: 200;
                text-align: justify;
                line-height: 36px;
                margin-bottom: 0px;
            }
        }

        .factory-intro-section .info-p:last-child {
            margin-bottom: 0;
        }

        /* List Blocks inside Info */
        .factory-intro-section .info-list {
            margin-top: 0;
            margin-bottom: 16px;
            margin-left: 20px;
            list-style-type: decimal;
            font-size: 16px;
            font-weight: 200;
            font-family: Archivo, sans-serif;
            text-align: left;
            line-height: 24px;
        }

        @media (min-width: 768px) {
            .factory-intro-section .info-list {
                color: #101010;
                font-size: 16px;
                font-family: Archivo, sans-serif;
                font-weight: 200;
                text-align: justify;
                line-height: 36px;
            }
        }

        .factory-intro-section .info-list:last-child {
            margin-bottom: 0;
        }

        .factory-intro-section .info-list li {
            margin-bottom: 4px;
        }

        .factory-intro-section .info-list li::marker {
            font-weight: bold;
            color: #2E2F2A;
        }

        .factory-intro-section .info-list strong {
            font-weight: bold;
            color: #2E2F2A;
        }

        @media (min-width: 768px) {

            .factory-intro-section .info-list li::marker,
            .factory-intro-section .info-list strong {
                font-weight: 200;
                color: #101010;
            }
        }

        /* Dividers (Mobile only) */
        .factory-intro-section .mobile-divider {
            height: 1px;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.1);
            margin-top: 64px;
            display: block;
        }

        @media (min-width: 768px) {
            .factory-intro-section .mobile-divider {
                display: none;
            }
        }

        /* Row 2 Spacer & Content */
        .factory-intro-section .row-2-spacer {
            display: none;
        }

        @media (min-width: 768px) {
            .factory-intro-section .row-2-spacer {
                display: block;
                width: 20%;
            }
        }

        .factory-intro-section .row-2-content {
            width: 100%;
            padding-top: 32px;
            padding-bottom: 32px;
            padding-left: 16px;
            padding-right: 16px;
        }

        @media (min-width: 768px) {
            .factory-intro-section .row-2-content {
                width: 80%;
                padding-top: 0px;
                padding-bottom: 40px;
                padding-left: 64px;
                padding-right: 80px;
            }
        }

        .factory-intro-section .quote-p {
            font-size: 24px;
            line-height: 35px;
            font-family: "Arima", system-ui, sans-serif;
            font-weight: 400;
            text-align: left;
        }

        @media (min-width: 768px) {
            .factory-intro-section .quote-p {
                font-size: 32px;
                line-height: 1.5;
                text-align: justify;
            }
        }

        @media (min-width: 1024px) {
            .factory-intro-section .quote-p {
                font-size: 36px;
            }
        }

        .factory-intro-section .row-2-divider {
            height: 1px;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.1);
            margin-top: 40px;
            display: block;
        }

        @media (min-width: 768px) {
            .factory-intro-section .row-2-divider {
                display: none;
            }
        }

        /* Spacers */
        .factory-intro-section .bottom-spacer {
            display: none;
        }

        @media (min-width: 768px) {
            .factory-intro-section .bottom-spacer {
                display: flex;
                flex-direction: row;
                height: 96px;
            }
        }
    </style>
@endpush

<section class="factory-intro-section">
    <!-- 1. Grid Overlay (Behind Content) -->
    <div class="grid-overlay">
        <div class="grid-overlay-container">
            <!-- Line A (Vertical Left) -->
            <div class="line line-v line-a"></div>

            <!-- Line B (Horizontal Mid) -->
            <div class="line line-h line-b"></div>

            <!-- Completing the horizontal axis to the right edge -->
            <div class="line line-h line-bottom"></div>

            <!-- Line C (The "T" Intersection) -->
            <div class="line line-v line-c"></div>

            <!-- Line D (Right Border) -->
            <div class="line line-v line-d"></div>
        </div>
    </div>

    <!-- 2. Clean HTML Content -->
    <div class="content-container">
        <!-- Row 1 -->
        <div class="row-1">
            <!-- Left col (Title) -->
            <div class="title-col">
                <h2 class="title-h2" data-aos="fade-up">
                    {{ $factory->intro_title ?? 'Nhà xưởng' }}
                </h2>
            </div>
            <!-- Right col (Description) -->
            <div class="info-col">
                <h3 class="subtitle-h3" data-aos="fade-up">
                    {{ $factory->intro_subtitle ?? 'QUY MÔ ẤN TƯỢNG: 5000M² - 3 TẦNG VẬN HÀNH CHUYÊN BIỆT' }}
                </h3>
                @foreach ($introBlocks as $block)
                    @if (($block['type'] ?? null) === 'paragraph' && !empty($block['content']))
                        <p class="info-p" data-aos="fade-up" data-aos-delay="100">
                            {!! nl2br(e($block['content'])) !!}
                        </p>
                    @elseif(($block['type'] ?? null) === 'list' && !empty($block['items']) && is_array($block['items']))
                        <ul class="info-list" data-aos="fade-up" data-aos-delay="100">
                            @foreach ($block['items'] as $item)
                                @if (!empty($item['title']) || !empty($item['content']))
                                    <li>
                                        @if (!empty($item['title']))
                                            <strong>{{ $item['title'] }}</strong>
                                        @endif
                                        {!! nl2br(e($item['content'] ?? '')) !!}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                @endforeach

                <!-- Mobile Divider -->
                <div class="mobile-divider"></div>
            </div>
        </div>

        <!-- Row 2 (Quote) -->
        <div class="row-2">
            <!-- Left col (empty spacer) -->
            <div class="row-2-spacer"></div>
            <!-- Right col -->
            <div class="row-2-content">
                <p class="quote-p" data-aos="fade-up">
                    Lựa chọn Gốm sứ Thanh Hải, quý khách không chỉ mua một loại vật liệu
                    xây dựng, mà đang đặt niềm tin vào một quy trình vận hành tận tâm,
                    chuyên nghiệp và những giá trị văn hóa bền vững theo thời gian.
                </p>
                <!-- Mobile Divider -->
                <div class="row-2-divider"></div>
            </div>
        </div>

        <!-- Bottom spacing row -->
        <div class="bottom-spacer"></div>
    </div>
</section>
