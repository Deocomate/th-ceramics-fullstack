<?php

namespace Database\Seeders;

use App\Domains\Catalog\Infrastructure\Models\RoofTileAccessory;
use Database\Seeders\Support\CanonicalProductSeeding;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class RoofTileAccessorySeeder extends Seeder
{
    use CanonicalProductSeeding;
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->seedFromData('phu_kien_ngoi', RoofTileAccessory::class);
        $this->seedCanonicalProductType('phu_kien_ngoi_ct', 'phu_kien_ngoi_ct_id', false);
        $this->seedCanonicalVariants('phan_loai_phu_kien_ngoi_ct', 'phan_loai_phu_kien_ngoi_ct_id', 'phu_kien_ngoi_ct_id', 'phu_kien_ngoi_ct');
    }
}
