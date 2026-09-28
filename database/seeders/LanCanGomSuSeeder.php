<?php

namespace Database\Seeders;

use App\Models\LanCanGomXu;
use Database\Seeders\Support\CanonicalProductSeeding;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class LanCanGomSuSeeder extends Seeder
{
    use SeedsFromSqlData;
    use CanonicalProductSeeding;

    public function run(): void
    {
        $this->seedFromData('lan_can_gom_xu', LanCanGomXu::class);
        $this->seedCanonicalProductType('lan_can_gom_su_ct', 'lan_can_gom_su_ct_id', false);
        $this->seedCanonicalVariants('phan_loai_lan_can_gom_su_ct', 'phan_loai_lan_can_gom_su_ct_id', 'lan_can_gom_su_ct_id', 'lan_can_gom_su_ct');
    }
}
