<?php

namespace Database\Seeders;

use App\Domains\Content\Infrastructure\Models\ProjectPageConfig;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class ProjectPageConfigSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        ProjectPageConfig::truncate();
        $this->seedFromData('trang_du_an', ProjectPageConfig::class);
    }
}
