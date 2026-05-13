<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            HomeAndAboutUsSeeder::class,
            GiaiThuongThanhTuuSeeder::class,
            UserSeeder::class,
            DinhMucSeeder::class,
            PageConfigSeeder::class,
            
            // CÁC SEEDER THEO KẾ HOẠCH BÀI BẢN VÀ ĐẦY ĐỦ 100%:
            NgoiAmDuongSeeder::class,
            NgoiHaiSeeder::class,
            GachThongGioSeeder::class,
            GachTrangTriSeeder::class,
            GachCoBatTrangSeeder::class,
            PhuKienNgoiSeeder::class,
            LanCanGomSuSeeder::class,
            LinhVatPhongThuySeeder::class,
            DenGomSuSeeder::class,
            
            // Các dữ liệu content khác
            DuAnSeeder::class,
            CatalogSeeder::class,
            VeChungToiSeeder::class,
            TinTucSeeder::class,
            ThiCongSeeder::class,
        ]);
    }
}