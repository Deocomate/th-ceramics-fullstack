<?php

namespace Database\Seeders;

use App\Models\GiaiThuongThanhTuu;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class GiaiThuongThanhTuuSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        GiaiThuongThanhTuu::truncate();
        $this->seedFromData('giai_thuong_thanh_tuu', GiaiThuongThanhTuu::class);
    }
}
