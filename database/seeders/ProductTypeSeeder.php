<?php

namespace Database\Seeders;

use App\Domains\Catalog\Infrastructure\Models\CeramicBalustrade;
use App\Domains\Catalog\Infrastructure\Models\CeramicLamp;
use App\Domains\Catalog\Infrastructure\Models\CeramicLampImage;
use App\Domains\Catalog\Infrastructure\Models\FengShuiCreature;
use App\Domains\Catalog\Infrastructure\Models\FengShuiCreatureImage;
use App\Domains\Catalog\Infrastructure\Models\FengShuiCreatureLegacy;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Các module đã chuyển sang Seeder riêng nên bị gỡ khỏi đây.
        // Chỉ còn lại:
        $this->seedLanCanGomXu();
        $this->seedLinhVatPhongThuy();
        $this->seedDenGomSu();
    }

    private function seedLanCanGomXu(): void
    {
        CeramicBalustrade::firstOrCreate(
            ['lan_can_gom_xu_id' => 1],
            [
                'thumbnail_main' => 'assets/images/lan-can-01.jpg',
                'video' => 'https://www.youtube.com/embed/Win12rIicBI',
            ]
        );
    }

    private function seedLinhVatPhongThuy(): void
    {
        $parent = FengShuiCreature::firstOrCreate(
            ['linh_vat_phong_thuy_id' => 1],
            [
                'thumbnail_main' => 'assets/images/linh-vat-banner.png',
                'video' => 'https://www.youtube.com/embed/Win12rIicBI',
            ]
        );

        $linhVats = [
            ['title' => 'Long', 'image' => 'assets/images/dau-rong.png', 'description' => 'Rồng (Long) — linh vật quyền lực nhất trong tứ linh, biểu tượng của sức mạnh và sự uy nghi.'],
            ['title' => 'Lân', 'image' => 'assets/images/lan-can-01.png', 'description' => 'Lân — linh vật của sự may mắn, bình an, bảo vệ gia chủ khỏi tà khí.'],
        ];

        foreach ($linhVats as $lv) {
            FengShuiCreatureLegacy::firstOrCreate(
                ['title' => $lv['title']],
                [
                    'image' => $lv['image'],
                    'description' => $lv['description'],
                    'linh_vat_phong_thuy_id' => $parent->linh_vat_phong_thuy_id,
                ]
            );
        }

        $anhs = [
            ['image' => 'assets/images/work-01.jpg'],
            ['image' => 'assets/images/work-02.jpg'],
        ];

        foreach ($anhs as $anh) {
            FengShuiCreatureImage::firstOrCreate(
                ['image' => $anh['image']],
                ['linh_vat_phong_thuy_id' => $parent->linh_vat_phong_thuy_id]
            );
        }
    }

    private function seedDenGomSu(): void
    {
        $parent = CeramicLamp::firstOrCreate(
            ['den_gom_su_id' => 1], [
                'thumbnail_main' => 'assets/images/den-gom-banner.png',
                'video' => 'https://www.youtube.com/embed/Win12rIicBI',
                'title2' => 'Đèn gốm sứ cao cấp',
                'title3' => 'Sản phẩm tiêu biểu',
                'image1' => 'assets/images/den-gom-01.png',
                'image2' => 'assets/images/den-gom-02.png',
                'image3' => 'assets/images/den-gom-01.png',
                'image4' => 'assets/images/den-gom-02.png',
            ]
        );

        $anhs = [
            ['image' => 'assets/images/den-gom-01.png'],
            ['image' => 'assets/images/den-gom-02.png'],
        ];

        foreach ($anhs as $anh) {
            CeramicLampImage::firstOrCreate(
                ['image' => $anh['image']],
                ['den_gom_su_id' => $parent->den_gom_su_id]
            );
        }
    }
}
