<?php

namespace Database\Seeders;

use App\Models\DinhMucGachCoBatTrang;
use App\Models\DinhMucGachHoaThongGio;
use App\Models\DinhMucGachTrangTri;
use App\Models\DinhMucNgoiAmDuong;
use App\Models\DinhMucNgoiHaiCo;
use App\Models\DinhMucNgoiHaiVanMieu;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class DinhMucSeeder extends Seeder
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

        $this->seedFromData('dinh_muc_ngoi_am_duong', DinhMucNgoiAmDuong::class);
        $this->seedFromData('dinh_muc_ngoi_hai_co', DinhMucNgoiHaiCo::class);
        $this->seedFromData('dinh_muc_ngoi_hai_van_mieu', DinhMucNgoiHaiVanMieu::class);
        $this->seedFromData('dinh_muc_gach_trang_tri', DinhMucGachTrangTri::class);
        $this->seedFromData('dinh_muc_gach_hoa_thong_gio', DinhMucGachHoaThongGio::class);
        $this->seedFromData('dinh_muc_gach_co_bat_trang', DinhMucGachCoBatTrang::class);
    }
}
