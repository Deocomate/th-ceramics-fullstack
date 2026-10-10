<?php

namespace Database\Seeders;

use App\Domains\Content\Infrastructure\Models\CoreValue;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class CoreValueSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        CoreValue::truncate();
        $this->seedFromData('gia_tri_vuot_troi', CoreValue::class);
    }
}
