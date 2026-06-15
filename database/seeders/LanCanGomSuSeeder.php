<?php

namespace Database\Seeders;

use App\Models\LanCanGomSuCt;
use App\Models\LanCanGomXu;
use App\Models\PhanLoaiLanCanGomSuCt;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class LanCanGomSuSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->truncateTables('phan_loai_lan_can_gom_su_ct', 'lan_can_gom_su_ct', 'lan_can_gom_xu');
        $this->seedFromData('lan_can_gom_xu', LanCanGomXu::class);
        $this->seedFromData('lan_can_gom_su_ct', LanCanGomSuCt::class);
        $this->seedFromData('phan_loai_lan_can_gom_su_ct', PhanLoaiLanCanGomSuCt::class);
    }
}
