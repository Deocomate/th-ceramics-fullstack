<?php

namespace Database\Seeders;

use App\Models\TrangDuAn;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class TrangDuAnSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        TrangDuAn::truncate();
        $this->seedFromData('trang_du_an', TrangDuAn::class);
    }
}
