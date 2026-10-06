<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Order: home/content → users → dinh muc → page config → values → products → projects → about/news.
     */
    public function run(): void
    {
        $this->call([
            HomeSeeder::class,
            AwardAchievementSeeder::class,
            UserSeeder::class,
            UsageNormSeeder::class,
            PageConfigSeeder::class,
            CoreValueSeeder::class,

            YinYangRoofTileSeeder::class,
            FishScaleRoofTileSeeder::class,
            BreezeBlockSeeder::class,
            DecorativeTileSeeder::class,
            BatTrangAntiqueBrickSeeder::class,
            RoofTileAccessorySeeder::class,
            CeramicBalustradeSeeder::class,
            FengShuiCreatureSeeder::class,
            CeramicLampSeeder::class,

            ProjectSeeder::class,
            ProjectPageConfigSeeder::class,
            CatalogSeeder::class,
            AboutPageConfigSeeder::class,
            NewsArticleSeeder::class,
            InstallationGuideSeeder::class,
        ]);
    }
}
