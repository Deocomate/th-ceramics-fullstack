<?php

namespace Database\Seeders;

use App\Models\DanhMucTinTuc;
use App\Models\TinTuc;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class TinTucSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->truncateTables('tin_tuc', 'danh_muc_tin_tuc');
        $this->seedFromData('danh_muc_tin_tuc', DanhMucTinTuc::class);
        $this->seedFromData('tin_tuc', TinTuc::class);
    }
}
