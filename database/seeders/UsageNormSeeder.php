<?php

namespace Database\Seeders;

use App\Domains\Catalog\Infrastructure\Models\UsageNormAncientFishScaleRoofTile;
use App\Domains\Catalog\Infrastructure\Models\UsageNormBatTrangAntiqueBrick;
use App\Domains\Catalog\Infrastructure\Models\UsageNormBreezeBlock;
use App\Domains\Catalog\Infrastructure\Models\UsageNormDecorativeTile;
use App\Domains\Catalog\Infrastructure\Models\UsageNormVanMieuFishScaleRoofTile;
use App\Domains\Catalog\Infrastructure\Models\UsageNormYinYangRoofTile;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class UsageNormSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->truncateTables(
            'dinh_muc_ngoi_am_duong',
            'dinh_muc_ngoi_hai_co',
            'dinh_muc_ngoi_hai_van_mieu',
            'dinh_muc_gach_trang_tri',
            'dinh_muc_gach_hoa_thong_gio',
            'dinh_muc_gach_co_bat_trang',
        );

        $this->seedFromData('dinh_muc_ngoi_am_duong', UsageNormYinYangRoofTile::class);
        $this->seedFromData('dinh_muc_ngoi_hai_co', UsageNormAncientFishScaleRoofTile::class);
        $this->seedFromData('dinh_muc_ngoi_hai_van_mieu', UsageNormVanMieuFishScaleRoofTile::class);
        $this->seedFromData('dinh_muc_gach_trang_tri', UsageNormDecorativeTile::class);
        $this->seedFromData('dinh_muc_gach_hoa_thong_gio', UsageNormBreezeBlock::class);
        $this->seedFromData('dinh_muc_gach_co_bat_trang', UsageNormBatTrangAntiqueBrick::class);
    }
}
