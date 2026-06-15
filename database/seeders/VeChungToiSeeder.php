<?php

namespace Database\Seeders;

use App\Models\VeChungToi;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class VeChungToiSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        VeChungToi::truncate();

        $row = $this->withoutTimestamps($this->seederDataFirst('ve_chung_toi') ?? []);

        VeChungToi::create($row);
    }
}
