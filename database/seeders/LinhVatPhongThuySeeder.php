<?php

namespace Database\Seeders;

use App\Models\LinhVat;
use App\Models\LinhVatPhongThuy;
use App\Models\LinhVatPhongThuyAnh;
use App\Models\LinhVatPhongThuyCt;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class LinhVatPhongThuySeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->truncateTables('linh_vat_phong_thuy_ct', 'linh_vat_phong_thuy_anh', 'linh_vat', 'linh_vat_phong_thuy');
        $this->seedFromData('linh_vat_phong_thuy', LinhVatPhongThuy::class);
        $this->seedFromData('linh_vat', LinhVat::class);
        $this->seedFromData('linh_vat_phong_thuy_anh', LinhVatPhongThuyAnh::class);
        $this->seedFromData('linh_vat_phong_thuy_ct', LinhVatPhongThuyCt::class);
    }
}
