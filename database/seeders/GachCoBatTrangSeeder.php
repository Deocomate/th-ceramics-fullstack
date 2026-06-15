<?php

namespace Database\Seeders;

use App\Models\GachCoBatTrang;
use App\Models\GachCoBatTrangAnh;
use App\Models\GachCoBatTrangCt;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class GachCoBatTrangSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->truncateTables('gach_co_bat_trang_ct', 'gach_co_bat_trang_anh', 'gach_co_bat_trang');
        $this->seedFromData('gach_co_bat_trang', GachCoBatTrang::class);
        $this->seedFromData('gach_co_bat_trang_anh', GachCoBatTrangAnh::class);
        $this->seedFromData('gach_co_bat_trang_ct', GachCoBatTrangCt::class);
    }
}
