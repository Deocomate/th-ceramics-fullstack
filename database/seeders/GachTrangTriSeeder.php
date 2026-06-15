<?php

namespace Database\Seeders;

use App\Models\GachTrangTri;
use App\Models\GachTrangTriCt;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class GachTrangTriSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->truncateTables('gach_trang_tri_ct', 'gach_trang_tri');
        $this->seedFromData('gach_trang_tri', GachTrangTri::class);
        $this->seedFromData('gach_trang_tri_ct', GachTrangTriCt::class);
    }
}
