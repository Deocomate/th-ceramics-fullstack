<?php

namespace Database\Seeders;

use App\Domains\Catalog\Infrastructure\Models\VanMieuFishScaleRoofTile;
use Database\Seeders\Support\CanonicalProductSeeding;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class FishScaleRoofTileSeeder extends Seeder
{
    use CanonicalProductSeeding;
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->seedFromData('ngoi_hai_van_mieu', VanMieuFishScaleRoofTile::class);
        $this->seedCanonicalProductType('ngoi_hai_co_ct', 'ngoi_hai_co_ct_id', false);
        $this->seedCanonicalProductType('ngoi_hai_van_mieu_ct', 'ngoi_hai_van_mieu_ct_id', false);
        $this->seedCanonicalVariants('mau_sac_ngoi_hai_co_ct', 'mau_sac_ngoi_hai_co_ct_id', 'ngoi_hai_co_ct_id', 'ngoi_hai_co_ct');
        $this->seedCanonicalVariants('mau_sac_ngoi_hai_van_mieu_ct', 'mau_sac_ngoi_hai_van_mieu_ct_id', 'ngoi_hai_van_mieu_ct_id', 'ngoi_hai_van_mieu_ct');
    }
}
