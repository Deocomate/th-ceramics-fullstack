<?php

namespace Database\Seeders;

use App\Models\GiaTriVuotTroi;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class GiaTriVuotTroiSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        GiaTriVuotTroi::truncate();
        $this->seedFromData('gia_tri_vuot_troi', GiaTriVuotTroi::class);
    }
}
