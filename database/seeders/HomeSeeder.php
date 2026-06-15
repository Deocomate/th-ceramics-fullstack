<?php

namespace Database\Seeders;

use App\Models\TrangChu;
use Database\Seeders\Support\SeederDataContract;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class HomeSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        TrangChu::truncate();

        $row = $this->withoutTimestamps($this->seederDataFirst('trang_chu') ?? []);

        $bannerPool = [
            'assets/images/ngoi-am-duong-banner.jpg',
            'assets/images/home-hero-01.png',
            'assets/images/factory-01.jpg',
            'assets/images/factory-02.png',
            'assets/images/trang-tri-slide-01.jpg',
        ];

        $showroomPool = [
            'assets/images/showroom-01.png',
            'assets/images/showroom-02.png',
            'assets/images/showroom-03.png',
            'assets/images/factory-03.png',
            'assets/images/factory-04.jpg',
        ];

        $row['banner'] = SeederDataContract::expandGallery(
            $row['banner'] ?? [],
            $bannerPool,
            5
        );

        $row['showroom_images'] = SeederDataContract::expandGallery(
            $row['showroom_images'] ?? [],
            $showroomPool,
            5
        );

        SeederDataContract::assertGallery($row['banner'], 'trang_chu.banner');
        SeederDataContract::assertGallery($row['showroom_images'], 'trang_chu.showroom_images');

        TrangChu::create($row);
    }
}
