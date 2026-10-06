<x-client.layouts.main title="Trang chủ" data-page="index" main-class="bg-white">
    <x-client.content.home.banner-slider :trang-chu="$trangChu" />
    <x-client.commerce.shared.coupon-banner :banner-coupons="$bannerCoupons" />
    <x-client.content.home.works :projects="$projects" />
    <x-client.content.home.partner-slider :trang-chu="$trangChu" />
    <x-client.content.home.products-ngoi-am-duong :ngoi-am-duongs="$ngoiAmDuongs" />
    <x-client.content.home.products-ngoi-hai :ngoi-hais="$ngoiHais" />
    <x-client.content.home.products-gach-hoa :gach-hoas="$gachHoas" />
    <x-client.content.home.ceo-letter :trang-chu="$trangChu" />

    <section class="bg-neutral-2 pt-8 lg:pt-20 overflow-hidden">
        <div class="w-[85%] max-w-[1320px] mx-auto mb-5 lg:mb-12">
            <h2
                class="text-center text-secondary text-[20px] leading-[24px] md:text-left lg:text-left lg:text-4xl font-bold uppercase"
                data-aos="fade-up">
                Giải thưởng & thành tựu
            </h2>
        </div>
        <x-client.content.home.awards-deck :awards="$awards" />
    </section>

    <x-client.content.home.press-marquee :trang-chu="$trangChu" />
    <x-client.content.home.video-stats :trang-chu="$trangChu" />
    <x-client.content.home.showroom-grid :trang-chu="$trangChu" />
    <x-client.content.shared.newsletter />
</x-client.layouts.main>