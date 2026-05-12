<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            HomeSeeder::class,
            GiaiThuongThanhTuuSeeder::class,
            UserSeeder::class,
            DinhMucSeeder::class,
            PageConfigSeeder::class,
            ProductTypeSeeder::class,
            ProductDetailSeeder::class,
            DuAnSeeder::class,
            CatalogSeeder::class,
            VeChungToiSeeder::class,
            TinTucSeeder::class,
        ]);
    }
}
