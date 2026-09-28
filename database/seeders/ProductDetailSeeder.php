<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductDetailSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            NgoiAmDuongSeeder::class,
            NgoiHaiSeeder::class,
            GachThongGioSeeder::class,
            GachTrangTriSeeder::class,
            GachCoBatTrangSeeder::class,
            PhuKienNgoiSeeder::class,
            LanCanGomSuSeeder::class,
            LinhVatPhongThuySeeder::class,
            DenGomSuSeeder::class,
        ]);
    }
}
