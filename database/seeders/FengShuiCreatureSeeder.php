<?php

namespace Database\Seeders;

use App\Domains\Catalog\Infrastructure\Models\LinhVat;
use App\Domains\Catalog\Infrastructure\Models\LinhVatPhongThuy;
use App\Domains\Catalog\Infrastructure\Models\LinhVatPhongThuyAnh;
use Database\Seeders\Support\CanonicalProductSeeding;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class FengShuiCreatureSeeder extends Seeder
{
    use CanonicalProductSeeding;
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->seedFromData('linh_vat_phong_thuy', LinhVatPhongThuy::class);
        $this->seedFromData('linh_vat', LinhVat::class);
        $this->seedFromData('linh_vat_phong_thuy_anh', LinhVatPhongThuyAnh::class);
        $this->seedCanonicalProductType('linh_vat_phong_thuy_ct', 'linh_vat_phong_thuy_ct_id', true);
    }
}
