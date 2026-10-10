<?php

namespace Database\Seeders;

use App\Domains\Content\Infrastructure\Models\AboutPageConfig;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class AboutPageConfigSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        AboutPageConfig::truncate();

        $row = $this->withoutTimestamps($this->seederDataFirst('ve_chung_toi') ?? []);

        AboutPageConfig::create($row);
    }
}
