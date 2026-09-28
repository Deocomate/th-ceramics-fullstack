<?php

namespace Database\Seeders;

use App\Models\LinhVat;
use App\Models\LinhVatPhongThuy;
use App\Models\LinhVatPhongThuyAnh;
use Database\Seeders\Support\CanonicalProductSeeding;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class LinhVatPhongThuySeeder extends Seeder
{
    use SeedsFromSqlData;
    use CanonicalProductSeeding;

    public function run(): void
    {
        $this->seedFromData('linh_vat_phong_thuy', LinhVatPhongThuy::class);
        $this->seedFromData('linh_vat', LinhVat::class);
        $this->seedFromData('linh_vat_phong_thuy_anh', LinhVatPhongThuyAnh::class);
        $this->seedCanonicalProductType('linh_vat_phong_thuy_ct', 'linh_vat_phong_thuy_ct_id', true);
    }
}
