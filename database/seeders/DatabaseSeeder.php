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
            GiaiThuongThanhTuuSeeder::class,
            UserSeeder::class,
            DinhMucSeeder::class,
            PageConfigSeeder::class,
            GiaTriVuotTroiSeeder::class,

            NgoiAmDuongSeeder::class,
            NgoiHaiSeeder::class,
            GachThongGioSeeder::class,
            GachTrangTriSeeder::class,
            GachCoBatTrangSeeder::class,
            PhuKienNgoiSeeder::class,
            LanCanGomSuSeeder::class,
            LinhVatPhongThuySeeder::class,
            DenGomSuSeeder::class,

            DuAnSeeder::class,
            TrangDuAnSeeder::class,
            CatalogSeeder::class,
            VeChungToiSeeder::class,
            TinTucSeeder::class,
            ThiCongSeeder::class,
        ]);
    }
}
