<?php

namespace Database\Seeders;

use App\Domains\Catalog\Infrastructure\Models\BatTrangAntiqueBrick;
use App\Domains\Catalog\Infrastructure\Models\BatTrangAntiqueBrickImage;
use Database\Seeders\Support\CanonicalProductSeeding;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class BatTrangAntiqueBrickSeeder extends Seeder
{
    use CanonicalProductSeeding;
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->seedFromData('gach_co_bat_trang', BatTrangAntiqueBrick::class);
        $this->seedFromData('gach_co_bat_trang_anh', BatTrangAntiqueBrickImage::class);
        $this->seedCanonicalProductType('gach_co_bat_trang_ct', 'gach_co_bat_trang_ct_id', true);
    }
}
