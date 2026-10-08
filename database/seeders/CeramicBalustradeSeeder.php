<?php

namespace Database\Seeders;

use App\Domains\Catalog\Infrastructure\Models\CeramicBalustrade;
use Database\Seeders\Support\CanonicalProductSeeding;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class CeramicBalustradeSeeder extends Seeder
{
    use CanonicalProductSeeding;
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->seedFromData('lan_can_gom_xu', CeramicBalustrade::class);
        $this->seedCanonicalProductType('lan_can_gom_su_ct', 'lan_can_gom_su_ct_id', false);
        $this->seedCanonicalVariants('phan_loai_lan_can_gom_su_ct', 'phan_loai_lan_can_gom_su_ct_id', 'lan_can_gom_su_ct_id', 'lan_can_gom_su_ct');
    }
}
