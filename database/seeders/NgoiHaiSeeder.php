<?php

namespace Database\Seeders;

use App\Models\MauSacNgoiHaiCoCt;
use App\Models\MauSacNgoiHaiVanMieuCt;
use App\Models\NgoiHaiCoCt;
use App\Models\NgoiHaiVanMieu;
use App\Models\NgoiHaiVanMieuCt;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class NgoiHaiSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->truncateTables(
            'mau_sac_ngoi_hai_van_mieu_ct',
            'mau_sac_ngoi_hai_co_ct',
            'ngoi_hai_van_mieu_ct',
            'ngoi_hai_co_ct',
            'ngoi_hai_van_mieu',
        );

        $this->seedFromData('ngoi_hai_van_mieu', NgoiHaiVanMieu::class);
        $this->seedFromData('ngoi_hai_co_ct', NgoiHaiCoCt::class);
        $this->seedFromData('ngoi_hai_van_mieu_ct', NgoiHaiVanMieuCt::class);
        $this->seedFromData('mau_sac_ngoi_hai_co_ct', MauSacNgoiHaiCoCt::class);
        $this->seedFromData('mau_sac_ngoi_hai_van_mieu_ct', MauSacNgoiHaiVanMieuCt::class);
    }
}
