<?php

namespace Database\Seeders;

use App\Models\GachHoaThongGio;
use App\Models\GachHoaThongGioAnh;
use App\Models\GiaTriGachHoaThongGio;
use Database\Seeders\Support\CanonicalProductSeeding;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class GachThongGioSeeder extends Seeder
{
    use SeedsFromSqlData;
    use CanonicalProductSeeding;

    public function run(): void
    {
        $this->seedFromData('gach_hoa_thong_gio', GachHoaThongGio::class);
        $this->seedFromData('gia_tri_gach_hoa_thong_gio', GiaTriGachHoaThongGio::class);

        $galleryRows = array_slice($this->seederData('gach_hoa_thong_gio_anh'), 0, 10);
        Model::unguarded(function () use ($galleryRows): void {
            foreach ($galleryRows as $row) {
                unset($row['created_at'], $row['updated_at']);
                GachHoaThongGioAnh::create($row);
            }
        });

        $this->seedCanonicalProductType('gach_hoa_thong_gio_ct', 'gach_hoa_thong_gio_ct_id', true);
    }
}
