<?php

namespace Database\Seeders;

use App\Models\DanhMucDuAn;
use App\Models\DuAn;
use Database\Seeders\Support\SeederDataContract;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DuAnSeeder extends Seeder
{
    use SeedsFromSqlData;

    private const IMAGE_POOL = [
        'assets/images/factory-01.jpg',
        'assets/images/factory-02.png',
        'assets/images/factory-03.png',
        'assets/images/factory-04.jpg',
        'assets/images/trang-tri-slide-01.jpg',
        'assets/images/trang-tri-slide-02.jpg',
        'assets/images/trang-tri-slide-03.jpg',
        'assets/images/trang-tri-slide-04.jpg',
        'assets/images/gach-co-work-1.jpg',
        'assets/images/gach-co-work-2.jpg',
    ];

    public function run(): void
    {
        if (File::exists(public_path('assets/images'))) {
            File::copyDirectory(public_path('assets/images'), storage_path('app/public/assets/images'));
        }

        $this->truncateTables('du_an', 'danh_muc_du_an');

        $this->seedFromData('danh_muc_du_an', DanhMucDuAn::class);

        foreach ($this->seederData('du_an') as $index => $row) {
            $row = $this->withoutTimestamps($row);
            $row['images'] = SeederDataContract::rotateGallery(
                self::IMAGE_POOL,
                $index,
                7
            );
            SeederDataContract::assertGallery($row['images'], "du_an.{$row['du_an_id']}.images");
            DuAn::create($row);
        }
    }
}
