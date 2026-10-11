<?php

use App\Domains\Media\Infrastructure\ImageReferenceRewriter;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Static images under public/assets/images that were converted to WebP.
     *
     * @var array<string, string>
     */
    private const CONVERTED = [
        'assets/images/about-01.png' => 'assets/images/about-01.webp',
        'assets/images/award-01.jpg' => 'assets/images/award-01.webp',
        'assets/images/award-03.jpg' => 'assets/images/award-03.webp',
        'assets/images/award-05.jpg' => 'assets/images/award-05.webp',
        'assets/images/bo-noc.png' => 'assets/images/bo-noc.webp',
        'assets/images/chu-van-1.png' => 'assets/images/chu-van-1.webp',
        'assets/images/chu-van-2.png' => 'assets/images/chu-van-2.webp',
        'assets/images/chu-van-3.png' => 'assets/images/chu-van-3.webp',
        'assets/images/chu-van-mobile.png' => 'assets/images/chu-van-mobile.webp',
        'assets/images/contact-map.png' => 'assets/images/contact-map.webp',
        'assets/images/dao-kim.jpg' => 'assets/images/dao-kim.webp',
        'assets/images/dau-rong.png' => 'assets/images/dau-rong.webp',
        'assets/images/den-gom-02.png' => 'assets/images/den-gom-02.webp',
        'assets/images/den-gom-banner.png' => 'assets/images/den-gom-banner.webp',
        'assets/images/doitra.png' => 'assets/images/doitra.webp',
        'assets/images/factory-01.jpg' => 'assets/images/factory-01.webp',
        'assets/images/factory-02.png' => 'assets/images/factory-02.webp',
        'assets/images/factory-03.png' => 'assets/images/factory-03.webp',
        'assets/images/factory-04.jpg' => 'assets/images/factory-04.webp',
        'assets/images/faq2.png' => 'assets/images/faq2.webp',
        'assets/images/footer-image-3.png' => 'assets/images/footer-image-3.webp',
        'assets/images/gach-co-work-1.jpg' => 'assets/images/gach-co-work-1.webp',
        'assets/images/gach-hoa-02.jpg' => 'assets/images/gach-hoa-02.webp',
        'assets/images/gach-hoa-05.jpg' => 'assets/images/gach-hoa-05.webp',
        'assets/images/gach-trang-tri-banner.png' => 'assets/images/gach-trang-tri-banner.webp',
        'assets/images/gia-tri-vuot-troi-01.jpg' => 'assets/images/gia-tri-vuot-troi-01.webp',
        'assets/images/gia-tri-vuot-troi-02.jpg' => 'assets/images/gia-tri-vuot-troi-02.webp',
        'assets/images/gia-tri-vuot-troi-03.jpg' => 'assets/images/gia-tri-vuot-troi-03.webp',
        'assets/images/gia-tri-vuot-troi-04.jpg' => 'assets/images/gia-tri-vuot-troi-04.webp',
        'assets/images/gia-tri-vuot-troi.png' => 'assets/images/gia-tri-vuot-troi.webp',
        'assets/images/gtt-size.png' => 'assets/images/gtt-size.webp',
        'assets/images/home-hero-01.png' => 'assets/images/home-hero-01.webp',
        'assets/images/linh-vat-banner.png' => 'assets/images/linh-vat-banner.webp',
        'assets/images/news-03.png' => 'assets/images/news-03.webp',
        'assets/images/news-05.png' => 'assets/images/news-05.webp',
        'assets/images/news-banner.png' => 'assets/images/news-banner.webp',
        'assets/images/nghe.png' => 'assets/images/nghe.webp',
        'assets/images/ngoi-02.png' => 'assets/images/ngoi-02.webp',
        'assets/images/ngoi-am-duong-02.png' => 'assets/images/ngoi-am-duong-02.webp',
        'assets/images/ngoi-am-duong-size.png' => 'assets/images/ngoi-am-duong-size.webp',
        'assets/images/phuong.png' => 'assets/images/phuong.webp',
        'assets/images/pk-02.jpg' => 'assets/images/pk-02.webp',
        'assets/images/pk-03.jpg' => 'assets/images/pk-03.webp',
        'assets/images/pk-04.jpg' => 'assets/images/pk-04.webp',
        'assets/images/pk-05.jpg' => 'assets/images/pk-05.webp',
        'assets/images/pk-06.jpg' => 'assets/images/pk-06.webp',
        'assets/images/pk-07.jpg' => 'assets/images/pk-07.webp',
        'assets/images/pk-banner.png' => 'assets/images/pk-banner.webp',
        'assets/images/process.png' => 'assets/images/process.webp',
        'assets/images/return-policy.jpg' => 'assets/images/return-policy.webp',
        'assets/images/showroom-01.jpg' => 'assets/images/showroom-01.webp',
        'assets/images/showroom-01.png' => 'assets/images/showroom-01.webp',
        'assets/images/showroom-02.jpg' => 'assets/images/showroom-02.webp',
        'assets/images/showroom-02.png' => 'assets/images/showroom-02.webp',
        'assets/images/showroom-03.png' => 'assets/images/showroom-03.webp',
        'assets/images/showroom-map.png' => 'assets/images/showroom-map.webp',
        'assets/images/trang-tri-slide-01.jpg' => 'assets/images/trang-tri-slide-01.webp',
        'assets/images/trang-tri-slide-02.jpg' => 'assets/images/trang-tri-slide-02.webp',
        'assets/images/trang-tri-slide-04.jpg' => 'assets/images/trang-tri-slide-04.webp',
        'assets/images/video-placeholder-02.png' => 'assets/images/video-placeholder-02.webp',
        'assets/images/work-03.jpg' => 'assets/images/work-03.webp',
    ];

    /**
     * Point stored references at the WebP files that replaced the JPG and PNG originals.
     */
    public function up(): void
    {
        app(ImageReferenceRewriter::class)->rewrite(self::CONVERTED);
    }

    /**
     * Two pairs of originals were the same picture and share one WebP file; flipping
     * keeps the later name of each pair, the PNG the seed data referred to.
     */
    public function down(): void
    {
        app(ImageReferenceRewriter::class)->rewrite(array_flip(self::CONVERTED));
    }
};
