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

    <div class="w-full flex justify-start md:justify-center md:mb-6 pb-2 md:pb-0 mobile-scroll-visible">
        <img src="{{ asset('assets/images/process.png') }}" alt="Quy trình chế tác gạch"
            class="h-auto object-contain max-w-none w-[200%] md:max-w-full md:w-full" />
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
