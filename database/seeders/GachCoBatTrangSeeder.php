<?php

namespace Database\Seeders;

use App\Models\GachCoBatTrang;
use App\Models\GachCoBatTrangAnh;
use Database\Seeders\Support\CanonicalProductSeeding;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class GachCoBatTrangSeeder extends Seeder
{
    use SeedsFromSqlData;
    use CanonicalProductSeeding;

    public function run(): void
    {
        $this->seedFromData('gach_co_bat_trang', GachCoBatTrang::class);
        $this->seedFromData('gach_co_bat_trang_anh', GachCoBatTrangAnh::class);
        $this->seedCanonicalProductType('gach_co_bat_trang_ct', 'gach_co_bat_trang_ct_id', true);
    }
}
