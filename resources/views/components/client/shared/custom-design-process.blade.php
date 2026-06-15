@props(['images' => []])

@php
    $mediaUrl = function (?string $path, string $fallback) {
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
@endphp

<style>
    /* Desktop aspect container and typography scaling */
    .process-desktop-container {
        position: relative;
        width: 100%;
        aspect-ratio: 1320 / 346;
        container-type: inline-size;
    }

    .process-desktop-container .process-title {
        color: #2E2F2A;
        font-family: 'Archivo', sans-serif;
        font-weight: 500;
        font-size: 2.4242cqw; /* 32px at 1320px width */
        line-height: 3.0303cqw; /* 40px */
        text-align: center;
        transition: color 0.3s ease;
    }

    .process-desktop-container .process-desc {
        color: rgba(46, 47, 42, 0.8);
        font-family: 'Archivo', sans-serif;
        font-weight: 300;
        font-size: 1.2121cqw; /* 16px at 1320px width */
        line-height: 2.2727cqw; /* 30px */
        text-align: center;
    }

    /* Column blocks positioning */
    .process-desktop-container .process-col-1 {
        position: absolute;
        left: 20.11%;
        width: 23.64%;
        top: 0;
        height: 100%;
        transform: translateX(-50%);
    }

    .process-desktop-container .process-col-2 {
        position: absolute;
        left: 49.81%;
        width: 23.64%;
        top: 0;
        height: 100%;
        transform: translateX(-50%);
    }

    .process-desktop-container .process-col-3 {
        position: absolute;
        left: 79.13%;
        width: 23.64%;
        top: 0;
        height: 100%;
        transform: translateX(-50%);
    }

    /* Inner absolute elements */
    .process-desktop-container .col-title {
        position: absolute;
        top: 32.95%; /* 114px */
        left: 0;
        width: 100%;
    }

    .process-desktop-container .col-desc {
        position: absolute;
        top: 49.42%; /* 171px */
        left: 0;
        width: 100%;
    }

    /* Patterns */
    .process-desktop-container .process-pat-1 {
        position: absolute;
        left: 5.74%;
        top: 20.61%;
        width: 11.11%;
        height: auto;
        transform: translate(-50%, -50%) rotate(-5.23deg);
    }

    .process-desktop-container .process-pat-2 {
        position: absolute;
        left: 94%;
        top: 79%;
        width: 11.11%;
        height: auto;
        transform: translate(-50%, -50%) rotate(-362deg);
    }

    /* Arrows connecting titles */
    .process-desktop-container .process-arr-1 {
        position: absolute;
        left: 29.09%;
        width: 11.74%;
        top: 13.5%;
        height: auto;
    }

    .process-desktop-container .process-arr-2 {
        position: absolute;
        left: 58.79%;
        width: 11.36%;
        top: 71.05%;
        height: auto;
    }
</style>

<section class="w-[85%] max-w-[1320px] mx-auto pb-[20px] md:pb-16" data-aos="fade-up">
    <div class="w-full mb-[10px] md:mb-10">
        <div class="flex flex-col w-full">
            <h2
                class="text-nowrap text-secondary font-italianno font-normal text-[25px] sm:text-[28px] leading-[45px] md:text-[60px] md:leading-none text-left drop-shadow-sm">
                Cùng chúng tôi hiện thực hóa mẫu gạch trong mơ...
            </h2>
            <h2
                class="text-secondary font-italianno font-normal text-[25px] sm:text-[28px] leading-[45px] md:text-[60px] md:leading-none text-right mt-0 md:mt-2 drop-shadow-sm">
                Yêu cầu của bạn là gì?
            </h2>
        </div>
    </div>

    <!-- Mobile: image + horizontal scroll (giữ nguyên như trước refactor) -->
    <div class="md:hidden w-full flex justify-start pb-2 mobile-scroll-visible">
        <img src="{{ asset('assets/images/process.png') }}" alt="Quy trình chế tác gạch"
            class="h-auto object-contain max-w-none w-[200%]" />
    </div>

    <!-- Desktop: HTML process card -->
    <div class="hidden md:block relative bg-white w-full shadow-[0px_4px_15px_rgba(0,0,0,0.06)] rounded-sm overflow-hidden md:mb-6" data-aos="fade-up">
        <div class="process-desktop-container">
            <!-- Corner Decoration Left-Top -->
            <img src="{{ asset('assets/images/process-pattern-1.png') }}" alt=""
                class="process-pat-1 pointer-events-none select-none z-0" />

            <!-- Corner Decoration Right-Bottom -->
            <img src="{{ asset('assets/images/process-pattern-2.png') }}" alt=""
                class="process-pat-2 pointer-events-none select-none z-0" />

            <!-- Arrow 1 (Desktop, Upper) -->
            <img src="{{ asset('assets/images/process-arrow-1.png') }}" alt=""
                class="process-arr-1 pointer-events-none select-none z-20" />

            <!-- Arrow 2 (Desktop, Lower) -->
            <img src="{{ asset('assets/images/process-arrow-2.png') }}" alt=""
                class="process-arr-2 pointer-events-none select-none z-20" />

            <!-- Column 1: Gửi ý tưởng -->
            <div class="process-col-1 group">
                <h3 class="col-title process-title group-hover:text-secondary">
                    Gửi ý tưởng
                </h3>
                <p class="col-desc process-desc">
                    Cung cấp bản vẽ, kích thước và màu sắc bạn mong muốn
                </p>
            </div>

            <!-- Column 2: Chế tác cốt -->
            <div class="process-col-2 group">
                <h3 class="col-title process-title group-hover:text-secondary">
                    Chế tác cốt
                </h3>
                <p class="col-desc process-desc">
                    Hiện thực hóa hình dáng qua quy trình đúc cốt mẫu và tinh chỉnh chuyên sâu
                </p>
            </div>

            <!-- Column 3: Phối sắc độ -->
            <div class="process-col-3 group">
                <h3 class="col-title process-title group-hover:text-secondary">
                    Phối sắc độ
                </h3>
                <p class="col-desc process-desc">
                    Thử nghiệm đa dạng tông màu trên yêu cầu để tìm ra sắc thái ưng ý nhất
                </p>
            </div>
        </div>
    </div>

    <div class="w-full mt-[10px] md:mt-auto">
        <p
            class="text-[10px] md:text-xl italic text-[#2E2F2A] md:text-primary font-thin leading-[18px] md:leading-normal">
            * Để đảm bảo sự hoàn mỹ: Quá trình chế tác mẫu có tính phí và thời gian
            thực hiện tùy thuộc vào độ tinh xảo của sản phẩm
        </p>
    </div>
</section>

<section class="w-full mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-6">
        <!-- Ảnh 1 -->
        <img src="/assets/images/trang-tri-01.png" alt="Gạch Trang Trí 1"
            class="w-full h-auto object-contain lg:object-cover shadow-lg bg-white" data-aos="fade-up">

        <!-- Ảnh 2 -->
        <img src="/assets/images/trang-tri-02.png" alt="Gạch Trang Trí 2"
            class="hidden md:block w-full h-auto object-contain lg:object-cover shadow-lg bg-white" data-aos="fade-up">
    </div>
</section>
