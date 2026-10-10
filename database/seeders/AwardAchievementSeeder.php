<?php

namespace Database\Seeders;

use App\Domains\Content\Infrastructure\Models\AwardAchievement;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class AwardAchievementSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        AwardAchievement::truncate();
        $this->seedFromData('giai_thuong_thanh_tuu', AwardAchievement::class);
    }
}
