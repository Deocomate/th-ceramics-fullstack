<?php

namespace Database\Seeders;

use App\Domains\Catalog\Infrastructure\Models\NgoiAmDuong;
use Database\Seeders\Support\CanonicalProductSeeding;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class YinYangRoofTileSeeder extends Seeder
{
    use CanonicalProductSeeding;
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->seedFromData('ngoi_am_duong', NgoiAmDuong::class);
        $this->seedCanonicalProductType('ngoi_am_duong_ct', 'ngoi_am_duong_ct_id', true);
        $this->seedCanonicalDisplayOptions('mau_sac_ngoi_am_duong_ct', 'mau_sac_ngoi_am_duong_ct_id', 'ngoi_am_duong_ct');
    }
}
