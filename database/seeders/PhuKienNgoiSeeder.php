<?php

namespace Database\Seeders;

use App\Models\PhuKienNgoi;
use Database\Seeders\Support\CanonicalProductSeeding;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class PhuKienNgoiSeeder extends Seeder
{
    use SeedsFromSqlData;
    use CanonicalProductSeeding;

    public function run(): void
    {
        $this->seedFromData('phu_kien_ngoi', PhuKienNgoi::class);
        $this->seedCanonicalProductType('phu_kien_ngoi_ct', 'phu_kien_ngoi_ct_id', false);
        $this->seedCanonicalVariants('phan_loai_phu_kien_ngoi_ct', 'phan_loai_phu_kien_ngoi_ct_id', 'phu_kien_ngoi_ct_id', 'phu_kien_ngoi_ct');
    }
}
