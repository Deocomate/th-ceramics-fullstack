<?php

namespace Database\Seeders;

use App\Domains\Content\Infrastructure\Models\InstallationGuide;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class InstallationGuideSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        InstallationGuide::truncate();
        $this->seedFromData('thi_cong', InstallationGuide::class);
    }
}
