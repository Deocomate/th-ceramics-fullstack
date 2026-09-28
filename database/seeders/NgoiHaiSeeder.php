<?php

namespace Database\Seeders;

use App\Models\NgoiHaiVanMieu;
use Database\Seeders\Support\CanonicalProductSeeding;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class NgoiHaiSeeder extends Seeder
{
    use SeedsFromSqlData;
    use CanonicalProductSeeding;

    public function run(): void
    {
        $this->seedFromData('ngoi_hai_van_mieu', NgoiHaiVanMieu::class);
        $this->seedCanonicalProductType('ngoi_hai_co_ct', 'ngoi_hai_co_ct_id', false);
        $this->seedCanonicalProductType('ngoi_hai_van_mieu_ct', 'ngoi_hai_van_mieu_ct_id', false);
        $this->seedCanonicalVariants('mau_sac_ngoi_hai_co_ct', 'mau_sac_ngoi_hai_co_ct_id', 'ngoi_hai_co_ct_id', 'ngoi_hai_co_ct');
        $this->seedCanonicalVariants('mau_sac_ngoi_hai_van_mieu_ct', 'mau_sac_ngoi_hai_van_mieu_ct_id', 'ngoi_hai_van_mieu_ct_id', 'ngoi_hai_van_mieu_ct');
    }
}
