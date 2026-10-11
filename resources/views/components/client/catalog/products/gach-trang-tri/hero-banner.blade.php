@props([
    'config' => null,
])
<!-- Top Banner -->
<section
    class="relative w-full min-h-[322px] md:min-h-[500px] lg:min-h-[600px] flex items-center md:pb-8 overflow-hidden">
    <!-- Background Image with Dark Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="{{ $config && $config->thumbnail_main ? asset('storage/' . $config->thumbnail_main) : asset('assets/images/gach-trang-tri-banner.webp') }}"
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
