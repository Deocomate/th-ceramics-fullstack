<?php

namespace Database\Seeders;

use App\Models\TrangDuAn;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrangDuAnSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        TrangDuAn::firstOrCreate(
            ['trang_du_an_id' => 1],
            [
                'promo_title' => "Gạch thông\ngió 300x300\nthường",
                'promo_image' => 'assets/images/news-detail-5.png',
                'promo_cta_label' => 'XEM CATALOG',
                'promo_cta_url' => '/san-pham/gach-hoa-thong-gio',
                'promo_enabled' => true,
            ]
        );
    }
}
