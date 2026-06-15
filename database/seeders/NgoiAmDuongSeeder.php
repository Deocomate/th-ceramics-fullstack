<?php

namespace Database\Seeders;

use App\Models\MauSacNgoiAmDuongCt;
use App\Models\NgoiAmDuong;
use App\Models\NgoiAmDuongCt;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class NgoiAmDuongSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->truncateTables('mau_sac_ngoi_am_duong_ct', 'ngoi_am_duong_ct', 'ngoi_am_duong');
        $this->seedFromData('ngoi_am_duong', NgoiAmDuong::class);
        $this->seedFromData('ngoi_am_duong_ct', NgoiAmDuongCt::class);
        $this->seedFromData('mau_sac_ngoi_am_duong_ct', MauSacNgoiAmDuongCt::class);
    }
}
