<?php

namespace Database\Seeders;

use App\Models\PhanLoaiPhuKienNgoiCt;
use App\Models\PhuKienNgoi;
use App\Models\PhuKienNgoiCt;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class PhuKienNgoiSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->truncateTables('phan_loai_phu_kien_ngoi_ct', 'phu_kien_ngoi_ct', 'phu_kien_ngoi');
        $this->seedFromData('phu_kien_ngoi', PhuKienNgoi::class);
        $this->seedFromData('phu_kien_ngoi_ct', PhuKienNgoiCt::class);
        $this->seedFromData('phan_loai_phu_kien_ngoi_ct', PhanLoaiPhuKienNgoiCt::class);
    }
}
