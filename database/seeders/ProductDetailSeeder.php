<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductDetailSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            YinYangRoofTileSeeder::class,
            FishScaleRoofTileSeeder::class,
            BreezeBlockSeeder::class,
            DecorativeTileSeeder::class,
            BatTrangAntiqueBrickSeeder::class,
            RoofTileAccessorySeeder::class,
            CeramicBalustradeSeeder::class,
            FengShuiCreatureSeeder::class,
            CeramicLampSeeder::class,
        ]);
    }
}
