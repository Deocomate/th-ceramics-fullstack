<?php

namespace Database\Seeders;

use App\Domains\Catalog\Infrastructure\Models\GachTrangTri;
use Database\Seeders\Support\CanonicalProductSeeding;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class DecorativeTileSeeder extends Seeder
{
    use CanonicalProductSeeding;
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->seedFromData('gach_trang_tri', GachTrangTri::class);
        $this->seedCanonicalProductType('gach_trang_tri_ct', 'gach_trang_tri_ct_id', true);
    }
}
