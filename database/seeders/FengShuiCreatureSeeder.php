<?php

namespace Database\Seeders;

use App\Domains\Catalog\Infrastructure\Models\FengShuiCreature;
use App\Domains\Catalog\Infrastructure\Models\FengShuiCreatureImage;
use App\Domains\Catalog\Infrastructure\Models\FengShuiCreatureLegacy;
use Database\Seeders\Support\CanonicalProductSeeding;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class FengShuiCreatureSeeder extends Seeder
{
    use CanonicalProductSeeding;
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->seedFromData('linh_vat_phong_thuy', FengShuiCreature::class);
        $this->seedFromData('linh_vat', FengShuiCreatureLegacy::class);
        $this->seedFromData('linh_vat_phong_thuy_anh', FengShuiCreatureImage::class);
        $this->seedCanonicalProductType('linh_vat_phong_thuy_ct', 'linh_vat_phong_thuy_ct_id', true);
    }
}
