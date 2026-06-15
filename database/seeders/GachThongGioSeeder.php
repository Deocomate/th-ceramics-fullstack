<?php

namespace Database\Seeders;

use App\Models\GachHoaThongGio;
use App\Models\GachHoaThongGioAnh;
use App\Models\GachHoaThongGioCt;
use App\Models\GiaTriGachHoaThongGio;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class GachThongGioSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->truncateTables(
            'gach_hoa_thong_gio_ct',
            'gach_hoa_thong_gio_anh',
            'gia_tri_gach_hoa_thong_gio',
            'gach_hoa_thong_gio',
        );

        $this->seedFromData('gach_hoa_thong_gio', GachHoaThongGio::class);
        $this->seedFromData('gia_tri_gach_hoa_thong_gio', GiaTriGachHoaThongGio::class);

        $galleryRows = array_slice($this->seederData('gach_hoa_thong_gio_anh'), 0, 10);
        Model::unguarded(function () use ($galleryRows): void {
            foreach ($galleryRows as $row) {
                unset($row['created_at'], $row['updated_at']);
                GachHoaThongGioAnh::create($row);
            }
        });

        $this->seedFromData('gach_hoa_thong_gio_ct', GachHoaThongGioCt::class);
    }
}
